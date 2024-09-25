<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlaimLob extends Model
{
    use HasFactory;
    protected $fillable = [
        'lob',
        'penyebab_klaim',
        'periode',
        'id_wilker',
        'tgl_keputusan_klaim',
        'jumlah_terjamin',
        'nilai_beban_klaim',
        'debet_kredit',
        'created_by',
    ];
}
