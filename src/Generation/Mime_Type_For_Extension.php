<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection\Generation;

class Mime_Type_For_Extension
{
    private string $mime_type;
    private string $extension;
    public function __construct(string $mime_type, string $extension)
    {
        $this->mime_type = $mime_type;
        $this->extension = $extension;
    }
    public function mime_type(): string
    {
        return $this->mime_type;
    }
    public function extension(): string
    {
        return $this->extension;
    }
}