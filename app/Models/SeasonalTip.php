<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonalTip extends Model
{
    use HasFactory;

    protected $fillable = ['season', 'region', 'tip', 'plant_id'];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}