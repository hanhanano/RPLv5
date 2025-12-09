<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Exports\PublicationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;

class PublicationExportController extends Controller
{
    // --- Method export (ZIP) dibiarkan tetap ---
    public function export($slug_publication)
    {
        $publication = Publication::with(['stepsplans.stepsFinals.struggles'])->where('slug_publication', $slug_publication)->firstOrFail();

        $excelFileName = sprintf(
            "%s_%s.xlsx",
            str_replace(' ', '_', $publication->publication_name),
            str_replace(' ', '_', $publication->publication_report)
        );
        $excelPath = "exports/{$excelFileName}";
        Excel::store(new PublicationExport($slug_publication), $excelPath);

        $zipFileName = sprintf(
            "%s_%s.zip",
            str_replace(' ', '_', $publication->publication_name),
            str_replace(' ', '_', $publication->publication_report)
        );
        $zipPath = "exports/{$zipFileName}";
        $zip = new ZipArchive;

        if (!Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        if ($zip->open(Storage::path($zipPath), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            
            $excelFullPath = Storage::path($excelPath);
            if (file_exists($excelFullPath)) {
                $zip->addFile($excelFullPath, $excelFileName);
            }

            foreach ($publication->stepsplans as $plan) {
                if ($plan->plan_doc && Storage::disk('public')->exists($plan->plan_doc)) {
                    $filename = Str::slug($plan->plan_type . '_' . $plan->plan_name, '_') 
                        . '.' . pathinfo($plan->plan_doc, PATHINFO_EXTENSION);
                    
                    $zip->addFile(Storage::disk('public')->path($plan->plan_doc), "bukti_dukung_rencana/" . $filename);
                }
                
                if ($plan->stepsFinals) {
                    $final = $plan->stepsFinals;
                    if ($final->final_doc && Storage::disk('public')->exists($final->final_doc)) {
                        $filename = Str::slug($plan->plan_type . '_' . $plan->plan_name, '_') 
                            . '.' . pathinfo($plan->plan_doc, PATHINFO_EXTENSION);
                        
                        $zip->addFile(Storage::disk('public')->path($final->final_doc), "bukti_dukung_realisasi/" . $filename);
                    }
                    foreach ($final->struggles as $struggle) {
                        if ($struggle->solution_doc && Storage::disk('public')->exists($struggle->solution_doc)) {
                            $filename = Str::slug($plan->plan_type . '_' . $plan->plan_name, '_') 
                                . '.' . pathinfo($plan->plan_doc, PATHINFO_EXTENSION);
                            
                            $zip->addFile(Storage::disk('public')->path($struggle->solution_doc), "bukti_dukung_kendala_solusi/" . $filename);
                        }
                    }
                }
            }
            $zip->close();
        }

        if (Storage::exists($zipPath)) {
            return Storage::download($zipPath);
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }
    }

    // --- EXPORT TABLE (DENGAN PERBAIKAN TAMPILAN MERGE) ---
    public function exportTable()
    {
        $year = session('selected_year', now()->year);

        $masterReports = [
            "Laporan Statistik Kependudukan dan Ketenagakerjaan",
            "Laporan Statistik Statistik Kesejahteraan Rakyat",
            "Laporan Statistik Ketahanan Sosial",
            "Laporan Statistik Tanaman Pangan",
            "Laporan Statistik Peternakan, Perikanan, dan Kehutanan",
            "Laporan Statistik Industri",
            "Laporan Statistik Distribusi",
            "Laporan Statistik Harga",
            "Laporan Statistik Keuangan, Teknologi Informasi, dan Pariwisata",
            "Laporan Neraca Produksi",
            "Laporan Neraca Pengeluaran",
            "Laporan Analisis dan Pengembangan Statistik",
            "Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar",
            "Indeks Pelayanan Publik - Penilaian Mandiri",
            "Nilai SAKIP oleh Inspektorat",
            "Indeks Implementasi BerAKHLAK"
        ];

        // 1. QUERY DATA
        $dbData = Publication::with(['teamTarget', 'publicationPlans', 'stepsPlans.stepsFinals'])
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy('publication_report');

        $laporanKinerja = [];
        $allReportNames = collect($masterReports)->merge($dbData->keys())->unique();

        // 2. LOGIKA PERHITUNGAN
        foreach ($allReportNames as $reportName) {
            $items = $dbData->get($reportName) ?? collect([]);

            // A. DATA RAW
            $targetTahapanPlanQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            $targetTahapanRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $realTahapanRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            $outputPlanTotal   = 0;
            $targetOutputRealQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $realOutputRawQ    = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            foreach ($items as $pub) {
                // Realisasi Tahapan (Steps)
                if ($pub->stepsPlans) {
                    foreach ($pub->stepsPlans as $step) {
                        $date = $step->stepsFinals->actual_started ?? null;
                        if ($date && $q = $this->getQuarter($date)) $realTahapanRawQ[$q]++;
                    }
                }
                // Realisasi Output (Pub Plans)
                if ($pub->publicationPlans) {
                    foreach ($pub->publicationPlans as $plan) {
                        $date = $plan->actual_date ?? null;
                        if ($date && $q = $this->getQuarter($date)) $realOutputRawQ[$q]++;
                    }
                }
                // Target
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

            // B. KUMULATIF
            $realTahapanKumulatif = [];
            $realOutputKumulatif = [];
            $runT = 0; $runO = 0;
            
            for ($i=1; $i<=4; $i++) {
                $runT += $realTahapanRawQ[$i];
                $realTahapanKumulatif[$i] = $runT;

                $runO += $realOutputRawQ[$i];
                $realOutputKumulatif[$i] = $runO;
            }

            // C. DATA BARIS
            $row1_Blue  = $targetTahapanPlanQ; 
            $row1_Green = $realTahapanKumulatif;
            $row2_Blue  = $targetTahapanPlanQ;
            $row2_Green = $targetTahapanRealQ;

            $row3_Blue = [];
            for($i=1; $i<=4; $i++) $row3_Blue[$i] = $outputPlanTotal;
            $row3_Green = $realOutputKumulatif;
            $row4_Blue = $row3_Blue;
            $row4_Green = $targetOutputRealQ;

            // D. CAPAIAN
            $capaian = ['tahapan' => [], 'output' => []];
            
            $denom_Tahapan_THN = ($targetTahapanPlanQ[4] > 0) ? ($targetTahapanRealQ[4] / $targetTahapanPlanQ[4]) : 0;
            $denom_Output_THN = ($outputPlanTotal > 0) ? ($targetOutputRealQ[4] / $outputPlanTotal) : 0;

            for ($i = 1; $i <= 4; $i++) {
                // TAHAPAN
                $num_TW_T = ($targetTahapanPlanQ[$i] > 0) ? ($realTahapanKumulatif[$i] / $targetTahapanPlanQ[$i]) : 0;
                $den_TW_T = ($targetTahapanPlanQ[$i] > 0) ? ($targetTahapanRealQ[$i] / $targetTahapanPlanQ[$i]) : 0;
                $raw_Tahapan_TW = ($den_TW_T > 0) ? ($num_TW_T / $den_TW_T) * 100 : 0;
                $capaian['tahapan']['tw'][$i] = ($raw_Tahapan_TW > 120) ? 120 : $raw_Tahapan_TW;

                $num_THN_T = ($targetTahapanPlanQ[$i] > 0) ? ($realTahapanKumulatif[$i] / $targetTahapanPlanQ[$i]) : 0;
                $raw_Tahapan_THN = ($denom_Tahapan_THN > 0) ? ($num_THN_T / $denom_Tahapan_THN) * 100 : 0;
                $capaian['tahapan']['thn'][$i] = ($raw_Tahapan_THN > 120) ? 120 : $raw_Tahapan_THN;

                // OUTPUT
                $num_TW_O = ($outputPlanTotal > 0) ? ($realOutputKumulatif[$i] / $outputPlanTotal) : 0;
                $den_TW_O = ($outputPlanTotal > 0) ? ($targetOutputRealQ[$i] / $outputPlanTotal) : 0;
                $raw_Output_TW = ($den_TW_O > 0) ? ($num_TW_O / $den_TW_O) * 100 : 0;
                $capaian['output']['tw'][$i] = ($raw_Output_TW > 120) ? 120 : $raw_Output_TW;

                $num_THN_O = ($outputPlanTotal > 0) ? ($realOutputKumulatif[$i] / $outputPlanTotal) : 0;
                $raw_Output_THN = ($denom_Output_THN > 0) ? ($num_THN_O / $denom_Output_THN) * 100 : 0;
                $capaian['output']['thn'][$i] = ($raw_Output_THN > 120) ? 120 : $raw_Output_THN;
            }

            $laporanKinerja[] = [
                'report_name' => $reportName,
                'row1_blue'  => $row1_Blue,
                'row1_green' => $row1_Green,
                'row2_blue'  => $row2_Blue,
                'row2_green' => $row2_Green,
                'row3_blue'  => $row3_Blue,
                'row3_green' => $row3_Green,
                'row4_blue'  => $row4_Blue,
                'row4_green' => $row4_Green,
                'capaian'    => $capaian
            ];
        }

        // 3. GENERATE EXCEL DENGAN PHPSPREADSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Header Setup ---
        $sheet->mergeCells('A1:A3')->setCellValue('A1', 'Nama Sasaran/Laporan');
        $sheet->mergeCells('B1:B3')->setCellValue('B1', 'Jenis');
        
        $sheet->mergeCells('C1:F2')->setCellValue('C1', 'Rencana Kegiatan'); // Merge 2 baris (Kumulatif/Diskrit)
        $sheet->mergeCells('G1:J2')->setCellValue('G1', 'Realisasi Kegiatan'); // Merge 2 baris

        $sheet->mergeCells('K1:R1')->setCellValue('K1', 'Capaian Kinerja (%)');
        $sheet->mergeCells('K2:N2')->setCellValue('K2', 'Terhadap Target Triwulanan');
        $sheet->mergeCells('O2:R2')->setCellValue('O2', 'Terhadap Target Setahun');

        $headersTW = ['TW I', 'TW II', 'TW III', 'TW IV'];
        
        // Header Triwulan (Baris 3)
        foreach($headersTW as $idx => $txt) $sheet->setCellValue(chr(67+$idx).'3', $txt);
        foreach($headersTW as $idx => $txt) $sheet->setCellValue(chr(71+$idx).'3', $txt);
        foreach($headersTW as $idx => $txt) $sheet->setCellValue(chr(75+$idx).'3', $txt);
        foreach($headersTW as $idx => $txt) $sheet->setCellValue(chr(79+$idx).'3', $txt);

        // Styling Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']]
        ];
        $sheet->getStyle('A1:R3')->applyFromArray($headerStyle);
        $sheet->getStyle('C1')->getFont()->getColor()->setRGB('1E3A8A');
        $sheet->getStyle('G1')->getFont()->getColor()->setRGB('064E3B');
        $sheet->getStyle('K1')->getFont()->getColor()->setRGB('581C87');

        // --- Isi Data ---
        $row = 4;
        foreach ($laporanKinerja as $item) {
            $startRow = $row;
            
            // Nama Laporan
            $sheet->mergeCells("A{$row}:A".($row+3));
            $sheet->setCellValue("A{$row}", $item['report_name']);
            $sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            // --- TAHAPAN (Baris 1 & 2) ---
            
            // 1. Realisasi Tahapan (Row 1 - Blue Bg)
            $sheet->setCellValue("B{$row}", "Realisasi Tahapan");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row1_blue'][$i] ?? 0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row1_green'][$i] ?? 0);
            
            // Isi Capaian (Akan di-merge ke bawah)
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['tahapan']['tw'][$i], 0).'%');
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['tahapan']['thn'][$i], 0).'%');
            
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFF6FF');
            $row++;

            // 2. Target Tahapan (Row 2 - White Bg)
            $sheet->setCellValue("B{$row}", "Target Tahapan");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row2_blue'][$i] ?? 0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row2_green'][$i] ?? 0);
            // Capaian dikosongkan (sudah diisi di baris atasnya)
            $row++;

            // ** MERGE CAPAIAN TAHAPAN KE BAWAH (Col K - R) **
            for ($c = 75; $c <= 82; $c++) { // Col K (75) s.d R (82)
                $colChar = chr($c);
                $sheet->mergeCells("{$colChar}{$startRow}:{$colChar}".($startRow+1));
            }


            // --- OUTPUT (Baris 3 & 4) ---

            // 3. Realisasi Output (Row 3 - Purple Bg)
            $sheet->setCellValue("B{$row}", "Realisasi Output");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row3_blue'][$i] ?? 0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row3_green'][$i] ?? 0);
            
            // Isi Capaian (Akan di-merge ke bawah)
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['output']['tw'][$i], 0).'%');
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['output']['thn'][$i], 0).'%');

            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FAF5FF');
            $row++;

            // 4. Target Output (Row 4 - White Bg)
            $sheet->setCellValue("B{$row}", "Target Output");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row4_blue'][$i] ?? 0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row4_green'][$i] ?? 0);
            // Capaian dikosongkan
            $row++;

            // ** MERGE CAPAIAN OUTPUT KE BAWAH (Col K - R) **
            // Start Row Output adalah $startRow + 2
            $outputStartRow = $startRow + 2;
            for ($c = 75; $c <= 82; $c++) { // Col K (75) s.d R (82)
                $colChar = chr($c);
                $sheet->mergeCells("{$colChar}{$outputStartRow}:{$colChar}".($outputStartRow+1));
            }
        }

        // Global Styling
        $lastRow = $row - 1;
        $sheet->getStyle("A1:R{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        // Center alignment untuk semua data (kecuali Kolom A sudah diatur top-left sebelumnya)
        $sheet->getStyle("B4:R{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        
        // AutoSize
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('A')->setWidth(40);

        $fileName = 'laporan_capaian_kinerja_'. $year .'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }

    private function getQuarter($date)
    {
        if (!$date) return null;
        try {
            return ceil(Carbon::parse($date)->month / 3);
        } catch (\Exception $e) {
            return null;
        }
    }
}