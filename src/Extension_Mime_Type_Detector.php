<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use const PATHINFO_EXTENSION;
class Extension_Mime_Type_Detector implements Mime_Type_Detector, Extension_Lookup
{
    private \League\Mime_Type_Detection\Extension_To_Mime_Type_Map $extensions;
    public function __construct(?Extension_To_Mime_Type_Map $extensions = null)
    {
        $this->extensions = $extensions ?: new Generated_Extension_To_Mime_Type_Map();
    }
    public function detect_mime_type(string $path, $contents): ?string
    {
        return $this->detect_mime_type_from_path($path);
    }
    public function detect_mime_type_from_path(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return $this->extensions->lookup_mime_type($extension);
    }
    public function detect_mime_type_from_file(string $path): ?string
    {
        return $this->detect_mime_type_from_path($path);
    }
    public function detect_mime_type_from_buffer(string $contents): ?string
    {
        return null;
    }
    public function lookup_extension(string $mimetype): ?string
    {
        return $this->extensions instanceof Extension_Lookup ? $this->extensions->lookup_extension($mimetype) : null;
    }
    public function lookup_all_extensions(string $mimetype): array
    {
        return $this->extensions instanceof Extension_Lookup ? $this->extensions->lookup_all_extensions($mimetype) : [];
    }
}