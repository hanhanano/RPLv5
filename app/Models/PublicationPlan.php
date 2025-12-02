<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke Publication
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }
}