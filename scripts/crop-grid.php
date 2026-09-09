<?php

/**
 * Slices a 1024x1024 sheet into its 4 perfect 512x512 quadrants (TL TR BL
 * BR), one icon per quadrant. The input filename must be 4 space-separated
 * labels in that order (e.g. "bugbear inutil ceratops dahllan.jpg") — a
 * quadrant labeled "inutil" is skipped entirely. Output is written straight
 * to <output_dir>/<label>_NN.webp, matching the flat, underscore-numbered
 * convention used across public/images — numbering continues from whatever
 * <label>_NN.webp files already exist in <output_dir> rather than always
 * restarting at 01, so re-running or adding a second sheet for the same
 * label doesn't clobber earlier crops.
 *
 * Usage: php crop-grid.php <input> <output_dir>
 */

if ($argc < 3) {
    fwrite(STDERR, "Usage: php crop-grid.php <input> <output_dir>\n");
    exit(1);
}

$input = $argv[1];
$outputDir = $argv[2];

$labels = preg_split('/\s+/', pathinfo($input, PATHINFO_FILENAME));
if (count($labels) !== 4) {
    fwrite(STDERR, "Expected exactly 4 space-separated labels in the input filename (TL TR BL BR), got: " . implode(' ', $labels) . "\n");
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
    default => throw new RuntimeException("Unsupported extension: $ext"),
};

// Next number to use per label, continuing past whatever's already in
// output_dir instead of always starting at 01.
$nextNumber = [];
$nextNumberFor = function (string $label) use (&$nextNumber, $outputDir): int {
    if (!isset($nextNumber[$label])) {
        $max = 0;
        $quoted = preg_quote($label, '/');
        foreach (glob("$outputDir/{$label}_*.webp") ?: [] as $existing) {
            if (preg_match("/^{$quoted}_(\d+)\.webp\$/", basename($existing), $m)) {
                $max = max($max, (int) $m[1]);
            }
        }
        $nextNumber[$label] = $max + 1;
    }
    return $nextNumber[$label]++;
};

$written = 0;
for ($row = 0; $row < 2; $row++) {
    for ($col = 0; $col < 2; $col++) {
        $quadIndex = $row * 2 + $col;
        $label = $labels[$quadIndex];
        if (strcasecmp($label, 'inutil') === 0) {
            continue;
        }
        $n = $nextNumberFor($label);
        $outPath = sprintf('%s/%s_%02d.webp', $outputDir, $label, $n);

        $dest = imagecreatetruecolor(512, 512);
        imagecopy($dest, $src, 0, 0, $col * 512, $row * 512, 512, 512);
        imagewebp($dest, $outPath, 80);
        imagedestroy($dest);

        $written++;
    }
}

imagedestroy($src);
echo "Wrote $written files to $outputDir\n";
