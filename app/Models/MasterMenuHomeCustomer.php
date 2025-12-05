<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterMenuHomeCustomer extends Model
{
    use HasFactory;

    protected $table = 'master_menu_home_customer';

    protected $fillable = [
        'image',
        'label',
        'path_url',
    ];
}