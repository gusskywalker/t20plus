<?php

/**
 * Slices a 1024x1024 sheet into its 16 perfect 256x256 tiles (4x4, row by
 * row from the top-left), one portrait per tile. The label is the input
 * filename with its trailing digits stripped (e.g. "humanos1.png" and
 * "humanos2.png" both yield the label "humanos"). Output is always written to
 * t20plus-frontend/public/images/portraits/<label>_NN.webp — numbering
 * continues from whatever <label>_NN.webp files already exist there rather
 * than always restarting at 01, so several sheets for the same label don't
 * clobber earlier crops.
 *
 * Usage: php portrait-crop-grid.php <input>
 */

if ($argc < 2) {
    fwrite(STDERR, "Usage: php portrait-crop-grid.php <input>\n");
    exit(1);
}

$input = $argv[1];
$outputDir = __DIR__ . '/../t20plus-frontend/public/images/portraits';

$label = preg_replace('/\d+$/', '', pathinfo($input, PATHINFO_FILENAME));
if ($label === '') {
    fwrite(STDERR, "Could not derive a label from the input filename: " . basename($input) . "\n");
    exit(1);
}

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$info = getimagesize($input);
[$width, $height] = $info;
if ($width !== 1024 || $height !== 1024) {
    fwrite(STDERR, "Expected a 1024x1024 input, got {$width}x{$height}\n");
    exit(1);
}

$ext = strtolower(pathinfo($input, PATHINFO_EXTENSION));
$src = match ($ext) {
    'jpg', 'jpeg' => imagecreatefromjpeg($input),
    'png' => imagecreatefrompng($input),
    'webp' => imagecreatefromwebp($input),
    default => throw new RuntimeException("Unsupported extension: $ext"),
};

$max = 0;
$quoted = preg_quote($label, '/');
foreach (glob("$outputDir/{$label}_*.webp") ?: [] as $existing) {
    if (preg_match("/^{$quoted}_(\d+)\.webp\$/", basename($existing), $m)) {
        $max = max($max, (int) $m[1]);
    }
}
$nextNumber = $max + 1;

$written = 0;
for ($row = 0; $row < 4; $row++) {
    for ($col = 0; $col < 4; $col++) {
        $outPath = sprintf('%s/%s_%02d.webp', $outputDir, $label, $nextNumber++);

        $dest = imagecreatetruecolor(256, 256);
        imagecopy($dest, $src, 0, 0, $col * 256, $row * 256, 256, 256);
        imagewebp($dest, $outPath, 80);
        imagedestroy($dest);

        $written++;
    }
}

imagedestroy($src);
echo "Wrote $written files to $outputDir as {$label}_NN.webp\n";
