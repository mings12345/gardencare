<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function seasonalTips()
    {
        return $this->hasMany(SeasonalTip::class);
    }
}
