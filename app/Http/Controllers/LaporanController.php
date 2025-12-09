<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. QUERY DATA (Tambahkan 'teamTarget' agar Baris 2 & 4 muncul)
        $query = Publication::with([
            'user',
            'stepsPlans.stepsFinals', // Untuk Baris 1 (Tahapan)
            'files',                  // Untuk Baris 3 (Output)
            'teamTarget'              // PENTING: Untuk Baris 2 & 4 (Target Manual)
        ]);

        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        $publications = $query->get();

        // 2. HITUNG LOGIKA PER BARIS
        foreach ($publications as $pub) {
            
            // --- A. DATA BARIS 1: REALISASI TAHAPAN (OTOMATIS) ---
            $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $listPlans = [1 => [], 2 => [], 3 => [], 4 => []];
            $listFinals = [1 => [], 2 => [], 3 => [], 4 => []];
            $listLintas = [1 => [], 2 => [], 3 => [], 4 => []];
            $lintasTriwulan = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            foreach ($pub->stepsPlans as $plan) {
                // Hitung Triwulan Rencana
                $q = $this->getQuarter($plan->plan_start_date);
                if ($q) {
                    // Logika Akumulasi: Jika rencana di Q2, maka Q2, Q3, Q4 dihitung ada (+1)
                    for ($i = $q; $i <= 4; $i++) {
                        $rekapPlans[$i]++;
                        // Simpan nama rencana hanya di kuartal aslinya untuk tooltip
                        if($i == $q) $listPlans[$i][] = $plan->plan_name;
                    }
                }

                // Hitung Triwulan Realisasi
                if ($plan->stepsFinals) {
                    $fq = $this->getQuarter($plan->stepsFinals->actual_started);
                    if ($fq) {
                        for ($i = $fq; $i <= 4; $i++) {
                            $rekapFinals[$i]++;
                            if($i == $fq) $listFinals[$i][] = $plan->plan_name;
                        }
                        
                        // Cek Lintas Triwulan (Telat nyebrang kuartal)
                        if ($q && $fq > $q) {
                            $lintasTriwulan[$fq]++; // Catat di kuartal kejadian
                            $listLintas[$fq][] = [
                                'plan_name' => $plan->plan_name,
                                'from_quarter' => $q,
                                'to_quarter' => $fq,
                            ];
                        }
                    }
                }
            }

            // --- B. DATA BARIS 3: REALISASI OUTPUT (OTOMATIS) ---
            // Hitung berdasarkan file yang diupload
            $outputRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $hasFile = $pub->files->count() > 0;
            
            if ($hasFile) {
                // Ambil file pertama sebagai patokan tanggal realisasi output
                $firstFile = $pub->files->sortBy('created_at')->first();
                $qFile = $this->getQuarter($firstFile->created_at);
                
                // Akumulasi: Jika upload di Q2, maka Q2, Q3, Q4 nilainya 1
                for ($i = $qFile; $i <= 4; $i++) {
                    $outputRealQ[$i] = 1;
                }
            }

            // --- SIMPAN DATA KE OBJECT ---
            // Tempelkan hasil hitungan ke object $pub supaya bisa dibaca di View
            $pub->rekapPlans = $rekapPlans;
            $pub->rekapFinals = $rekapFinals;
            $pub->listPlans = $listPlans;
            $pub->listFinals = $listFinals;
            $pub->lintasTriwulan = $lintasTriwulan;
            $pub->listLintas = $listLintas;
            $pub->outputRealQ = $outputRealQ; // Data baru untuk Baris 3

            // Hitung Progress Total (%)
            $totalRencana = $rekapPlans[4]; // Ambil kumulatif terakhir
            $totalRealisasi = $rekapFinals[4];
            $pub->progressKumulatif = ($totalRencana > 0) 
                ? ($totalRealisasi / $totalRencana) * 100 
                : 0;
                
            $pub->filesCount = $pub->files->count();
        }

        return view('tampilan.laporan', compact('publications'));
    }

    private function getQuarter($date)
    {
        if (empty($date)) return null;
        try {
            $month = Carbon::parse($date)->month;
            return ceil($month / 3);
        } catch (\Exception $e) {
            return null;
        }
    }
}
