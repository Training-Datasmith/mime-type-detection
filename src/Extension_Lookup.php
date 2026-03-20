<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

interface Extension_Lookup
{
    public function lookup_extension(string $mimetype): ?string;
    /**
     * @return string[]
     */
    public function lookup_all_extensions(string $mimetype): array;
}