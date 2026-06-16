<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Resource extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'endpoint_id',
        'resource_name',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
