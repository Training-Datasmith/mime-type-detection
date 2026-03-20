<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use const PATHINFO_EXTENSION;
class Extension_Mime_Type_Detector implements Mime_Type_Detector, Extension_Lookup
{
    private \League\Mime_Type_Detection\Extension_To_Mime_Type_Map $extensions;
    /**
     * @param Extension_To_Mime_Type_Map|null $extensions Custom extension map, or null to use the bundled generated map.
     */
    public function __construct(?Extension_To_Mime_Type_Map $extensions = null)
    {
        $this->extensions = $extensions ?: new Generated_Extension_To_Mime_Type_Map();
    }
    /**
     * Detect MIME type from the file extension in $path; the $contents parameter is ignored.
     *
     * @param string          $path     File path whose extension will be looked up.
     * @param string|resource $contents Not used by this detector; provided for interface compatibility.
     * @return string|null MIME type, or null if the extension is not in the map.
     */
    public function detect_mime_type(string $path, $contents): ?string
    {
        return $this->detect_mime_type_from_path($path);
    }
    /**
     * Detect MIME type by looking up the lowercased extension of $path in the extension map.
     *
     * @param string $path File path; only the extension is used.
     * @return string|null MIME type for the extension, or null if not found.
     */
    public function detect_mime_type_from_path(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return $this->extensions->lookup_mime_type($extension);
    }
    /**
     * Detect MIME type from a file path; delegates to detect_mime_type_from_path (no I/O performed).
     *
     * @param string $path Path to the file; only the extension is examined.
     * @return string|null MIME type, or null if the extension is not in the map.
     */
    public function detect_mime_type_from_file(string $path): ?string
    {
        return $this->detect_mime_type_from_path($path);
    }
    /**
     * Extension-based detector cannot detect MIME type from raw bytes; always returns null.
     *
     * @param string $contents Raw file contents (not used).
     * @return null Always null — use Finfo_Mime_Type_Detector for buffer-based detection.
     */
    public function detect_mime_type_from_buffer(string $contents): ?string
    {
        return null;
    }
    /**
     * Look up the primary file extension for a given MIME type (reverse lookup).
     *
     * @param string $mimetype MIME type to search for (e.g. 'image/png').
     * @return string|null Primary extension without leading dot, or null if not found or map does not support reverse lookup.
     */
    public function lookup_extension(string $mimetype): ?string
    {
        return $this->extensions instanceof Extension_Lookup ? $this->extensions->lookup_extension($mimetype) : null;
    }
    /**
     * Look up all known file extensions for a given MIME type (reverse lookup).
     *
     * @param string $mimetype MIME type to search for (e.g. 'image/jpeg').
     * @return string[] All associated file extensions without leading dots, or empty array if not found.
     */
    public function lookup_all_extensions(string $mimetype): array
    {
        return $this->extensions instanceof Extension_Lookup ? $this->extensions->lookup_all_extensions($mimetype) : [];
    }
}