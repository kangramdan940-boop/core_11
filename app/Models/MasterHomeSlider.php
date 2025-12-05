<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterHomeSlider extends Model
{
    use HasFactory;

    protected $table = 'master_home_slider';

    protected $fillable = [
        'image_url',
        'title',
        'description',
    ];
}