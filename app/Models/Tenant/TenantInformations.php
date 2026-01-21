<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantInformations extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_name',
        'address',
        'phone',
        'email',
        'RIB',
        'SIRET',
        'VAT_number',
        'logo_path',
    ];
}
