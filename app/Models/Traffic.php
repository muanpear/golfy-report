<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traffic extends Model
{
    use HasFactory;

    protected $table = 'traffics';

    protected $fillable = [
        'groupID',
        'deviceID',
        'deviceName',
        'deviceUp',
        'deviceDown',
        'deviceAva',
        'rxSpeedMin',
        'rxSpeedMax',
        'rxSpeedAvg',
        'txSpeedMin',
        'txSpeedMax',
        'txSpeedAvg',
        'pollTimeUtc'
    ];

}