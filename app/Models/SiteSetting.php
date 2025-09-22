<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'logo',
        'site_logo',
        'favicon',
        'site_name',
        'description',
        'short_description',
        'desgination',
        'experience',
        'admin_logo',
        'address',
        'email',
        'phone',
        'customer_care_no',
        'help_line_no',
        'gst',
        'delivery_charge',
        'image',
        'company',
        'no_of_services',
        'site_url',
        'android_url',
        'ios_url',
        'customer_app_url',
        'store_app_url',
        'franchise_app_url',
        'facebook',
        'linkedin',
        'youtube',
        'pinterest',
        'instagram',
        'twitter',
        'customers',
        'plasticReduced',
        'co2Reduced',
        'treeSaved',
    ];
}
