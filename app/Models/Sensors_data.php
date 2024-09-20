<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensors_data extends Model
{
    use HasFactory;
    protected $table = 'sensors_data';
    protected $primarykey = 'id';
    protected $fillable = [
        'sensor_id',
        'valor'

    ];

    public $timestamps = false;
}
