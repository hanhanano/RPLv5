<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struggle extends Model
{
    use HasFactory;

    protected $table = 'struggles';
    protected $primaryKey = 'struggle_id';
    
    protected $fillable = [
        'struggle_desc',
        'solution_desc',
        'solution_doc',
        'step_final_id'
    ];

    // Relasi: Struggle dimiliki oleh satu Steps Final
    public function stepsFinal()
    {
        return $this->belongsTo(StepsFinal::class, 'step_final_id', 'step_final_id');
    }
}