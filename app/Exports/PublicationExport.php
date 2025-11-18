<?php

namespace App\Exports;

use App\Models\Publication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class PublicationExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting 
{
    protected $publicationSlug;

    public function __construct($publicationSlug)
    {
        $this->publicationSlug = $publicationSlug;
    }
    
    public function collection()
    {
        // Ambil data publikasi berdasarkan slug
        $publication = Publication::with(['stepsplans.stepsFinals.struggles'])
            ->where('slug_publication', $this->publicationSlug)
            ->firstOrFail();

        $rows = collect();

        // Tambahkan baris untuk setiap plan
        foreach ($publication->stepsplans as $plan) {
            $final = $plan->stepsFinals;
            if ($final) {
                // Jika ada struggles, buat baris untuk setiap struggle
                if ($final->struggles->count()) {
                    foreach ($final->struggles as $struggle) {
                        $rows->push([
                            $publication->publication_report,
                            $publication->publication_name,
                            $plan->plan_type,
                            $plan->plan_name,
                            $this->formatDate($plan->plan_start_date),
                            $this->formatDate($plan->plan_end_date),
                            $this->formatDate($final->actual_started),
                            $this->formatDate($final->actual_ended),
                            $plan->plan_desc,
                            $final->final_desc,
                            $struggle->struggle_desc,
                            $struggle->solution_desc,
                            $final->next_step,
                            $publication->publication_pic,
                            $final->actual_ended
                        ]);
                    }
                } else {
                    // Jika tidak ada struggles, buat satu baris kosong
                    $rows->push([
                        $publication->publication_report,
                        $publication->publication_name,
                        $plan->plan_type,
                        $plan->plan_name,
                        $this->formatDate($plan->plan_start_date),
                        $this->formatDate($plan->plan_end_date),
                        $this->formatDate($final->actual_started),
                        $this->formatDate($final->actual_ended),
                        $plan->plan_desc,
                        $final->final_desc,
                        null, // Struggle kosong
                        null, // Solution kosong
                        $final->next_step,
                        $publication->publication_pic,
                        $final->actual_ended
                    ]);
                }
            } else {
                 // Jika tidak ada stepsFinals, buat satu baris kosong
                $rows->push([
                    $publication->publication_report,
                    $publication->publication_name,
                    $plan->plan_type,
                    $plan->plan_name,
                    $this->formatDate($plan->plan_start_date),
                    $this->formatDate($plan->plan_end_date),
                    null,
                    null,
                    $plan->plan_desc,
                    null,
                    null,
                    null,
                    null,
                    $publication->publication_pic,
                    null
                ]);
            }
        }

        return $rows;
    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->translatedFormat('d F Y') : null;
        // contoh output: 01 Oktober 2025
    }

    public function headings(): array
    {
        return [
            'Nama Publikasi/Laporan',
            'Nama Kegiatan',
            'Tipe Tahapan',
            'Nama tahapan',
            'Tanggal Awal Tahapan',
            'Tanggal Akhir Tahapan',
            'Tanggal Awal Realisasi',
            'Tanggal Akhir Realisasi',
            'Rincian Rencana Kegiatan',
            'Rincian Realisasi Kegiatan',
            'Kendala Kegiatan',
            'Solusi Kegiatan',
            'Rencana Tindak Lanjut',
            'PIC Tindak Lanjut',
            'Batas Waktu Tindak Lanjut'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = max(1, $sheet->getHighestRow());

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', $sheet->getHighestColumn()) as $lastColumn) {
            $sheet->getColumnDimension($lastColumn)->setAutoSize(true);
        }

        $sheet->getStyle('A1:O1')->getFont()->setBold(true);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            // Auto-convert tanggal ke format default Excel
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}