<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_name',
        'request_body',
        'response_status_code',
        'response_body',
        'source_ip',
    ];
}
