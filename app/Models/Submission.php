<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Storage;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'scanned_photo',
        'name',
        'email',
        'phone',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the scan results for this submission.
     */
    public function scanResults(): HasMany
    {
        return $this->hasMany(ScanResult::class);
    }

    public function getScannedPhotoUrlAttribute()
    {
        return Storage::disk('public')->url($this->scanned_photo);
    }
    /**
     * Get the product recommendations for this submission.
     */
    public function productRecommendations(): HasMany
    {
        return $this->hasMany(ProductRecommendation::class);
    }
}
