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
            ['name' => 'Aharadak', 'energy_type' => -1],
            ['name' => 'Allihanna', 'energy_type' => 1],
            ['name' => 'Arsenal', 'energy_type' => 0],
            ['name' => 'Azgher', 'energy_type' => 1],
            ['name' => 'Hyninn', 'energy_type' => 0],
            ['name' => 'Kallyadranoch', 'energy_type' => -1],
            ['name' => 'Khalmyr', 'energy_type' => 1],
            ['name' => 'Lena', 'energy_type' => 1],
            ['name' => 'Lin-Wu', 'energy_type' => 0],
            ['name' => 'Marah', 'energy_type' => 1],
            ['name' => 'Megalokk', 'energy_type' => -1],
            ['name' => 'Nimb', 'energy_type' => 0],
            ['name' => 'Oceano', 'energy_type' => 0],
            ['name' => 'Sszzaas', 'energy_type' => -1],
            ['name' => 'Tanna-Toh', 'energy_type' => 0],
            ['name' => 'Tenebra', 'energy_type' => -1],
            ['name' => 'Thwor', 'energy_type' => 0],
            ['name' => 'Thyatis', 'energy_type' => 1],
            ['name' => 'Valkaria', 'energy_type' => 1],
            ['name' => 'Wynna', 'energy_type' => 0],
            ['name' => 'Glórienn', 'energy_type' => 1],
            ['name' => 'Keenn', 'energy_type' => -1],
            ['name' => 'Ragnar', 'energy_type' => -1],
            ['name' => 'Tauron', 'energy_type' => 0],
            ['name' => 'Tilliann', 'energy_type' => 0],

            // Minor gods
            ['name' => 'Gwendolynn', 'energy_type' => 1],
            ['name' => 'Mauziell', 'energy_type' => 1],
            ['name' => 'Tibar', 'energy_type' => 0],
            ['name' => 'Espada Deus', 'energy_type' => 0],
            ['name' => 'Akok', 'energy_type' => 1],
            ['name' => 'Altair', 'energy_type' => 0],
            ['name' => 'Anilatir', 'energy_type' => 0],
            ['name' => 'Apis', 'energy_type' => 0],
            ['name' => 'Artaphan', 'energy_type' => 1],
            ['name' => 'Ayllana', 'energy_type' => 1],
            ['name' => 'Beluhga', 'energy_type' => 1],
            ['name' => 'Benthos', 'energy_type' => 0],
            ['name' => 'Betsumial', 'energy_type' => 0],
            ['name' => 'Blinar', 'energy_type' => 0],
            ['name' => 'Caerdellach', 'energy_type' => 1],
            ['name' => 'Canastra', 'energy_type' => 0],
            ['name' => 'Canora', 'energy_type' => 0],
            ['name' => 'Cette', 'energy_type' => 0],
            ['name' => 'Champarr', 'energy_type' => 0],
            ['name' => 'Dahriol', 'energy_type' => 0],
            ['name' => 'Drumak', 'energy_type' => 0],
            ['name' => 'Dunsark', 'energy_type' => 0],
            ['name' => 'Elrophin', 'energy_type' => 0],
            ['name' => 'Escamandra', 'energy_type' => 0],
            ['name' => 'Esmeralda', 'energy_type' => 0],
            ['name' => 'Garanaam', 'energy_type' => 0],
            ['name' => 'Garth', 'energy_type' => -1],
            ['name' => 'Goharom', 'energy_type' => 0],
            ['name' => 'Granto', 'energy_type' => 0],
            ['name' => 'Gratissa', 'energy_type' => 1],
            ['name' => 'Hippion', 'energy_type' => 1],
            ['name' => 'Hurlaagh', 'energy_type' => -1],
            ['name' => 'Hydora', 'energy_type' => 0],
            ['name' => 'Inghlblhpholtsgt', 'energy_type' => 0],
            ['name' => 'Irione', 'energy_type' => 0],
            ['name' => 'Jandra', 'energy_type' => 0],
            ['name' => 'Klangor', 'energy_type' => 1],
            ['name' => 'Kurur Lianth', 'energy_type' => -1],
            ['name' => 'Laan', 'energy_type' => 1],
            ['name' => 'Lamashtu', 'energy_type' => -1],
            ['name' => 'Lupan', 'energy_type' => 1],
            ['name' => 'Luvithy', 'energy_type' => -1],
            ['name' => 'Marina', 'energy_type' => 0],
            ['name' => 'Mzzileyn', 'energy_type' => -1],
            ['name' => 'Nerelim', 'energy_type' => 0],
            ['name' => 'Neruíte', 'energy_type' => 0],
            ['name' => 'O Deus Cristal de Urielka', 'energy_type' => 1],
            ['name' => 'O Deus das Cidades', 'energy_type' => 0],
            ['name' => 'O Deus do Medo', 'energy_type' => -1],
            ['name' => 'Piscigeros', 'energy_type' => -1],
            ['name' => 'Rhond', 'energy_type' => 0],
            ['name' => 'Sartan', 'energy_type' => -1],
            ['name' => 'Sckhar', 'energy_type' => -1],
            ['name' => 'Sunnary', 'energy_type' => 1],
            ['name' => 'Tamagrah', 'energy_type' => 0],
            ['name' => 'Tessalus', 'energy_type' => 0],
            ['name' => 'Toris', 'energy_type' => 0],
            ['name' => 'Tukala', 'energy_type' => 0],
            ['name' => 'Ur', 'energy_type' => 1],
            ['name' => 'Yasshara', 'energy_type' => -1],
            ['name' => 'Zadbblein', 'energy_type' => -1],
            ['name' => 'Zakharov', 'energy_type' => 0],
            ['name' => 'Drelene', 'energy_type' => 0],
        ];

        foreach ($gods as $god) {
            God::create($god);
        }
    }
}
