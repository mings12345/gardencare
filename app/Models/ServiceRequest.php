<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are guarded against mass assignment.
     *
     * @var array
     */
    protected $guarded = []; // Protect the 'id' field from mass assignment

    /**
     * Alternatively, you can use an empty array to allow mass assignment for all fields:
     * protected $guarded = [];
     */
}