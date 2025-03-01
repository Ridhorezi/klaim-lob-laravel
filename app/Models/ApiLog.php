<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'processdate',
        'totaldata',
        'deliverystatus',
        'lastupd_process',
        'created_by'
    ];
}
