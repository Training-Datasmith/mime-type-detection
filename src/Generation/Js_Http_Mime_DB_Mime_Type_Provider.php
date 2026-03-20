<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection\Generation;

class Js_Http_Mime_Db_Mime_Type_Provider implements Mime_Type_Provider
{
    public function provide_mime_types(): array
    {
        $result = [];
        $aggregated = (string) @file_get_contents('https://raw.githubusercontent.com/jshttp/mime-db/master/db.json');
        foreach (json_decode($aggregated, true) as $mime_type => $information) {
            /** @var string[] $extensions */
            $extensions = $information['extensions'] ?? [];
            foreach ($extensions as $extension) {
                $result[] = new Mime_Type_For_Extension($mime_type, $extension);
            }
        }
        return $result;
    }
}