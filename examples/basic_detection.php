<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use League\Mime_Type_Detection\Extension_Mime_Type_Detector;
use League\Mime_Type_Detection\Finfo_Mime_Type_Detector;
use League\Mime_Type_Detection\Overriding_Extension_To_Mime_Type_Map;
use League\Mime_Type_Detection\Generated_Extension_To_Mime_Type_Map;

// --- Example 1: Detect by file extension (no I/O, fastest) ---
$detector = new Extension_Mime_Type_Detector();

echo $detector->detect_mime_type_from_path('photo.jpg');   // image/jpeg
echo "\n";
echo $detector->detect_mime_type_from_path('archive.tar.gz'); // application/gzip
echo "\n";
echo $detector->detect_mime_type_from_path('unknown.xyz');  // null
echo "\n\n";

// --- Example 2: Detect by file content using finfo (reads file bytes) ---
$finfo_detector = new Finfo_Mime_Type_Detector();

// Provide a path to a real file on your system to test:
// echo $finfo_detector->detect_mime_type_from_file('/path/to/image.png'); // image/png

// Detect from a raw buffer (e.g., uploaded file contents already in memory):
$png_header = "\x89PNG\r\n\x1a\n"; // first 8 bytes of a PNG file
echo $finfo_detector->detect_mime_type_from_buffer($png_header); // image/png
echo "\n\n";

// --- Example 3: Override specific MIME types ---
$overrides = new Overriding_Extension_To_Mime_Type_Map(
    ['svg' => 'image/svg+xml; charset=UTF-8'],
    new Generated_Extension_To_Mime_Type_Map()
);
$custom_detector = new Extension_Mime_Type_Detector($overrides);

echo $custom_detector->detect_mime_type_from_path('icon.svg'); // image/svg+xml; charset=UTF-8
echo "\n\n";

// --- Example 4: Reverse lookup — find file extension from MIME type ---
echo $detector->lookup_extension('video/mp4');   // mp4
echo "\n";
$exts = $detector->lookup_all_extensions('image/jpeg');
echo implode(', ', $exts);  // jpeg, jpg, jpe, ...
echo "\n";
