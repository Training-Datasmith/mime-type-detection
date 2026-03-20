<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

class Empty_Extension_To_Mime_Type_Map implements Extension_To_Mime_Type_Map
{
    public function lookup_mime_type(string $extension): ?string
    {
        return null;
    }
}