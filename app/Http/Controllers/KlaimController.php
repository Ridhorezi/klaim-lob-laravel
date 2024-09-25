<?php

namespace App\Http\Controllers;

use App\Imports\KlaimLobImport;
use App\Models\KlaimLob;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KlaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klaims = KlaimLob::whereIn('lob', ['KUR', 'PEN', 'Produktif', 'Konsumtif', 'KBG dan Suretyship'])
            ->select('lob', 'penyebab_klaim', DB::raw('SUM(jumlah_terjamin) as total_terjamin'), DB::raw('SUM(nilai_beban_klaim) as total_beban_klaim'))
            ->groupBy('lob', 'penyebab_klaim')
            ->get();

        $groupedKlaims = $klaims->groupBy('lob');
        return view('klaim.index', compact('groupedKlaims'));
    }

    public function sendApi()
    {

        $klaims = KlaimLob::whereIn('lob', ['KUR', 'PEN'])->get();

        $totalData = $klaims->count();

        $deliveryStatus = 'success';

        foreach ($klaims as $klaim) {

            $response = Http::post('http://localhost:8080/v1/klaims', [
                'lob' => $klaim->lob,
                'penyebab_klaim' => $klaim->penyebab_klaim,
                'periode' => $klaim->periode,
                'nilai_beban_klaim' => $klaim->nilai_beban_klaim,
            ]);

            if (!$response->successful()) {
                $deliveryStatus = 'failed';
            }

            Log::info('Klaim data sent', ['data' => $klaim]);
        }

        ApiLog::create([
            'processdate' => now(),
            'totaldata' => $totalData,
            'deliverystatus' => $deliveryStatus,
            'lastupd_process' => 'Send api from klaim lob',
            'created_by' => auth()->user()->name,
        ]);

        if ($deliveryStatus === 'success') {
            return redirect()->back()->with('success', 'Successfully send data');
        } else {
            return redirect()->back()->with('error', 'Failed to send data');
        }
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new KlaimLobImport, $request->file('file')->store('temp'));
            return back()->with('success', 'Data imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
