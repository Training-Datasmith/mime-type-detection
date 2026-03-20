<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection\Generation;

interface Mime_Type_Provider
{
    /**
     * @return MimeTypeForExtension[]
     */
    public function provide_mime_types(): array;
}