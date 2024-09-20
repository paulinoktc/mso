<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameters extends Model
{
    use HasFactory;
    protected $table = 'parametres';
    protected $primarykey = 'id';
    protected $fillable = [
        'sensor_id',
        'maxim'

    ];

    public $timestamps = false;
}
