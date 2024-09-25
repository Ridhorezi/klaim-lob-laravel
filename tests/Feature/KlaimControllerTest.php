<?php

namespace Tests\Feature;

use App\Models\KlaimLob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KlaimControllerTest extends TestCase
{
    use RefreshDatabase;

    // Positive Testing for index method
    public function test_index_returns_correct_grouped_data()
    {
        KlaimLob::factory()->create([
            'lob' => 'KUR',
            'penyebab_klaim' => 'Kebakaran',
            'jumlah_terjamin' => 10000,
            'nilai_beban_klaim' => 5000,
        ]);

        KlaimLob::factory()->create([
            'lob' => 'KUR',
            'penyebab_klaim' => 'Banjir',
            'jumlah_terjamin' => 20000,
            'nilai_beban_klaim' => 8000,
        ]);

        KlaimLob::factory()->create([
            'lob' => 'PEN',
            'penyebab_klaim' => 'Banjir',
            'jumlah_terjamin' => 5000,
            'nilai_beban_klaim' => 2000,
        ]);

        // Call method index() 
        $klaimController = new \App\Http\Controllers\KlaimController();

        $response = $klaimController->index();

        // Ambil data klaims dari response
        $groupedKlaims = $response->getData()['groupedKlaims'];

        // Cek apakah data di group
        $this->assertEquals(2, $groupedKlaims['KUR']->count());
        $this->assertEquals(1, $groupedKlaims['PEN']->count());

        // Periksa subtotal per LOB
        $this->assertEquals(30000, $groupedKlaims['KUR']->sum('total_terjamin'));
        $this->assertEquals(13000, $groupedKlaims['KUR']->sum('total_beban_klaim'));

        $this->assertEquals(5000, $groupedKlaims['PEN']->sum('total_terjamin'));
        $this->assertEquals(2000, $groupedKlaims['PEN']->sum('total_beban_klaim'));
    }

    // Negative Testing for index method
    public function test_index_returns_no_data_when_no_klaim_exists()
    {
        // Data tidak ada 

        // Call method index()
        $klaimController = new \App\Http\Controllers\KlaimController();

        $response = $klaimController->index();

        // Get data klaims dari response
        $groupedKlaims = $response->getData()['groupedKlaims'];

        // Cek tidak ada data klaim yang ditemukan
        $this->assertEmpty($groupedKlaims);
    }

    // Positive Testing for sendApi method
    public function testCanSendKlaimDataToApi()
    {

        $klaimData = [
            'lob' => 'KUR',
            'penyebab_klaim' => 'Kebakaran',
            'periode' => now()->format('Y-m-d'),
            'nilai_beban_klaim' => 5000,
        ];

        $klaim = KlaimLob::factory()->create($klaimData);

        // Fake HTTP request
        Http::fake([
            'http://localhost:8080/v1/klaims' => Http::response('OK', 200)
        ]);

        // authentication
        $user = User::factory()->create(); 

        $this->actingAs($user); 

        // instance KlaimApiController
        $klaimController = new \App\Http\Controllers\KlaimController();

        // call metode sendApi
        $response = $klaimController->sendApi();

        // Periksa hasil respons
        $this->assertEquals(302, $response->getStatusCode()); 

        $this->assertDatabaseHas('api_logs', [
            'totaldata' => 1, 
            'deliverystatus' => 'success',
            'lastupd_process' => 'Send api from klaim lob',
            'created_by' => $user->name, 
        ]);
    }


    // Negative Testing for sendApi method when no klaim data exists
    public function test_cannot_send_klaim_data_if_no_data_exists()
    {
        Http::fake();

        $response = $this->post('/klaim/sendApi');

        $response->assertStatus(302);

        Http::assertNothingSent();
    }
}

