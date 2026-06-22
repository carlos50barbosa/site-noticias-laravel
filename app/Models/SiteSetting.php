<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['site_name', 'logo_url', 'favicon_url', 'adsense_client', 'visits'])]
class SiteSetting extends Model
{
    protected function casts(): array
    {
        return [
            'visits' => 'integer',
        ];
    }
}
