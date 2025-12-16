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
    // --- Method export Excel ---
    public function export($slug_publication)
    {
        $publication = Publication::where('slug_publication', $slug_publication)->firstOrFail();

        $fileName = sprintf(
            "%s_%s.xlsx",
            str_replace(' ', '_', $publication->publication_name),
            str_replace(' ', '_', $publication->publication_report)
        );

        return Excel::download(new \App\Exports\PublicationExport($slug_publication), $fileName);
    }

    // --- EXPORT TABLE ---
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
            "Laporan Analisis dan Pengembangan Statistik"
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

        // return response()->streamDownload(function () use ($writer) {
        //     $writer->save('php://output');
        // }, $fileName);
        return $this->generateExcel($year, $this->getDataIndikator($year), 'laporan_kinerja_indikator');
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

    public function exportTableSasaran()
    {
        $year = session('selected_year', now()->year);
        // Ambil data normal
        $dataNormal = $this->getDataSasaran($year);
        // Ambil data spesial
        $dataSpesial = $this->getDataSpesialSasaran($year);
        
        // Generate Excel gabungan
        return $this->generateExcelSasaranGabungan($year, $dataNormal, $dataSpesial, 'laporan_kinerja_sasaran');
    }

    private function generateExcelSasaranGabungan($year, $laporanNormal, $laporanSpesial, $fileNamePrefix) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Header Setup ---
        $sheet->mergeCells('A1:A3')->setCellValue('A1', 'Nama Sasaran/Laporan');
        $sheet->mergeCells('B1:B3')->setCellValue('B1', 'Jenis');
        $sheet->mergeCells('C1:F2')->setCellValue('C1', 'Rencana Kegiatan');
        $sheet->mergeCells('G1:J2')->setCellValue('G1', 'Realisasi Kegiatan');
        $sheet->mergeCells('K1:R1')->setCellValue('K1', 'Capaian Kinerja (%)');
        $sheet->mergeCells('K2:N2')->setCellValue('K2', 'Terhadap Target Triwulanan');
        $sheet->mergeCells('O2:R2')->setCellValue('O2', 'Terhadap Target Setahun');

        $headersTW = ['TW I', 'TW II', 'TW III', 'TW IV'];
        foreach($headersTW as $idx => $txt) {
            $sheet->setCellValue(chr(67+$idx).'3', $txt);
            $sheet->setCellValue(chr(71+$idx).'3', $txt);
            $sheet->setCellValue(chr(75+$idx).'3', $txt);
            $sheet->setCellValue(chr(79+$idx).'3', $txt);
        }

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']]
        ];
        $sheet->getStyle('A1:R3')->applyFromArray($headerStyle);

        $row = 4;

        // ========== BAGIAN 1: DATA NORMAL (4 baris per item) ==========
        foreach ($laporanNormal as $item) {
            $startRow = $row;
            
            // Nama
            $sheet->mergeCells("A{$row}:A".($row+3));
            $sheet->setCellValue("A{$row}", $item['report_name']);
            $sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            // Row 1: Realisasi Tahapan
            $sheet->setCellValue("B{$row}", "Realisasi Tahapan");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row1_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row1_green'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['tahapan']['tw'][$i],0).'%');
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['tahapan']['thn'][$i],0).'%');
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFF6FF');
            $row++;

            // Row 2: Target Tahapan
            $sheet->setCellValue("B{$row}", "Target Tahapan");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row2_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row2_green'][$i]??0);
            $row++;
            
            // Merge Capaian Row 1-2
            for ($c = 75; $c <= 82; $c++) $sheet->mergeCells(chr($c)."{$startRow}:".chr($c).($startRow+1));

            // Row 3: Realisasi Output
            $outputStartRow = $row;
            $sheet->setCellValue("B{$row}", "Realisasi Output");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row3_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row3_green'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['output']['tw'][$i],0).'%');
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['output']['thn'][$i],0).'%');
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FAF5FF');
            $row++;

            // Row 4: Target Output
            $sheet->setCellValue("B{$row}", "Target Output");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row4_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row4_green'][$i]??0);
            $row++;

            // Merge Capaian Row 3-4
            for ($c = 75; $c <= 82; $c++) $sheet->mergeCells(chr($c)."{$outputStartRow}:".chr($c).($outputStartRow+1));
        }

        // ========== BAGIAN 2: DATA SPESIAL (2 baris per item) ==========
        // Tambah separator/header untuk bagian spesial
        $sheet->mergeCells("A{$row}:R{$row}");
        $sheet->setCellValue("A{$row}", "INDIKATOR SPESIAL");
        $sheet->getStyle("A{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF3C7');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        foreach ($laporanSpesial as $item) {
            $startRow = $row;
            
            // Nama Laporan - merge 2 baris
            $sheet->mergeCells("A{$row}:A".($row+1));
            $sheet->setCellValue("A{$row}", $item['report_name'] . ' (Spesial)');
            $sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            // Row 1: Target Poin
            // Row 1: Poin dan data (baris atas)
            $sheet->setCellValue("B{$row}", "Poin");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, number_format($item['target_q'][$i] ?? 0, 2)); // C-F: Target
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, number_format($item['actual_q'][$i] ?? 0, 2)); // G-J: Realisasi
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['output']['tw'][$i], 1).'%'); // K-N: Capaian TW
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['output']['thn'][$i], 1).'%'); // O-R: Capaian THN
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF3C7');

            // Row 2: Data capaian (baris bawah)
            $row++;
            $sheet->setCellValue("B{$row}", ""); // Kolom B kosong
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, ''); // C-F kosong
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, ''); // G-J kosong
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, ''); // K-N: Capaian TW
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, ''); // O-R: Capaian THN
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ECFDF5');

            // Merge vertikal kolom B-J (B sampai J)
            for($col = 66; $col <= 74; $col++) { // 66=B, 74=J
                $sheet->mergeCells(chr($col).($row-1).":".chr($col).$row);
            }

            $row++;

            // Merge Capaian Row 1-2
            for ($c = 75; $c <= 82; $c++) {
                $colChar = chr($c);
                $sheet->mergeCells("{$colChar}{$startRow}:{$colChar}".($startRow+1));
            }
        }

        // Global Styling
        $lastRow = $row - 1;
        $sheet->getStyle("A1:R{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B4:R{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        foreach (range('A', 'R') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getColumnDimension('A')->setWidth(40);

        $fileName = $fileNamePrefix.'_'.$year.'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }

    // --- EXPORT TABLE ---
    public function exportTableIndikator()
    {
        $year = session('selected_year', now()->year);
        $data = $this->getDataIndikator($year);
        return $this->generateExcel($year, $data, 'laporan_kinerja_indikator');
    }

    private function generateExcel($year, $laporanKinerja, $fileNamePrefix) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Header Setup (Sama seperti sebelumnya) ---
        $sheet->mergeCells('A1:A3')->setCellValue('A1', 'Nama Sasaran/Laporan');
        $sheet->mergeCells('B1:B3')->setCellValue('B1', 'Jenis');
        $sheet->mergeCells('C1:F2')->setCellValue('C1', 'Rencana Kegiatan');
        $sheet->mergeCells('G1:J2')->setCellValue('G1', 'Realisasi Kegiatan');
        $sheet->mergeCells('K1:R1')->setCellValue('K1', 'Capaian Kinerja (%)');
        $sheet->mergeCells('K2:N2')->setCellValue('K2', 'Terhadap Target Triwulanan');
        $sheet->mergeCells('O2:R2')->setCellValue('O2', 'Terhadap Target Setahun');

        $headersTW = ['TW I', 'TW II', 'TW III', 'TW IV'];
        foreach($headersTW as $idx => $txt) {
            $sheet->setCellValue(chr(67+$idx).'3', $txt);
            $sheet->setCellValue(chr(71+$idx).'3', $txt);
            $sheet->setCellValue(chr(75+$idx).'3', $txt);
            $sheet->setCellValue(chr(79+$idx).'3', $txt);
        }

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']]
        ];
        $sheet->getStyle('A1:R3')->applyFromArray($headerStyle);
        $sheet->getStyle('C1')->getFont()->getColor()->setRGB('1E3A8A');
        $sheet->getStyle('G1')->getFont()->getColor()->setRGB('064E3B');
        $sheet->getStyle('K1')->getFont()->getColor()->setRGB('581C87');

        // Isi Data
        $row = 4;
        foreach ($laporanKinerja as $item) {
            $startRow = $row;
            
            // Nama Laporan - merge 4 baris
            $sheet->mergeCells("A{$row}:A".($row+3));
            $sheet->setCellValue("A{$row}", $item['report_name']);
            $sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            // Row 1: Realisasi Tahapan
            $sheet->setCellValue("B{$row}", "Realisasi Tahapan");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row1_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row1_green'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['tahapan']['tw'][$i],0).'%');
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['tahapan']['thn'][$i],0).'%');
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFF6FF');
            $row++;

            // Row 2: Target Tahapan
            $sheet->setCellValue("B{$row}", "Target Tahapan");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row2_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row2_green'][$i]??0);
            $row++;
            
            // Merge Capaian Row 1-2
            for ($c = 75; $c <= 82; $c++) $sheet->mergeCells(chr($c)."{$startRow}:".chr($c).($startRow+1));

            // Row 3: Realisasi Output
            $outputStartRow = $row;
            $sheet->setCellValue("B{$row}", "Realisasi Output");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row3_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row3_green'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['output']['tw'][$i],0).'%');
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['output']['thn'][$i],0).'%');
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FAF5FF');
            $row++;

            // Row 4: Target Output
            $sheet->setCellValue("B{$row}", "Target Output");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, $item['row4_blue'][$i]??0);
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, $item['row4_green'][$i]??0);
            $row++;

            // Merge Capaian Row 3-4
            for ($c = 75; $c <= 82; $c++) $sheet->mergeCells(chr($c)."{$outputStartRow}:".chr($c).($outputStartRow+1));
        }

        $lastRow = $row - 1;
        $sheet->getStyle("A1:R{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B4:R{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        foreach (range('A', 'R') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getColumnDimension('A')->setWidth(40);

        $fileName = $fileNamePrefix.'_'.$year.'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }

    private function getSasaranStrategis() {
        return [
            "Terwujudnya Penyediaan Data dan Insight Statistik Kependudukan dan Ketenagakerjaan yang Berkualitas" => ["Laporan Statistik Kependudukan dan Ketenagakerjaan"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Kesejahteraan Rakyat yang Berkualitas" => ["Laporan Statistik Statistik Kesejahteraan Rakyat"],
            "Terwujudnya penyediaan Data dan Insight Statistik Ketahanan Sosial yang Berkualitas" => ["Laporan Statistik Ketahanan Sosial"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Tanaman Pangan, Hortikultura, dan Perkebunan yang Berkualitas" => ["Laporan Statistik Tanaman Pangan"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Peternakan, Perikanan, dan Kehutanan yang Berkualitas" => ["Laporan Statistik Peternakan, Perikanan, dan Kehutanan"],
            "Terwujudnya penyediaan Data dan Insight Statistik Industri yang Berkualitas" => ["Laporan Statistik Industri"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Distribusi yang Berkualitas" => ["Laporan Statistik Distribusi"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Harga yang Berkualitas" => ["Laporan Statistik Harga"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Keuangan, Teknologi Informasi, dan Pariwisata yang Berkualitas" => ["Laporan Statistik Keuangan, Teknologi Informasi, dan Pariwisata"],
            "Terwujudnya Penyediaan Data dan Insight Statistik Lintas Sektor yang Berkualitas" => ["Laporan Neraca Produksi", "Laporan Neraca Pengeluaran", "Laporan Analisis dan Pengembangan Statistik"]
        ];
    }

    private function getDataSasaran($year) {
        $dbData = Publication::with(['teamTarget', 'publicationPlans', 'stepsPlans.stepsFinals'])
            ->whereYear('created_at', $year)->get()->groupBy('publication_report');
        
        $result = [];
        foreach ($this->getSasaranStrategis() as $namaSasaran => $daftarLaporan) {
            foreach ($daftarLaporan as $reportName) {
                // LOGIKA PER LAPORAN (INDIVIDUAL)
                $items = $dbData->get($reportName) ?? collect([]);
                $result[] = $this->calculateSingleItem($reportName, $items);
            }
        }
        return $result;
    }

    private function getDataIndikator($year) {
        $dbData = Publication::with(['teamTarget', 'publicationPlans', 'stepsPlans.stepsFinals'])
            ->whereYear('created_at', $year)->get()->groupBy('publication_report');
        
        $result = [];
        foreach ($this->getSasaranStrategis() as $namaSasaran => $daftarLaporan) {
            // LOGIKA AGREGAT (GABUNGAN)
            // Kumpulkan semua items dari semua laporan dalam 1 indikator
            $allItems = collect([]);
            foreach ($daftarLaporan as $reportName) {
                $allItems = $allItems->merge($dbData->get($reportName) ?? collect([]));
            }
            $result[] = $this->calculateSingleItem($namaSasaran, $allItems);
        }
        return $result;
    }

    // Inti Perhitungan (Sama untuk Indikator & Sasaran)
    private function calculateSingleItem($name, $items) {
        // Init Variabel
        $tPlanQ = [1=>0, 2=>0, 3=>0, 4=>0]; $tRealQ = [1=>0, 2=>0, 3=>0, 4=>0]; $rRawQ = [1=>0, 2=>0, 3=>0, 4=>0];
        $oPlanTotal = 0; $oTargetRealQ = [1=>0, 2=>0, 3=>0, 4=>0]; $oRawQ = [1=>0, 2=>0, 3=>0, 4=>0];

        foreach ($items as $pub) {
            if ($pub->stepsPlans) {
                foreach ($pub->stepsPlans as $step) {
                    $date = $step->stepsFinals->actual_started ?? null;
                    if ($date && $q = $this->getQuarter($date)) $rRawQ[$q]++;
                }
            }
            if ($pub->publicationPlans) {
                foreach ($pub->publicationPlans as $plan) {
                    $date = $plan->actual_date ?? null;
                    if ($date && $q = $this->getQuarter($date)) $oRawQ[$q]++;
                }
            }
            if ($pub->teamTarget) {
                $t = $pub->teamTarget;
                $tPlanQ[1] += $t->q1_plan??0; $tPlanQ[2] += $t->q2_plan??0; $tPlanQ[3] += $t->q3_plan??0; $tPlanQ[4] += $t->q4_plan??0;
                $tRealQ[1] += $t->q1_real??0; $tRealQ[2] += $t->q2_real??0; $tRealQ[3] += $t->q3_real??0; $tRealQ[4] += $t->q4_real??0;
                $oPlanTotal += $t->output_plan??0;
                $oTargetRealQ[1] += $t->output_real_q1??0; $oTargetRealQ[2] += $t->output_real_q2??0; $oTargetRealQ[3] += $t->output_real_q3??0; $oTargetRealQ[4] += $t->output_real_q4??0;
            }
        }

        // Kumulatif
        $rKumulatif = []; $oKumulatif = []; $runT = 0; $runO = 0;
        for ($i=1; $i<=4; $i++) {
            $runT += $rRawQ[$i]; $rKumulatif[$i] = $runT;
            $runO += $oRawQ[$i]; $oKumulatif[$i] = $runO;
        }

        // Data Baris
        $row1_Blue = $tPlanQ; $row1_Green = $rKumulatif;
        $row2_Blue = $tPlanQ; $row2_Green = $tRealQ;
        $row3_Blue = []; for($i=1; $i<=4; $i++) $row3_Blue[$i] = $oPlanTotal;
        $row3_Green = $oKumulatif;
        $row4_Blue = $row3_Blue; $row4_Green = $oTargetRealQ;

        // Persentase
        $capaian = ['tahapan'=>['tw'=>[], 'thn'=>[]], 'output'=>['tw'=>[], 'thn'=>[]]];
        $denom_Tahapan_THN = ($tPlanQ[4]>0) ? ($tRealQ[4]/$tPlanQ[4]) : 0;
        $denom_Output_THN = ($oPlanTotal>0) ? ($oTargetRealQ[4]/$oPlanTotal) : 0;

        for ($i=1; $i<=4; $i++) {
            $den_TW_T = ($tPlanQ[$i]>0) ? ($tRealQ[$i]/$tPlanQ[$i]) : 0;
            $raw_T_TW = ($den_TW_T>0) ? (($tPlanQ[$i]>0 ? ($rKumulatif[$i]/$tPlanQ[$i]) : 0) / $den_TW_T)*100 : 0;
            $capaian['tahapan']['tw'][$i] = ($raw_T_TW>120) ? 120 : $raw_T_TW;

            $raw_T_THN = ($denom_Tahapan_THN>0) ? (($tPlanQ[$i]>0 ? ($rKumulatif[$i]/$tPlanQ[$i]) : 0) / $denom_Tahapan_THN)*100 : 0;
            $capaian['tahapan']['thn'][$i] = ($raw_T_THN>120) ? 120 : $raw_T_THN;

            $den_TW_O = ($oPlanTotal>0) ? ($oTargetRealQ[$i]/$oPlanTotal) : 0;
            $raw_O_TW = ($den_TW_O>0) ? (($oPlanTotal>0 ? ($oKumulatif[$i]/$oPlanTotal) : 0) / $den_TW_O)*100 : 0;
            $capaian['output']['tw'][$i] = ($raw_O_TW>120) ? 120 : $raw_O_TW;

            $raw_O_THN = ($denom_Output_THN>0) ? (($oPlanTotal>0 ? ($oKumulatif[$i]/$oPlanTotal) : 0) / $denom_Output_THN)*100 : 0;
            $capaian['output']['thn'][$i] = ($raw_O_THN>120) ? 120 : $raw_O_THN;
        }

        return [
            'report_name' => $name,
            'row1_blue' => $row1_Blue, 'row1_green' => $row1_Green,
            'row2_blue' => $row2_Blue, 'row2_green' => $row2_Green,
            'row3_blue' => $row3_Blue, 'row3_green' => $row3_Green,
            'row4_blue' => $row4_Blue, 'row4_green' => $row4_Green,
            'capaian' => $capaian
        ];
    }

    // Export indikator spesial level INDIKATOR (agregat)
    public function exportTableSpesialIndikator()
    {
        $year = session('selected_year', now()->year);
        $data = $this->getDataSpesialIndikator($year);
        return $this->generateExcelSpesial($year, $data, 'laporan_kinerja_spesial_indikator');
    }

    // Export indikator spesial level SASARAN (per laporan)
    public function exportTableSpesialSasaran()
    {
        $year = session('selected_year', now()->year);
        $data = $this->getDataSpesialSasaran($year);
        return $this->generateExcelSpesial($year, $data, 'laporan_kinerja_spesial_sasaran');
    }

    // Data untuk level INDIKATOR (agregat dari semua laporan dalam 1 indikator)
    private function getDataSpesialIndikator($year) {
        $dbData = Publication::with(['teamTarget'])
            ->whereYear('created_at', $year)->get()->groupBy('publication_report');
        
        $result = [];
        foreach ($this->getSasaranSpesial() as $namaSasaran => $daftarLaporan) {
            $agg_targetQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $agg_actualQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

            foreach ($daftarLaporan as $reportName) {
                $items = $dbData->get($reportName) ?? collect([]);
                foreach ($items as $pub) {
                    if ($pub->teamTarget) {
                        $t = $pub->teamTarget;
                        // Target poin per TW (dari output_real_q1-q4)
                        $agg_targetQ[1] += $t->output_real_q1 ?? 0;
                        $agg_targetQ[2] += $t->output_real_q2 ?? 0;
                        $agg_targetQ[3] += $t->output_real_q3 ?? 0;
                        $agg_targetQ[4] += $t->output_real_q4 ?? 0;
                        // Realisasi poin per TW (dari actual_output_q1-q4)
                        $agg_actualQ[1] += $t->actual_output_q1 ?? 0;
                        $agg_actualQ[2] += $t->actual_output_q2 ?? 0;
                        $agg_actualQ[3] += $t->actual_output_q3 ?? 0;
                        $agg_actualQ[4] += $t->actual_output_q4 ?? 0;
                    }
                }
            }
            $result[] = $this->calculateSpesialItem($namaSasaran, $agg_targetQ, $agg_actualQ);
        }
        return $result;
    }

    // Data untuk level SASARAN (per laporan individual)
    private function getDataSpesialSasaran($year) {
        $dbData = Publication::with(['teamTarget'])
            ->whereYear('created_at', $year)->get()->groupBy('publication_report');
        
        $result = [];
        foreach ($this->getSasaranSpesial() as $namaSasaran => $daftarLaporan) {
            foreach ($daftarLaporan as $reportName) {
                $items = $dbData->get($reportName) ?? collect([]);
                
                $ind_targetQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                $ind_actualQ = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

                foreach ($items as $pub) {
                    if ($pub->teamTarget) {
                        $t = $pub->teamTarget;
                        $ind_targetQ[1] += $t->output_real_q1 ?? 0;
                        $ind_targetQ[2] += $t->output_real_q2 ?? 0;
                        $ind_targetQ[3] += $t->output_real_q3 ?? 0;
                        $ind_targetQ[4] += $t->output_real_q4 ?? 0;
                        
                        $ind_actualQ[1] += $t->actual_output_q1 ?? 0;
                        $ind_actualQ[2] += $t->actual_output_q2 ?? 0;
                        $ind_actualQ[3] += $t->actual_output_q3 ?? 0;
                        $ind_actualQ[4] += $t->actual_output_q4 ?? 0;
                    }
                }
                $result[] = $this->calculateSpesialItem($reportName, $ind_targetQ, $ind_actualQ);
            }
        }
        return $result;
    }

    // Perhitungan capaian untuk indikator spesial
    private function calculateSpesialItem($name, $targetQ, $actualQ) {
        $capaian = ['output' => ['tw' => [], 'thn' => []]];
        $targetQ4 = $targetQ[4] ?? 0;

        for ($i = 1; $i <= 4; $i++) {
            $target = $targetQ[$i] ?? 0;
            $actual = $actualQ[$i] ?? 0;

            // Capaian per TW = (Realisasi / Target TW) * 100
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
            'target_q' => $targetQ,
            'actual_q' => $actualQ,
            'capaian' => $capaian
        ];
    }

    // Generate Excel khusus untuk indikator spesial (hanya 2 baris: Target Poin & Realisasi Poin)
    private function generateExcelSpesial($year, $laporanKinerja, $fileNamePrefix) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Setup (berbeda dari normal karena hanya poin, tidak ada tahapan)
        $sheet->mergeCells('A1:A3')->setCellValue('A1', 'Nama Sasaran/Laporan');
        $sheet->mergeCells('B1:B3')->setCellValue('B1', 'Jenis');
        $sheet->mergeCells('C1:F2')->setCellValue('C1', 'Target Poin');
        $sheet->mergeCells('G1:J2')->setCellValue('G1', 'Realisasi Poin');
        $sheet->mergeCells('K1:R1')->setCellValue('K1', 'Capaian Kinerja (%)');
        $sheet->mergeCells('K2:N2')->setCellValue('K2', 'Terhadap Target Triwulanan');
        $sheet->mergeCells('O2:R2')->setCellValue('O2', 'Terhadap Target Setahun');

        $headersTW = ['TW I', 'TW II', 'TW III', 'TW IV'];
        foreach($headersTW as $idx => $txt) {
            $sheet->setCellValue(chr(67+$idx).'3', $txt);
            $sheet->setCellValue(chr(71+$idx).'3', $txt);
            $sheet->setCellValue(chr(75+$idx).'3', $txt);
            $sheet->setCellValue(chr(79+$idx).'3', $txt);
        }

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']]
        ];
        $sheet->getStyle('A1:R3')->applyFromArray($headerStyle);
        $sheet->getStyle('C1')->getFont()->getColor()->setRGB('1E3A8A');
        $sheet->getStyle('G1')->getFont()->getColor()->setRGB('064E3B');
        $sheet->getStyle('K1')->getFont()->getColor()->setRGB('581C87');

        // Isi Data (hanya 2 baris per item: Target Poin & Realisasi Poin)
        $row = 4;
        foreach ($laporanKinerja as $item) {
            $startRow = $row;
            
            // Nama Laporan - merge 2 baris (bukan 4 seperti normal)
            $sheet->mergeCells("A{$row}:A".($row+1));
            $sheet->setCellValue("A{$row}", $item['report_name']);
            $sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            $sheet->setCellValue("B{$row}", "Poin");
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, number_format($item['target_q'][$i] ?? 0, 2)); // C-F: Target
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, number_format($item['actual_q'][$i] ?? 0, 2)); // G-J: Realisasi
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, number_format($item['capaian']['output']['tw'][$i], 1).'%'); // K-N: Capaian TW
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, number_format($item['capaian']['output']['thn'][$i], 1).'%'); // O-R: Capaian THN
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF3C7');

            // Row 2: Data capaian (baris bawah)
            $row++;
            $sheet->setCellValue("B{$row}", ""); // Kolom B kosong
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(66+$i).$row, ''); // C-F kosong
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(70+$i).$row, ''); // G-J kosong
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(74+$i).$row, ''); // K-N: Capaian TW
            for($i=1; $i<=4; $i++) $sheet->setCellValue(chr(78+$i).$row, ''); // O-R: Capaian THN
            $sheet->getStyle("B{$row}:R{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ECFDF5');

            // Merge vertikal kolom B-J (B sampai J)
            for($col = 66; $col <= 74; $col++) { // 66=B, 74=J
                $sheet->mergeCells(chr($col).($row-1).":".chr($col).$row);
            }

            $row++;

            // Merge Capaian Row 1-2
            for ($c = 75; $c <= 82; $c++) {
                $sheet->mergeCells(chr($c)."{$startRow}:".chr($c).($startRow+1));
            }
        }

        // Global Styling
        $lastRow = $row - 1;
        $sheet->getStyle("A1:R{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B4:R{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        foreach (range('A', 'R') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getColumnDimension('A')->setWidth(50);

        $fileName = $fileNamePrefix.'_'.$year.'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }
    
    // Definisi 3 indikator spesial (4 laporan)
    private function getSasaranSpesial() {
        return [
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
    }
}
