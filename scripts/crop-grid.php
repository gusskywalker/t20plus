<?php

/**
 * Slices a grid of images (e.g. a portrait sheet) into separate WebP files.
 *
 * Legacy usage (single race per sheet):
 *   php crop-grid.php <input> <output_dir> <prefix> <cols> <rows> \
 *       <marginLeft> <marginTop> <marginRight> <marginBottom> <gapX> <gapY>
 *
 * Quad usage (4 races per sheet, one per quadrant): the input filename must
 * be 4 space-separated labels in TL TR BL BR order (e.g.
 * "bugbear inutil ceratops dahllan.jpg") — a quadrant labeled "inutil" is
 * skipped entirely. <cols>/<rows> describe the grid WITHIN one quadrant
 * (e.g. 4 4 for a 4x4-per-race sheet), not the whole image — the full grid
 * is derived by doubling both. Output is written straight to
 * <output_dir>/<label>_NN.webp, matching the flat, underscore-numbered
 * convention used across public/images/portraits — numbering continues
 * from whatever <label>_NN.webp files already exist in <output_dir> rather
 * than always restarting at 01, so re-running or adding a second sheet for
 * the same race doesn't clobber earlier crops.
 *   php crop-grid.php <input> <output_dir> --quad <cols> <rows> \
 *       <marginLeft> <marginTop> <marginRight> <marginBottom> <gapX> <gapY>
 *
 * Cell size is derived from the image dimensions, margins, gaps, and
 * cols/rows — not passed directly, so it stays correct even if you get the
 * margins/gaps slightly off and need to re-run.
 */

if ($argc < 12) {
    fwrite(STDERR, "Usage: php crop-grid.php <input> <output_dir> <prefix> <cols> <rows> <marginLeft> <marginTop> <marginRight> <marginBottom> <gapX> <gapY>\n");
    fwrite(STDERR, "   or: php crop-grid.php <input> <output_dir> --quad <cols> <rows> <marginLeft> <marginTop> <marginRight> <marginBottom> <gapX> <gapY>\n");
    exit(1);
}

$input = $argv[1];
$outputDir = $argv[2];
$isQuad = $argv[3] === '--quad';
$prefix = null;

if ($isQuad) {
    [$cols, $rows, $marginLeft, $marginTop, $marginRight, $marginBottom, $gapX, $gapY] = array_slice($argv, 4);
} else {
    $prefix = $argv[3];
    [$cols, $rows, $marginLeft, $marginTop, $marginRight, $marginBottom, $gapX, $gapY] = array_slice($argv, 4);
}

$cols = (int) $cols;
$rows = (int) $rows;
$marginLeft = (int) $marginLeft;
$marginTop = (int) $marginTop;
$marginRight = (int) $marginRight;
$marginBottom = (int) $marginBottom;
$gapX = (int) $gapX;
$gapY = (int) $gapY;

$labels = [];
if ($isQuad) {
    $labels = preg_split('/\s+/', pathinfo($input, PATHINFO_FILENAME));
    if (count($labels) !== 4) {
        fwrite(STDERR, "Quad mode expects exactly 4 space-separated labels in the input filename (TL TR BL BR), got: " . implode(' ', $labels) . "\n");
        exit(1);
    }
    $totalCols = $cols * 2;
    $totalRows = $rows * 2;
} else {
    $totalCols = $cols;
    $totalRows = $rows;
}

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$info = getimagesize($input);
[$width, $height] = $info;

$cellWidth = ($width - $marginLeft - $marginRight - ($totalCols - 1) * $gapX) / $totalCols;
$cellHeight = ($height - $marginTop - $marginBottom - ($totalRows - 1) * $gapY) / $totalRows;

echo "Image: {$width}x{$height} | Cell: {$cellWidth}x{$cellHeight}\n";

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
        foreach (glob("$outputDir/{$label}_*.webp") ?: [] as $existing) {
            if (preg_match('/_(\d+)\.webp$/', $existing, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }
        $nextNumber[$label] = $max + 1;
    }
    return $nextNumber[$label]++;
};

$written = 0;
for ($row = 0; $row < $totalRows; $row++) {
    for ($col = 0; $col < $totalCols; $col++) {
        if ($isQuad) {
            $quadIndex = ($row < $rows ? 0 : 2) + ($col < $cols ? 0 : 1);
            $label = $labels[$quadIndex];
            if (strcasecmp($label, 'inutil') === 0) {
                continue;
            }
            $n = $nextNumberFor($label);
            $outPath = sprintf('%s/%s_%02d.webp', $outputDir, $label, $n);
        } else {
            $n = $nextNumberFor($prefix);
            $outPath = sprintf('%s/%s_%02d.webp', $outputDir, $prefix, $n);
        }

        $x = (int) round($marginLeft + $col * ($cellWidth + $gapX));
        $y = (int) round($marginTop + $row * ($cellHeight + $gapY));
        $w = (int) round($cellWidth);
        $h = (int) round($cellHeight);

        $dest = imagecreatetruecolor($w, $h);
        imagecopy($dest, $src, 0, 0, $x, $y, $w, $h);
        imagewebp($dest, $outPath, 80);
        imagedestroy($dest);

        $written++;
    }
}

imagedestroy($src);
echo "Wrote $written files to $outputDir\n";
