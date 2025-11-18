<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StepsFinal extends Model
{
    use HasFactory;

    protected $table = 'steps_finals';
    protected $primaryKey = 'step_final_id';
    
    protected $fillable = [
        'actual_started',
        'actual_ended',
        'final_desc',
        'final_doc',
        'next_step',
        'step_plan_id'
    ];

    protected $casts = [
        'actual_started' => 'date',
        'actual_ended' => 'date'
    ];

    // Relasi: Steps Final dimiliki oleh satu Steps Plan
    public function stepsPlan()
    {
        return $this->belongsTo(StepsPlan::class, 'step_plan_id', 'step_plan_id');
    }

    // Relasi: Steps Final memiliki banyak Struggles
    public function struggles()
    {
        return $this->hasMany(Struggle::class, 'step_final_id', 'step_final_id');
    }
}