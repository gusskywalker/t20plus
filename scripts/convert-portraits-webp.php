<?php

/**
 * Converts every PNG in public/images/portraits (frontend repo) to WebP in
 * place, deleting the source PNG once its WebP sibling is written
 * successfully. Purely a file-format conversion — filenames keep their
 * stem, only the extension changes (foo_01.png -> foo_01.webp), so the
 * portraits.file_name values in the DB need a matching update afterward
 * (see the accompanying UpdatePortraitExtensionsToWebp one-off command/
 * seeder step, or run the SQL directly).
 *
 * Usage: php scripts/convert-portraits-webp.php [quality]
 * quality defaults to 80 (0-100, WebP lossy).
 */

$quality = isset($argv[1]) ? (int) $argv[1] : 80;
$dir = __DIR__ . '/../t20plus-frontend/public/images/portraits';

$files = glob("$dir/*.png");
$converted = 0;
$failed = [];

foreach ($files as $path) {
    $webpPath = preg_replace('/\.png$/', '.webp', $path);

    $src = imagecreatefrompng($path);
    if ($src === false) {
        $failed[] = $path;
        continue;
    }

    // Portrait PNGs have no transparency, but preserve alpha anyway in
    // case a future dump does.
    imagepalettetotruecolor($src);
    imagealphablending($src, true);
    imagesavealpha($src, true);

    if (!imagewebp($src, $webpPath, $quality)) {
        imagedestroy($src);
        $failed[] = $path;
        continue;
    }

    imagedestroy($src);
    unlink($path);
    $converted++;
}

echo "Converted $converted files.\n";
if ($failed) {
    echo 'Failed (' . count($failed) . "):\n" . implode("\n", $failed) . "\n";
}
