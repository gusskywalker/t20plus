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
        // File under public/images/icons (frontend repo, not this one — the
        // files themselves live there, this seeder just needs to enumerate
        // them), sliced by scripts/crop-grid.php from the AI-generated icon
        // sheets. 'items/' still holds the equipment catalog placeholders,
        // but the 'powers/' subfolder is gone — every power/item-improvement
        // icon sits flat directly in icons/ root now, no sorting into
        // subfolders at all anymore. This exact order (items alphabetically,
        // then root alphabetically) fixes the ids below — WeaponSeeder etc.
        // reference a specific icon id directly (same convention as every
        // other seeder), so don't reorder without updating those references
        // too.
        $iconsDir = base_path('../t20plus-frontend/public/images/icons');

        $nextId = 1;

        $itemFiles = glob("{$iconsDir}/items/*.webp");
        natsort($itemFiles);
        foreach ($itemFiles as $path) {
            Icon::create([
                'id' => $nextId++,
                // Full path from icons/ (e.g. "items/weapons_01.webp") —
                // the frontend builds the URL straight from this value, no
                // separate category field.
                'file_name' => 'items/' . basename($path),
            ]);
        }

        $rootFiles = glob("{$iconsDir}/*.webp");
        natsort($rootFiles);
        foreach ($rootFiles as $path) {
            Icon::create([
                'id' => $nextId++,
                'file_name' => basename($path),
            ]);
        }
    }
}
