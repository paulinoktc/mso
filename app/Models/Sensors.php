<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensors extends Model
{
    use HasFactory;
    protected $table = 'sensors';
    protected $primarykey = 'id';
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
