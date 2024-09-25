<?php

namespace App\Imports;

use App\Models\KlaimLob;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KlaimLobImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KlaimLob([
            'lob' => $row[0], 
            'penyebab_klaim' => $row[1], 
            'periode' => Date::excelToDateTimeObject($row[2]),
            'id_wilker' => intval($row[3]), 
            'tgl_keputusan_klaim' => Date::excelToDateTimeObject($row[4]), 
            'jumlah_terjamin' => is_numeric($row[5]) ? floatval($row[5]) : 0, 
            'nilai_beban_klaim' => is_numeric($row[6]) ? floatval($row[6]) : 0, 
            'debet_kredit' => $row[7], 
            'created_by' => auth()->user()->name ?? 'system',
        ]);
    }
}
