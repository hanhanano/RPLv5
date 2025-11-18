<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationFile extends Model
{
    use HasFactory;

    protected $table = 'publication_files';
    protected $primaryKey = 'file_id';

    protected $fillable = [
        'publication_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    // Relasi: File ini milik satu Publication
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }

    // Accessor: Ukuran file dalam format human-readable     
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Accessor: Icon berdasarkan file type
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => '📄',
            'xlsx' => '📊',
            'xls' => '📊',
            'docx' => '📝',
            'doc' => '📝',
            'zip' => '🗜️',
        ];

        return $icons[$this->file_type] ?? '📎';
    }
}