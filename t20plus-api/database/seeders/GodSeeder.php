<?php

namespace Database\Seeders;

use App\Models\God;
use Illuminate\Database\Seeder;

class GodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gods = [
            ['id' => 1, 'name' => 'Aharadak', 'energy_type' => -1],
            ['id' => 2, 'name' => 'Allihanna', 'energy_type' => 1],
            ['id' => 3, 'name' => 'Arsenal', 'energy_type' => 0],
            ['id' => 4, 'name' => 'Azgher', 'energy_type' => 1],
            ['id' => 5, 'name' => 'Hyninn', 'energy_type' => 0],
            ['id' => 6, 'name' => 'Kallyadranoch', 'energy_type' => -1],
            ['id' => 7, 'name' => 'Khalmyr', 'energy_type' => 1],
            ['id' => 8, 'name' => 'Lena', 'energy_type' => 1],
            ['id' => 9, 'name' => 'Lin-Wu', 'energy_type' => 0],
            ['id' => 10, 'name' => 'Marah', 'energy_type' => 1],
            ['id' => 11, 'name' => 'Megalokk', 'energy_type' => -1],
            ['id' => 12, 'name' => 'Nimb', 'energy_type' => 0],
            ['id' => 13, 'name' => 'Oceano', 'energy_type' => 0],
            ['id' => 14, 'name' => 'Sszzaas', 'energy_type' => -1],
            ['id' => 15, 'name' => 'Tanna-Toh', 'energy_type' => 0],
            ['id' => 16, 'name' => 'Tenebra', 'energy_type' => -1],
            ['id' => 17, 'name' => 'Thwor', 'energy_type' => 0],
            ['id' => 18, 'name' => 'Thyatis', 'energy_type' => 1],
            ['id' => 19, 'name' => 'Valkaria', 'energy_type' => 1],
            ['id' => 20, 'name' => 'Wynna', 'energy_type' => 0],
            ['id' => 21, 'name' => 'Glórienn', 'energy_type' => 1],
            ['id' => 22, 'name' => 'Keenn', 'energy_type' => -1],
            ['id' => 23, 'name' => 'Ragnar', 'energy_type' => -1],
            ['id' => 24, 'name' => 'Tauron', 'energy_type' => 0],
            ['id' => 25, 'name' => 'Tilliann', 'energy_type' => 0],

            // Minor gods
            ['id' => 26, 'name' => 'Gwendolynn', 'energy_type' => 1],
            ['id' => 27, 'name' => 'Mauziell', 'energy_type' => 1],
            ['id' => 28, 'name' => 'Tibar', 'energy_type' => 0],
            ['id' => 29, 'name' => 'Espada Deus', 'energy_type' => 0],
            ['id' => 30, 'name' => 'Akok', 'energy_type' => 1],
            ['id' => 31, 'name' => 'Altair', 'energy_type' => 0],
            ['id' => 32, 'name' => 'Anilatir', 'energy_type' => 0],
            ['id' => 33, 'name' => 'Apis', 'energy_type' => 0],
            ['id' => 34, 'name' => 'Artaphan', 'energy_type' => 1],
            ['id' => 35, 'name' => 'Ayllana', 'energy_type' => 1],
            ['id' => 36, 'name' => 'Beluhga', 'energy_type' => 1],
            ['id' => 37, 'name' => 'Benthos', 'energy_type' => 0],
            ['id' => 38, 'name' => 'Betsumial', 'energy_type' => 0],
            ['id' => 39, 'name' => 'Blinar', 'energy_type' => 0],
            ['id' => 40, 'name' => 'Caerdellach', 'energy_type' => 1],
            ['id' => 41, 'name' => 'Canastra', 'energy_type' => 0],
            ['id' => 42, 'name' => 'Canora', 'energy_type' => 0],
            ['id' => 43, 'name' => 'Cette', 'energy_type' => 0],
            ['id' => 44, 'name' => 'Champarr', 'energy_type' => 0],
            ['id' => 45, 'name' => 'Dahriol', 'energy_type' => 0],
            ['id' => 46, 'name' => 'Drumak', 'energy_type' => 0],
            ['id' => 47, 'name' => 'Dunsark', 'energy_type' => 0],
            ['id' => 48, 'name' => 'Elrophin', 'energy_type' => 0],
            ['id' => 49, 'name' => 'Escamandra', 'energy_type' => 0],
            ['id' => 50, 'name' => 'Esmeralda', 'energy_type' => 0],
            ['id' => 51, 'name' => 'Garanaam', 'energy_type' => 0],
            ['id' => 52, 'name' => 'Garth', 'energy_type' => -1],
            ['id' => 53, 'name' => 'Goharom', 'energy_type' => 0],
            ['id' => 54, 'name' => 'Granto', 'energy_type' => 0],
            ['id' => 55, 'name' => 'Gratissa', 'energy_type' => 1],
            ['id' => 56, 'name' => 'Hippion', 'energy_type' => 1],
            ['id' => 57, 'name' => 'Hurlaagh', 'energy_type' => -1],
            ['id' => 58, 'name' => 'Hydora', 'energy_type' => 0],
            ['id' => 59, 'name' => 'Inghlblhpholtsgt', 'energy_type' => 0],
            ['id' => 60, 'name' => 'Irione', 'energy_type' => 0],
            ['id' => 61, 'name' => 'Jandra', 'energy_type' => 0],
            ['id' => 62, 'name' => 'Klangor', 'energy_type' => 1],
            ['id' => 63, 'name' => 'Kurur Lianth', 'energy_type' => -1],
            ['id' => 64, 'name' => 'Laan', 'energy_type' => 1],
            ['id' => 65, 'name' => 'Lamashtu', 'energy_type' => -1],
            ['id' => 66, 'name' => 'Lupan', 'energy_type' => 1],
            ['id' => 67, 'name' => 'Luvithy', 'energy_type' => -1],
            ['id' => 68, 'name' => 'Marina', 'energy_type' => 0],
            ['id' => 69, 'name' => 'Mzzileyn', 'energy_type' => -1],
            ['id' => 70, 'name' => 'Nerelim', 'energy_type' => 0],
            ['id' => 71, 'name' => 'Neruíte', 'energy_type' => 0],
            ['id' => 72, 'name' => 'O Deus Cristal de Urielka', 'energy_type' => 1],
            ['id' => 73, 'name' => 'O Deus das Cidades', 'energy_type' => 0],
            ['id' => 74, 'name' => 'O Deus do Medo', 'energy_type' => -1],
            ['id' => 75, 'name' => 'Piscigeros', 'energy_type' => -1],
            ['id' => 76, 'name' => 'Rhond', 'energy_type' => 0],
            ['id' => 77, 'name' => 'Sartan', 'energy_type' => -1],
            ['id' => 78, 'name' => 'Sckhar', 'energy_type' => -1],
            ['id' => 79, 'name' => 'Sunnary', 'energy_type' => 1],
            ['id' => 80, 'name' => 'Tamagrah', 'energy_type' => 0],
            ['id' => 81, 'name' => 'Tessalus', 'energy_type' => 0],
            ['id' => 82, 'name' => 'Toris', 'energy_type' => 0],
            ['id' => 83, 'name' => 'Tukala', 'energy_type' => 0],
            ['id' => 84, 'name' => 'Ur', 'energy_type' => 1],
            ['id' => 85, 'name' => 'Yasshara', 'energy_type' => -1],
            ['id' => 86, 'name' => 'Zadbblein', 'energy_type' => -1],
            ['id' => 87, 'name' => 'Zakharov', 'energy_type' => 0],
            ['id' => 88, 'name' => 'Drelene', 'energy_type' => 0],
        ];

        foreach ($gods as $god) {
            God::create($god);
        }
    }
}
