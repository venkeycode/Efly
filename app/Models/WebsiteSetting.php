<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'company_id',
        'logo',
        'site_logo',
        'favicon',
        'site_name',
        'description',
        'short_description',
        'admin_logo',
        'address',
        'email',
        'phone',
        'customer_care_no',
        'help_line_no',
        'gst',
        'image',
        'company',
        'site_url',
        'android_url',
        'ios_url',
        'customer_app_url',
        'technician_app_url',
        'telecaller_app_url',
        'facebook',
        'linkedin',
        'youtube',
        'pinterest',
        'domain',
        'subDomain',

    ];
}
