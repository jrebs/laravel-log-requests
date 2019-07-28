<?php

namespace Jrebs\LogRequests;

use Illuminate\Database\Eloquent\Model;

class LoggedRequest extends Model
{
    protected $fillable = [
        'user_id',
        'url',
        'method',
        'duration',
        'ip',
        'status',
        'request',
        'response',
    ];
}
