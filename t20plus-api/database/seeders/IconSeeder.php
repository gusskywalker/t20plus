<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // File subdirectories under public/images/icons/items (frontend
        // repo, not this one — the files themselves live there, this
        // seeder just needs to enumerate them), sliced by
        // scripts/crop-grid.php from the AI-generated icon sheets.
        // 'general' holds potions/tools/ammo/reagents together, the other
        // 4 map 1:1 to the equipment tables. This exact order fixes the
        // ids below — WeaponSeeder etc. reference a specific icon id
        // directly (same convention as every other seeder), so don't
        // reorder without updating those references too.
        $subdirs = ['weapons', 'armors', 'shields', 'accessories', 'general'];

        $iconsDir = base_path('../t20plus-frontend/public/images/icons/items');

        $nextId = 1;
        foreach ($subdirs as $subdir) {
            $files = glob("{$iconsDir}/{$subdir}/*.webp");
            natsort($files);
            foreach ($files as $path) {
                Icon::create([
                    'id' => $nextId++,
                    // Includes the subdir (e.g. "weapons/weapons_01.webp")
                    // since file_name alone can't tell weapons_01.webp from
                    // an armors_01.webp living in a different folder — the
                    // frontend builds the URL straight from this value, no
                    // separate category field.
                    'file_name' => "{$subdir}/" . basename($path),
                ]);
            }
        }
    }
}
