<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use const FILEINFO_MIME_TYPE;
use finfo;
use const PATHINFO_EXTENSION;
class Finfo_Mime_Type_Detector implements Mime_Type_Detector, Extension_Lookup
{
    private const INCONCLUSIVE_MIME_TYPES = ['application/x-empty', 'text/plain', 'text/x-asm', 'application/octet-stream', 'inode/x-empty'];
    private \finfo $finfo;
    private \League\Mime_Type_Detection\Extension_To_Mime_Type_Map $extension_map;
    private ?int $buffer_sample_size;
    /**
     * @var array<string>
     */
    private array $inconclusive_mimetypes;
    public function __construct(string $magic_file = '', ?Extension_To_Mime_Type_Map $extension_map = null, ?int $buffer_sample_size = null, array $inconclusive_mimetypes = self::INCONCLUSIVE_MIME_TYPES)
    {
        $this->finfo = new finfo(FILEINFO_MIME_TYPE, $magic_file);
        $this->extension_map = $extension_map ?: new Generated_Extension_To_Mime_Type_Map();
        $this->buffer_sample_size = $buffer_sample_size;
        $this->inconclusive_mimetypes = $inconclusive_mimetypes;
    }
    public function detect_mime_type(string $path, $contents): ?string
    {
        $mime_type = is_string($contents) ? @$this->finfo->buffer($this->take_sample($contents)) ?: null : null;
        if ($mime_type !== null && !in_array($mime_type, $this->inconclusive_mimetypes)) {
            return $mime_type;
        }
        return $this->detect_mime_type_from_path($path);
    }
    public function detect_mime_type_from_path(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return $this->extension_map->lookup_mime_type($extension);
    }
    public function detect_mime_type_from_file(string $path): ?string
    {
        return @$this->finfo->file($path) ?: null;
    }
    public function detect_mime_type_from_buffer(string $contents): ?string
    {
        return @$this->finfo->buffer($this->take_sample($contents)) ?: null;
    }
    private function take_sample(string $contents): string
    {
        if ($this->buffer_sample_size === null) {
            return $contents;
        }
        return (string) substr($contents, 0, $this->buffer_sample_size);
    }
    public function lookup_extension(string $mimetype): ?string
    {
        return $this->extension_map instanceof Extension_Lookup ? $this->extension_map->lookup_extension($mimetype) : null;
    }
    public function lookup_all_extensions(string $mimetype): array
    {
        return $this->extension_map instanceof Extension_Lookup ? $this->extension_map->lookup_all_extensions($mimetype) : [];
    }
}