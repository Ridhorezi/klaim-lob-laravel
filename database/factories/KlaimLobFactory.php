<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KlaimLob>
 */
class KlaimLobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'lob' => $this->faker->randomElement(['KUR', 'PEN', 'Produktif', 'Konsumtif', 'KBG dan Suretyship']),
            'penyebab_klaim' => $this->faker->word,
            'periode' => $this->faker->date(),
            'id_wilker' => $this->faker->randomDigitNotNull(),
            'tgl_keputusan_klaim' => $this->faker->date(),
            'jumlah_terjamin' => $this->faker->numberBetween(1000, 10000),
            'nilai_beban_klaim' => $this->faker->numberBetween(1000, 10000),
            'debet_kredit' => $this->faker->randomElement(['Debet', 'Kredit']),
            'created_by' => $this->faker->name,
        ];
    }
}
