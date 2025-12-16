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

        // 1. DEFINISI MAPPING - INDIKATOR NORMAL
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
            "Terwujudnya Penyediaan Data dan Insight Statistik Lintas Sektor yang Berkualitas" => [
                "Laporan Neraca Produksi",
                "Laporan Neraca Pengeluaran",
                "Laporan Analisis dan Pengembangan Statistik"
            ]
        ];

        $sasaranSpesial = [
            "Terwujudnya Penguatan Penyelenggaraan Pembinaan Statistik Sektoral K/L/Pemda" => [
                "Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar"
            ],
            "Terwujudnya Kemudahan Akses Data BPS" => [
                "Indeks Pelayanan Publik - Penilaian Mandiri"
            ],
            "Terwujudnya Dukungan Manajemen pada BPS Provinsi dan BPS Kabupaten/Kota" => [
                "Nilai SAKIP oleh Inspektorat",
                "Indeks Implementasi BerAKHLAK"
            ]
        ];

        // 3. QUERY DATA
        $dbData = Publication::with(['teamTarget', 'publicationPlans', 'stepsPlans.stepsFinals'])
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy('publication_report');

        // ARRAY HASIL
        $laporanKinerjaIndikator = [];
        $laporanKinerjaSasaran = [];
        
        $laporanKinerjaSpesialIndikator = [];
        $laporanKinerjaSpesialSasaran = [];

        // PROSES INDIKATOR NORMAL
        foreach ($sasaranStrategis as $namaSasaran => $daftarLaporan) {
            
            $agg_targetTahapanPlanQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            $agg_targetTahapanRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $agg_realTahapanRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            $agg_outputPlanTotal   = 0;
            $agg_targetOutputRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $agg_realOutputRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            foreach ($daftarLaporan as $reportName) {
                
                $ind_targetTahapanPlanQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
                $ind_targetTahapanRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                $ind_realTahapanRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

                $ind_outputPlanTotal   = 0;
                $ind_targetOutputRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                $ind_realOutputRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

                $items = $dbData->get($reportName) ?? collect([]);

                foreach ($items as $pub) {
                    // 1. Realisasi Tahapan (dari steps_plans)
                    if ($pub->stepsPlans) {
                        foreach ($pub->stepsPlans as $step) {
                            $date = $step->stepsFinals->actual_started ?? null;
                            if ($date && $q = $this->getQuarter($date)) {
                                $agg_realTahapanRawQ[$q]++;
                                $ind_realTahapanRawQ[$q]++;
                            }
                        }
                    }
                    // 2. Realisasi Output (dari publication_plans)
                    if ($pub->publicationPlans) {
                        foreach ($pub->publicationPlans as $plan) {
                            $date = $plan->actual_date ?? null;
                            if ($date && $q = $this->getQuarter($date)) {
                                $agg_realOutputRawQ[$q]++;
                                $ind_realOutputRawQ[$q]++;
                            }
                        }
                    }
                    // 3. Target
                    if ($pub->teamTarget) {
                        $t = $pub->teamTarget;
                        
                        $agg_targetTahapanPlanQ[1] += $t->q1_plan ?? 0; $agg_targetTahapanPlanQ[2] += $t->q2_plan ?? 0; $agg_targetTahapanPlanQ[3] += $t->q3_plan ?? 0; $agg_targetTahapanPlanQ[4] += $t->q4_plan ?? 0;
                        $agg_targetTahapanRealQ[1] += $t->q1_real ?? 0; $agg_targetTahapanRealQ[2] += $t->q2_real ?? 0; $agg_targetTahapanRealQ[3] += $t->q3_real ?? 0; $agg_targetTahapanRealQ[4] += $t->q4_real ?? 0;
                        $agg_outputPlanTotal += $t->output_plan ?? 0;
                        $agg_targetOutputRealQ[1] += $t->output_real_q1 ?? 0; $agg_targetOutputRealQ[2] += $t->output_real_q2 ?? 0; $agg_targetOutputRealQ[3] += $t->output_real_q3 ?? 0; $agg_targetOutputRealQ[4] += $t->output_real_q4 ?? 0;

                        $ind_targetTahapanPlanQ[1] += $t->q1_plan ?? 0; $ind_targetTahapanPlanQ[2] += $t->q2_plan ?? 0; $ind_targetTahapanPlanQ[3] += $t->q3_plan ?? 0; $ind_targetTahapanPlanQ[4] += $t->q4_plan ?? 0;
                        $ind_targetTahapanRealQ[1] += $t->q1_real ?? 0; $ind_targetTahapanRealQ[2] += $t->q2_real ?? 0; $ind_targetTahapanRealQ[3] += $t->q3_real ?? 0; $ind_targetTahapanRealQ[4] += $t->q4_real ?? 0;
                        $ind_outputPlanTotal += $t->output_plan ?? 0;
                        $ind_targetOutputRealQ[1] += $t->output_real_q1 ?? 0; $ind_targetOutputRealQ[2] += $t->output_real_q2 ?? 0; $ind_targetOutputRealQ[3] += $t->output_real_q3 ?? 0; $ind_targetOutputRealQ[4] += $t->output_real_q4 ?? 0;
                    }
                }

                $laporanKinerjaSasaran[] = $this->calculateRowData(
                    $reportName, 
                    $ind_targetTahapanPlanQ, $ind_targetTahapanRealQ, $ind_realTahapanRawQ,
                    $ind_outputPlanTotal, $ind_targetOutputRealQ, $ind_realOutputRawQ,
                    false // isSpecial = false
                );
            }

            $laporanKinerjaIndikator[] = $this->calculateRowData(
                $namaSasaran,
                $agg_targetTahapanPlanQ, $agg_targetTahapanRealQ, $agg_realTahapanRawQ,
                $agg_outputPlanTotal, $agg_targetOutputRealQ, $agg_realOutputRawQ,
                false // isSpecial = false
            );
        }

        foreach ($sasaranSpesial as $namaSasaran => $daftarLaporan) {
            
            // Agregat untuk level indikator
            $agg_targetOutputQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; // TARGET poin per TW
            $agg_actualOutputQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; // REALISASI poin per TW

            foreach ($daftarLaporan as $reportName) {
                
                // Individual untuk level laporan
                $ind_targetOutputQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                $ind_actualOutputQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

                $items = $dbData->get($reportName) ?? collect([]);

                foreach ($items as $pub) {
                    if ($pub->teamTarget) {
                        $t = $pub->teamTarget;
                        
                        // TARGET poin per TW (dari output_real_q1-q4)
                        $ind_targetOutputQ[1] += $t->output_real_q1 ?? 0;
                        $ind_targetOutputQ[2] += $t->output_real_q2 ?? 0;
                        $ind_targetOutputQ[3] += $t->output_real_q3 ?? 0;
                        $ind_targetOutputQ[4] += $t->output_real_q4 ?? 0;

                        // REALISASI poin per TW (dari actual_output_q1-q4)
                        $ind_actualOutputQ[1] += $t->actual_output_q1 ?? 0;
                        $ind_actualOutputQ[2] += $t->actual_output_q2 ?? 0;
                        $ind_actualOutputQ[3] += $t->actual_output_q3 ?? 0;
                        $ind_actualOutputQ[4] += $t->actual_output_q4 ?? 0;

                        // Agregat
                        $agg_targetOutputQ[1] += $t->output_real_q1 ?? 0;
                        $agg_targetOutputQ[2] += $t->output_real_q2 ?? 0;
                        $agg_targetOutputQ[3] += $t->output_real_q3 ?? 0;
                        $agg_targetOutputQ[4] += $t->output_real_q4 ?? 0;

                        $agg_actualOutputQ[1] += $t->actual_output_q1 ?? 0;
                        $agg_actualOutputQ[2] += $t->actual_output_q2 ?? 0;
                        $agg_actualOutputQ[3] += $t->actual_output_q3 ?? 0;
                        $agg_actualOutputQ[4] += $t->actual_output_q4 ?? 0;
                    }
                }

                // Hitung capaian untuk laporan individual
                $laporanKinerjaSpesialSasaran[] = $this->calculateSpecialRowData(
                    $reportName,
                    $ind_targetOutputQ,
                    $ind_actualOutputQ
                );
            }

            // Hitung capaian untuk level indikator (agregat)
            $laporanKinerjaSpesialIndikator[] = $this->calculateSpecialRowData(
                $namaSasaran,
                $agg_targetOutputQ,
                $agg_actualOutputQ
            );
        }

        return view('tampilan.capaian_kinerja', compact(
            'laporanKinerjaIndikator', 
            'laporanKinerjaSasaran',
            'laporanKinerjaSpesialIndikator',
            'laporanKinerjaSpesialSasaran',
            'year'
        ));
    }

    private function calculateSpecialRowData($name, $targetQ, $actualQ) 
    {
        // Untuk indikator spesial:
        // - Tidak ada tahapan (hanya output/poin)
        // - Target berbeda tiap TW
        // - Realisasi dari input manual

        $capaian = ['output' => ['tw' => [], 'thn' => []]];

        // Rumus Capaian TW = (Realisasi Q / Target Q) * 100
        // Rumus Capaian THN = sama dengan rumus indikator normal
        
        $targetQ4 = $targetQ[4] ?? 0;
        
        for ($i = 1; $i <= 4; $i++) {
            $target = $targetQ[$i] ?? 0;
            $actual = $actualQ[$i] ?? 0;

            // Capaian per TW
            if ($target > 0) {
                $raw_TW = ($actual / $target) * 100;
                $capaian['output']['tw'][$i] = ($raw_TW > 120) ? 120 : $raw_TW;
            } else {
                $capaian['output']['tw'][$i] = 0;
            }

            // Capaian terhadap Target Akhir Tahun (Q4)
            if ($targetQ4 > 0) {
                $raw_THN = ($actual / $targetQ4) * 100;
                $capaian['output']['thn'][$i] = ($raw_THN > 120) ? 120 : $raw_THN;
            } else {
                $capaian['output']['thn'][$i] = 0;
            }
        }

        return [
            'report_name' => $name,
            'is_special' => true,
            'data' => [
                // Row 1: Target vs Realisasi (langsung tampil, bukan hitung kumulatif)
                'target_q' => $targetQ,
                'actual_q' => $actualQ,
            ],
            'capaian' => $capaian
        ];
    }

    // Helper untuk menghitung baris indikator NORMAL (tidak berubah)
    private function calculateRowData($name, $tPlanQ, $tRealQ, $rRawQ, $oPlanTotal, $oTargetRealQ, $oRawQ, $isSpecial = false) {
        
        // 1. Kumulatif
        $rKumulatif = [];
        $oKumulatif = [];
        $runT = 0; $runO = 0;
        for ($i=1; $i<=4; $i++) {
            $runT += $rRawQ[$i]; $rKumulatif[$i] = $runT;
            $runO += $oRawQ[$i]; $oKumulatif[$i] = $runO;
        }

        // 2. Susun Baris
        $row1_Blue = $tPlanQ;
        $row1_Green = $rKumulatif;
        $row2_Blue = $tPlanQ;
        $row2_Green = $tRealQ;
        
        $row3_Blue = [];
        for($i=1; $i<=4; $i++) $row3_Blue[$i] = $oPlanTotal;
        $row3_Green = $oKumulatif;
        $row4_Blue = $row3_Blue;
        $row4_Green = $oTargetRealQ;

        // 3. Hitung Persentase
        $capaian = ['tahapan' => [], 'output' => []];
        $denom_Tahapan_THN = ($tPlanQ[4] > 0) ? ($tRealQ[4] / $tPlanQ[4]) : 0;
        $denom_Output_THN = ($oPlanTotal > 0) ? ($oTargetRealQ[4] / $oPlanTotal) : 0;

        for ($i = 1; $i <= 4; $i++) {
            // Tahapan
            $num_TW_T = ($tPlanQ[$i] > 0) ? ($rKumulatif[$i] / $tPlanQ[$i]) : 0;
            $den_TW_T = ($tPlanQ[$i] > 0) ? ($tRealQ[$i] / $tPlanQ[$i]) : 0;
            $raw_T_TW = ($den_TW_T > 0) ? ($num_TW_T / $den_TW_T) * 100 : 0;
            $capaian['tahapan']['tw'][$i] = ($raw_T_TW > 120) ? 120 : $raw_T_TW;

            $num_THN_T = ($tPlanQ[$i] > 0) ? ($rKumulatif[$i] / $tPlanQ[$i]) : 0;
            $raw_T_THN = ($denom_Tahapan_THN > 0) ? ($num_THN_T / $denom_Tahapan_THN) * 100 : 0;
            $capaian['tahapan']['thn'][$i] = ($raw_T_THN > 120) ? 120 : $raw_T_THN;

            // Output
            $num_TW_O = ($oPlanTotal > 0) ? ($oKumulatif[$i] / $oPlanTotal) : 0;
            $den_TW_O = ($oPlanTotal > 0) ? ($oTargetRealQ[$i] / $oPlanTotal) : 0;
            $raw_O_TW = ($den_TW_O > 0) ? ($num_TW_O / $den_TW_O) * 100 : 0;
            $capaian['output']['tw'][$i] = ($raw_O_TW > 120) ? 120 : $raw_O_TW;

            $num_THN_O = ($oPlanTotal > 0) ? ($oKumulatif[$i] / $oPlanTotal) : 0;
            $raw_O_THN = ($denom_Output_THN > 0) ? ($num_THN_O / $denom_Output_THN) * 100 : 0;
            $capaian['output']['thn'][$i] = ($raw_O_THN > 120) ? 120 : $raw_O_THN;
        }

        return [
            'report_name' => $name,
            'is_special' => $isSpecial,
            'data' => [
                'row1_blue' => $row1_Blue, 'row1_green' => $row1_Green,
                'row2_blue' => $row2_Blue, 'row2_green' => $row2_Green,
                'row3_blue' => $row3_Blue, 'row3_green' => $row3_Green,
                'row4_blue' => $row4_Blue, 'row4_green' => $row4_Green,
            ],
            'capaian' => $capaian
        ];
    }

    private function getQuarter($date)
    {
        if (empty($date)) return null;
        try {
            return ceil(Carbon::parse($date)->month / 3);
        } catch (\Exception $e) {
            return null;
        }
    }
}
