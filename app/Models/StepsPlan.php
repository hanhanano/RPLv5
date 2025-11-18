<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StepsPlan extends Model
{
    use HasFactory;

    protected $table = 'steps_plans';
    protected $primaryKey = 'step_plan_id';
    
    protected $fillable = [
        'plan_type',
        'plan_name',
        'plan_start_date',
        'plan_end_date',
        'plan_desc',
        'plan_doc',
        'publication_id',
        'plan_quarter',
        'slug_plan',
    ];

    protected $casts = [
        'plan_start_date' => 'date',
        'plan_end_date' => 'date'
    ];

    // Relasi: Steps Plan dimiliki oleh satu Publication
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    // Relasi: Steps Plan memiliki satu Steps Finals
    public function stepsFinals()
    {
        return $this->hasOne(StepsFinal::class, 'step_plan_id', 'step_plan_id');
    }
}