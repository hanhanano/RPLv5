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

        // 1. Ambil Data (Query disamakan dengan PublicationController)
        $query = Publication::with([
            'user',
            'stepsPlans.stepsFinals.struggles', // Gunakan stepsPlans sesuai controller asli
            'files'
        ]);

        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        $publications = $query->get();

        // 2. Lakukan Perhitungan Statistik (Logika disamakan PERSIS dengan PublicationController)
        foreach ($publications as $publication) {
            $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $lintasTriwulan = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $tepatWaktu = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            $terlambat = [1 => 0, 2 => 0, 3 => 0, 4 => 0];  
            
            $listPlans = [1 => [], 2 => [], 3 => [], 4 => []];
            $listFinals = [1 => [], 2 => [], 3 => [], 4 => []];
            $listLintas = [1 => [], 2 => [], 3 => [], 4 => []];

            // Loop Tahapan
            foreach ($publication->stepsPlans as $plan) {
                // Gunakan fungsi helper private di bawah
                $q = $this->getQuarter($plan->plan_start_date);
                
                if ($q) {
                    // LOGIKA KUMULATIF: tambahkan ke quarter ini dan semua quarter setelahnya
                    for ($i = $q; $i <= 4; $i++) {
                        $rekapPlans[$i]++;
                        $listPlans[$i][] = $plan->plan_name;
                    }
                }
                
                if ($plan->stepsFinals) {
                    $fq = $this->getQuarter($plan->stepsFinals->actual_started);
                    
                    if ($fq) {
                        // KUMULATIF Realisasi
                        for ($i = $fq; $i <= 4; $i++) {
                            $rekapFinals[$i]++;
                            $listFinals[$i][] = $plan->plan_name;
                        }

                        // Cek Tepat Waktu / Terlambat
                        if ($q && $fq <= $q) {
                            // Tepat waktu
                            for ($i = $fq; $i <= 4; $i++) {
                                $tepatWaktu[$i]++;
                            }
                        } else {
                            // Terlambat
                            for ($i = $fq; $i <= 4; $i++) {
                                $terlambat[$i]++;
                                $lintasTriwulan[$i]++;
                                
                                // Detail lintas triwulan (hanya di Q kejadian)
                                if ($i == $fq) {
                                    $listLintas[$i][] = [
                                        'plan_name' => $plan->plan_name,
                                        'from_quarter' => $q,
                                        'to_quarter' => $fq,
                                    ];
                                }
                            }
                        }
                    }
                }
            }        

            // Hitung Progress Kumulatif Total
            $totalPlans = array_sum($rekapPlans); // Note: ini jadi sangat besar karena kumulatif, tapi logika controller aslinya begini
            // Jika ingin total unik, gunakan count($publication->stepsPlans)
            // Tapi kita ikuti logic controller asli:
            $totalPlansKumulatifQ4 = $rekapPlans[4]; // Ambil data Q4 sebagai total akhir
            $totalFinalsKumulatifQ4 = $rekapFinals[4];

            $publication->progressKumulatif = ($totalPlansKumulatifQ4 > 0) 
                ? ($totalFinalsKumulatifQ4 / $totalPlansKumulatifQ4) * 100 
                : 0;

            // Progress per triwulan
            $progressTriwulan = [];
            foreach ([1, 2, 3, 4] as $q) {
                if ($rekapPlans[$q] > 0) {
                    $progressTriwulan[$q] = ($rekapFinals[$q] / $rekapPlans[$q]) * 100;
                } else {
                    $progressTriwulan[$q] = 0;
                }
            }

            // Simpan data ke object publication untuk dikirim ke View
            $publication->rekapPlans = $rekapPlans;
            $publication->rekapFinals = $rekapFinals;
            $publication->lintasTriwulan = $lintasTriwulan;
            $publication->progressTriwulan = $progressTriwulan;
            $publication->listPlans = $listPlans;
            $publication->listFinals = $listFinals;
            $publication->listLintas = $listLintas;
            
            // Tambahan untuk kolom Output (File)
            $publication->filesCount = $publication->files->count();
            // Mapping untuk tooltip output jika diperlukan
            $publication->publicationPlansList = $publication->stepsPlans->map(function($p) {
                return (object) [
                    'name' => $p->plan_name,
                    'hasFinal' => !empty($p->stepsFinals), // cek relasi stepsFinals
                    'planDate' => $p->plan_start_date,
                    'actualDate' => $p->stepsFinals->actual_started ?? null
                ];
            });
        }

        // 3. Kirim ke View
        return view('tampilan.laporan', compact('publications'));
    }

    /**
     * Helper Function: Mendapatkan Kuartal (1-4) dari tanggal
     */
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