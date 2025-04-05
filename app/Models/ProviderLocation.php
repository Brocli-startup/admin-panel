<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'latitude',
        'longitude',
        'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}