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
    /**
     * @param string                       $magic_file          Path to a custom libmagic database file, or empty string for the system default.
     * @param Extension_To_Mime_Type_Map|null $extension_map    Map used as fallback when finfo returns an inconclusive type.
     * @param int|null                     $buffer_sample_size  Maximum number of bytes read from a buffer for detection; null reads the entire buffer.
     * @param array<string>                $inconclusive_mimetypes MIME types that are too generic to be trusted; triggers fallback to extension lookup.
     */
    public function __construct(string $magic_file = '', ?Extension_To_Mime_Type_Map $extension_map = null, ?int $buffer_sample_size = null, array $inconclusive_mimetypes = self::INCONCLUSIVE_MIME_TYPES)
    {
        $this->finfo = new finfo(FILEINFO_MIME_TYPE, $magic_file);
        $this->extension_map = $extension_map ?: new Generated_Extension_To_Mime_Type_Map();
        $this->buffer_sample_size = $buffer_sample_size;
        $this->inconclusive_mimetypes = $inconclusive_mimetypes;
    }
    /**
     * Detect MIME type from buffer content first; falls back to extension if finfo returns an inconclusive type.
     *
     * @param string          $path     File path (used for extension-based fallback).
     * @param string|resource $contents File contents as a string or a readable stream resource.
     * @return string|null Detected MIME type, or null if detection fails.
     */
    public function detect_mime_type(string $path, $contents): ?string
    {
        $mime_type = is_string($contents) ? @$this->finfo->buffer($this->take_sample($contents)) ?: null : null;
        if ($mime_type !== null && !in_array($mime_type, $this->inconclusive_mimetypes)) {
            return $mime_type;
        }
        return $this->detect_mime_type_from_path($path);
    }
    /**
     * Detect MIME type by looking up the file extension in the extension map.
     *
     * @param string $path File path; only the extension component is used.
     * @return string|null MIME type for the extension, or null if not found.
     */
    public function detect_mime_type_from_path(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return $this->extension_map->lookup_mime_type($extension);
    }
    /**
     * Detect MIME type by reading the file from disk and inspecting its magic bytes via finfo.
     *
     * @param string $path Absolute or relative path to the file to inspect.
     * @return string|null Detected MIME type, or null if finfo cannot determine it.
     */
    public function detect_mime_type_from_file(string $path): ?string
    {
        return @$this->finfo->file($path) ?: null;
    }
    /**
     * Detect MIME type by inspecting the magic bytes in an in-memory buffer via finfo.
     *
     * @param string $contents Raw file contents to inspect.
     * @return string|null Detected MIME type, or null if finfo cannot determine it.
     */
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
    /**
     * Look up the primary file extension for a given MIME type.
     *
     * @param string $mimetype The MIME type to find an extension for (e.g. 'image/jpeg').
     * @return string|null The preferred file extension (without leading dot), or null if not found.
     */
    public function lookup_extension(string $mimetype): ?string
    {
        return $this->extension_map instanceof Extension_Lookup ? $this->extension_map->lookup_extension($mimetype) : null;
    }
    /**
     * Look up all known file extensions for a given MIME type.
     *
     * @param string $mimetype The MIME type to search for (e.g. 'image/jpeg').
     * @return string[] All file extensions associated with this MIME type (without leading dots).
     */
    public function lookup_all_extensions(string $mimetype): array
    {
        return $this->extension_map instanceof Extension_Lookup ? $this->extension_map->lookup_all_extensions($mimetype) : [];
    }
}