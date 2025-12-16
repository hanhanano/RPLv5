<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamTarget extends Model
{
    use HasFactory;

    public const SPECIAL_REPORTS = [
        'Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar',
        'Indeks Pelayanan Publik - Penilaian Mandiri',
        'Nilai SAKIP oleh Inspektorat',
        'Indeks Implementasi BerAKHLAK',
    ];

    protected $fillable = [
        'team_name',
        'activity_name',
        'report_name',
        'publication_id',
        
        // Tahapan
        'q1_plan', 'q2_plan', 'q3_plan', 'q4_plan',
        'q1_real', 'q2_real', 'q3_real', 'q4_real',
        
        // Output
        'output_plan',
        'output_real',
        'output_real_q1', 'output_real_q2', 'output_real_q3', 'output_real_q4',
        
        // Realisasi Output untuk Indikator Spesial
        'actual_output_q1', 'actual_output_q2', 'actual_output_q3', 'actual_output_q4',
    ];

    protected $casts = [
        'output_real_q1' => 'decimal:2', // 2 digit desimal
        'output_real_q2' => 'decimal:2',
        'output_real_q3' => 'decimal:2',
        'output_real_q4' => 'decimal:2',
        'actual_output_q1' => 'decimal:2',
        'actual_output_q2' => 'decimal:2',
        'actual_output_q3' => 'decimal:2',
        'actual_output_q4' => 'decimal:2',
        'is_special_indicator' => 'boolean',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    /**
     * Cek apakah laporan ini termasuk indikator spesial
     */
    public static function isSpecialReport($reportName): bool
    {
        return in_array($reportName, self::SPECIAL_REPORTS);
    }
}
