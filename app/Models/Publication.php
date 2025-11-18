<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\StepsPlan;
use App\Models\PublicationFile;

class Publication extends Model
{
    use HasFactory;

    protected $table = 'publications';
    protected $primaryKey = 'publication_id';
    public $incrementing = true;

    protected $keyType = 'string';
    
    protected $fillable = [
        'publication_report',
        'publication_name',
        'publication_pic',
        'fk_user_id',
        'slug_publication',
        'is_monthly',
    ];
    
    // Generate UUID otomatis saat creating 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug_publication)) {
                $model->slug_publication = (string) Str::uuid();
            }
        });
    }

    // Gunakan publication_uuid untuk route-model binding
    public function getRouteKeyName()
    {
        return 'slug_publication';
    }

    // Publication dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user_id', 'id');
    }

    // Publication memiliki banyak Steps Plans
    public function stepsPlans()
    {
        return $this->hasMany(StepsPlan::class, 'publication_id', 'publication_id');
    }

    // Publication memiliki banyak Files
    public function files()
    {
        return $this->hasMany(PublicationFile::class, 'publication_id', 'publication_id')
                    ->orderBy('created_at', 'desc'); 
    }
}