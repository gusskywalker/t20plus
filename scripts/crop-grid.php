<?php

/**
 * Slices a grid of images (e.g. a portrait sheet) into separate PNG files.
 *
 * Usage:
 *   php crop-grid.php <input> <output_dir> <prefix> <cols> <rows> \
 *       <marginLeft> <marginTop> <marginRight> <marginBottom> <gapX> <gapY>
 *
 * Cell size is derived from the image dimensions, margins, gaps, and
 * cols/rows — not passed directly, so it stays correct even if you get the
 * margins/gaps slightly off and need to re-run.
 */

if ($argc < 12) {
    fwrite(STDERR, "Usage: php crop-grid.php <input> <output_dir> <prefix> <cols> <rows> <marginLeft> <marginTop> <marginRight> <marginBottom> <gapX> <gapY>\n");
    exit(1);
}

[$script, $input, $outputDir, $prefix, $cols, $rows, $marginLeft, $marginTop, $marginRight, $marginBottom, $gapX, $gapY] = $argv;

$cols = (int) $cols;
$rows = (int) $rows;
$marginLeft = (int) $marginLeft;
$marginTop = (int) $marginTop;
$marginRight = (int) $marginRight;
$marginBottom = (int) $marginBottom;
$gapX = (int) $gapX;
$gapY = (int) $gapY;

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$info = getimagesize($input);
[$width, $height] = $info;

$cellWidth = ($width - $marginLeft - $marginRight - ($cols - 1) * $gapX) / $cols;
$cellHeight = ($height - $marginTop - $marginBottom - ($rows - 1) * $gapY) / $rows;

echo "Image: {$width}x{$height} | Cell: {$cellWidth}x{$cellHeight}\n";

$ext = strtolower(pathinfo($input, PATHINFO_EXTENSION));
$src = match ($ext) {
    'jpg', 'jpeg' => imagecreatefromjpeg($input),
    'png' => imagecreatefrompng($input),
    default => throw new RuntimeException("Unsupported extension: $ext"),
};

$n = 1;
for ($row = 0; $row < $rows; $row++) {
    for ($col = 0; $col < $cols; $col++) {
        $x = (int) round($marginLeft + $col * ($cellWidth + $gapX));
        $y = (int) round($marginTop + $row * ($cellHeight + $gapY));
        $w = (int) round($cellWidth);
        $h = (int) round($cellHeight);

        $dest = imagecreatetruecolor($w, $h);
        imagecopy($dest, $src, 0, 0, $x, $y, $w, $h);

        $outPath = sprintf('%s/%s-%02d.png', $outputDir, $prefix, $n);
        imagepng($dest, $outPath);
        imagedestroy($dest);

        $n++;
    }
}

imagedestroy($src);
echo "Wrote " . ($n - 1) . " files to $outputDir\n";
