<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

interface Mime_Type_Detector
{
    /**
     * @param string|resource $contents
     */
    public function detect_mime_type(string $path, $contents): ?string;
    public function detect_mime_type_from_buffer(string $contents): ?string;
    public function detect_mime_type_from_path(string $path): ?string;
    public function detect_mime_type_from_file(string $path): ?string;
}