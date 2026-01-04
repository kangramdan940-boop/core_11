<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterMobileAppConfig extends Model
{
    use HasFactory;

    protected $table = 'mobile_app_configs';

    protected $fillable = [
        'login_page_icon',
        'information_link',
        'development_mode',
        'status_naik',
        'status_turun',
        'welcome_title',
        'welcome_description',
        'broadcast_info_banner_status',
        'broadcast_info_banner_description',
    ];

    protected $casts = [
        'development_mode' => 'boolean',
        'status_naik' => 'boolean',
        'status_turun' => 'boolean',
        'broadcast_info_banner_status' => 'boolean',
    ];
}