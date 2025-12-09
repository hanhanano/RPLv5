<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Carbon\Carbon;

class CapaianKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $year = session('selected_year', now()->year);

        // 1. DEFINISI MAPPING: SASARAN STRATEGIS => DAFTAR LAPORAN (DB)
        $sasaranStrategis = [
            "Terwujudnya Penyediaan Data dan Insight Statistik Kependudukan dan Ketenagakerjaan yang Berkualitas" => [
                "Laporan Statistik Kependudukan dan Ketenagakerjaan"
            ],
            "Terwujudnya Penyediaan Data dan Insight Statistik Kesejahteraan Rakyat yang Berkualitas" => [
                "Laporan Statistik Statistik Kesejahteraan Rakyat"
            ],
            "Terwujudnya penyediaan Data dan Insight Statistik Ketahanan Sosial yang Berkualitas" => [
                "Laporan Statistik Ketahanan Sosial"
            ],
            "Terwujudnya Penyediaan Data dan Insight Statistik Tanaman Pangan, Hortikultura, dan Perkebunan yang Berkualitas" => [
                "Laporan Statistik Tanaman Pangan"
            ],
            "Terwujudnya Penyediaan Data dan Insight Statistik Peternakan, Perikanan, dan Kehutanan yang Berkualitas" => [
                "Laporan Statistik Peternakan, Perikanan, dan Kehutanan"
            ],
            "Terwujudnya penyediaan Data dan Insight Statistik Industri yang Berkualitas" => [
                "Laporan Statistik Industri"
            ],
            "Terwujudnya Penyediaan Data dan Insight Statistik Distribusi yang Berkualitas" => [
                "Laporan Statistik Distribusi"
            ],
            "Terwujudnya Penyediaan Data dan Insight Statistik Harga yang Berkualitas" => [
                "Laporan Statistik Harga"
            ],
            "Terwujudnya Penyediaan Data dan Insight Statistik Keuangan, Teknologi Informasi, dan Pariwisata yang Berkualitas" => [
                "Laporan Statistik Keuangan, Teknologi Informasi, dan Pariwisata"
            ],
            // PENGGABUNGAN 3 LAPORAN
            "Terwujudnya Penyediaan Data dan Insight Statistik Lintas Sektor yang Berkualitas" => [
                "Laporan Neraca Produksi",
                "Laporan Neraca Pengeluaran",
                "Laporan Analisis dan Pengembangan Statistik"
            ],
            "Terwujudnya Penguatan Penyelenggaraan Pembinaan Statistik Sektoral K/L/Pemda" => [
                "Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar"
            ],
            "Terwujudnya Kemudahan Akses Data Bps" => [
                "Indeks Pelayanan Publik - Penilaian Mandiri"
            ],
            // PENGGABUNGAN 2 LAPORAN
            "Terwujudnya Dukungan Manajemen pada BPS Provinsi dan BPS Kabupaten/Kota" => [
                "Nilai SAKIP oleh Inspektorat",
                "Indeks Implementasi BerAKHLAK"
            ]
        ];

        // 2. QUERY DATA (Tetap Sama)
        $dbData = Publication::with(['teamTarget', 'publicationPlans', 'stepsPlans.stepsFinals'])
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy('publication_report');

        $laporanKinerja = [];

        // 3. LOOP BERDASARKAN SASARAN STRATEGIS
        foreach ($sasaranStrategis as $namaSasaran => $daftarLaporan) {
            
            // --- A. INISIALISASI VARIABEL TOTAL (Per Sasaran) ---
            $targetTahapanPlanQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            $targetTahapanRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $realTahapanRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            $outputPlanTotal   = 0;
            $targetOutputRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $realOutputRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            // Loop setiap Laporan yang tergabung dalam Sasaran ini
            foreach ($daftarLaporan as $reportName) {
                // Ambil data dari DB berdasarkan nama laporan asli
                $items = $dbData->get($reportName) ?? collect([]);

                foreach ($items as $pub) {
                    // 1. Realisasi Tahapan (Steps)
                    if ($pub->stepsPlans) {
                        foreach ($pub->stepsPlans as $step) {
                            $date = $step->stepsFinals->actual_started ?? null;
                            if ($date && $q = $this->getQuarter($date)) $realTahapanRawQ[$q]++;
                        }
                    }

                    // 2. Realisasi Output (Publication Plans)
                    if ($pub->publicationPlans) {
                        foreach ($pub->publicationPlans as $plan) {
                            $date = $plan->actual_date ?? null;
                            if ($date && $q = $this->getQuarter($date)) $realOutputRawQ[$q]++;
                        }
                    }

                    // 3. Target (TeamTarget) - Dijumlahkan
                    if ($pub->teamTarget) {
                        $t = $pub->teamTarget;
                        // Tahapan
                        $targetTahapanPlanQ[1] += $t->q1_plan ?? 0;
                        $targetTahapanPlanQ[2] += $t->q2_plan ?? 0;
                        $targetTahapanPlanQ[3] += $t->q3_plan ?? 0;
                        $targetTahapanPlanQ[4] += $t->q4_plan ?? 0;

                        $targetTahapanRealQ[1] += $t->q1_real ?? 0;
                        $targetTahapanRealQ[2] += $t->q2_real ?? 0;
                        $targetTahapanRealQ[3] += $t->q3_real ?? 0;
                        $targetTahapanRealQ[4] += $t->q4_real ?? 0;

                        // Output
                        $outputPlanTotal += $t->output_plan ?? 0;
                        $targetOutputRealQ[1] += $t->output_real_q1 ?? 0;
                        $targetOutputRealQ[2] += $t->output_real_q2 ?? 0;
                        $targetOutputRealQ[3] += $t->output_real_q3 ?? 0;
                        $targetOutputRealQ[4] += $t->output_real_q4 ?? 0;
                    }
                }
            } // End Loop Laporan per Sasaran

            // --- B. KUMULATIF (Sama seperti sebelumnya, tapi sekarang angkanya gabungan) ---
            $realTahapanKumulatif = [];
            $realOutputKumulatif = [];
            $runT = 0; 
            $runO = 0;
            
            for ($i=1; $i<=4; $i++) {
                $runT += $realTahapanRawQ[$i];
                $realTahapanKumulatif[$i] = $runT;

                $runO += $realOutputRawQ[$i];
                $realOutputKumulatif[$i] = $runO;
            }

            // --- C. DATA BARIS ---
            $row1_Blue  = $targetTahapanPlanQ; 
            $row1_Green = $realTahapanKumulatif;
            $row2_Blue  = $targetTahapanPlanQ;
            $row2_Green = $targetTahapanRealQ;

            $row3_Blue = [];
            for($i=1; $i<=4; $i++) $row3_Blue[$i] = $outputPlanTotal;
            $row3_Green = $realOutputKumulatif;
            $row4_Blue = $row3_Blue;
            $row4_Green = $targetOutputRealQ;

            // --- D. HITUNG PERSENTASE (LOGIKA & RUMUS TETAP SAMA) ---
            $capaian = ['tahapan' => [], 'output' => []];

            // Denominator THN (Berdasarkan TW IV)
            $denom_Tahapan_THN = ($targetTahapanPlanQ[4] > 0) ? ($targetTahapanRealQ[4] / $targetTahapanPlanQ[4]) : 0;
            $denom_Output_THN = ($outputPlanTotal > 0) ? ($targetOutputRealQ[4] / $outputPlanTotal) : 0;

            for ($i = 1; $i <= 4; $i++) {
                // TAHAPAN
                // TW
                $num_TW_T = ($targetTahapanPlanQ[$i] > 0) ? ($realTahapanKumulatif[$i] / $targetTahapanPlanQ[$i]) : 0;
                $den_TW_T = ($targetTahapanPlanQ[$i] > 0) ? ($targetTahapanRealQ[$i] / $targetTahapanPlanQ[$i]) : 0;
                $raw_Tahapan_TW = ($den_TW_T > 0) ? ($num_TW_T / $den_TW_T) * 100 : 0;
                $capaian['tahapan']['tw'][$i] = ($raw_Tahapan_TW > 120) ? 120 : $raw_Tahapan_TW;

                // THN
                $num_THN_T = ($targetTahapanPlanQ[$i] > 0) ? ($realTahapanKumulatif[$i] / $targetTahapanPlanQ[$i]) : 0;
                $raw_Tahapan_THN = ($denom_Tahapan_THN > 0) ? ($num_THN_T / $denom_Tahapan_THN) * 100 : 0;
                $capaian['tahapan']['thn'][$i] = ($raw_Tahapan_THN > 120) ? 120 : $raw_Tahapan_THN;

                // OUTPUT
                // TW
                $num_TW_O = ($outputPlanTotal > 0) ? ($realOutputKumulatif[$i] / $outputPlanTotal) : 0;
                $den_TW_O = ($outputPlanTotal > 0) ? ($targetOutputRealQ[$i] / $outputPlanTotal) : 0;
                $raw_Output_TW = ($den_TW_O > 0) ? ($num_TW_O / $den_TW_O) * 100 : 0;
                $capaian['output']['tw'][$i] = ($raw_Output_TW > 120) ? 120 : $raw_Output_TW;

                // THN
                $num_THN_O = ($outputPlanTotal > 0) ? ($realOutputKumulatif[$i] / $outputPlanTotal) : 0;
                $raw_Output_THN = ($denom_Output_THN > 0) ? ($num_THN_O / $denom_Output_THN) * 100 : 0;
                $capaian['output']['thn'][$i] = ($raw_Output_THN > 120) ? 120 : $raw_Output_THN;
            }

            // Simpan ke array hasil
            $laporanKinerja[] = [
                'report_name' => $namaSasaran, // NAMA SASARAN MUNCUL DISINI
                'data' => [
                    'row1_blue'  => $row1_Blue,
                    'row1_green' => $row1_Green,
                    'row2_blue'  => $row2_Blue,
                    'row2_green' => $row2_Green,
                    'row3_blue'  => $row3_Blue,
                    'row3_green' => $row3_Green,
                    'row4_blue'  => $row4_Blue,
                    'row4_green' => $row4_Green,
                ],
                'capaian' => $capaian
            ];
        }

        return view('tampilan.capaian_kinerja', compact('laporanKinerja', 'year'));
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