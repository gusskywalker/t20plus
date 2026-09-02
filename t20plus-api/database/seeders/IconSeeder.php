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
        // File subdirectories under public/images/icons (frontend repo,
        // not this one — the files themselves live there, this seeder
        // just needs to enumerate them), sliced by scripts/crop-grid.php
        // from the AI-generated icon sheets. Grouped by top-level category
        // ('items' for the equipment catalogs, 'powers' for power icons)
        // since both now live under icons/, not just items/ like before.
        // Within 'items', 'general' holds potions/tools/ammo/reagents
        // together, the other 4 map 1:1 to the equipment tables. This
        // exact order fixes the ids below — WeaponSeeder etc. reference a
        // specific icon id directly (same convention as every other
        // seeder), so don't reorder without updating those references too.
        $categories = [
            'items' => ['weapons', 'armors', 'shields', 'accessories', 'general'],
            'powers' => ['active', 'passive', 'physical', 'magical'],
        ];

        $iconsDir = base_path('../t20plus-frontend/public/images/icons');

        $nextId = 1;
        foreach ($categories as $category => $subdirs) {
            foreach ($subdirs as $subdir) {
                $files = glob("{$iconsDir}/{$category}/{$subdir}/*.webp");
                natsort($files);
                foreach ($files as $path) {
                    Icon::create([
                        'id' => $nextId++,
                        // Full path from icons/ (e.g.
                        // "items/weapons/weapons_01.webp") since file_name
                        // alone can't tell weapons_01.webp from an
                        // armors_01.webp living in a different folder —
                        // the frontend builds the URL straight from this
                        // value, no separate category field.
                        'file_name' => "{$category}/{$subdir}/" . basename($path),
                    ]);
                }
            }
        }
    }
}
