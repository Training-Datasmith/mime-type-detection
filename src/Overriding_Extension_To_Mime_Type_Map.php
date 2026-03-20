<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

class Overriding_Extension_To_Mime_Type_Map implements Extension_To_Mime_Type_Map
{
    private \League\Mime_Type_Detection\Extension_To_Mime_Type_Map $inner_map;
    /**
     * @var string[]
     */
    private array $overrides;
    /**
     * @param array<string, string>  $overrides
     */
    public function __construct(Extension_To_Mime_Type_Map $inner_map, array $overrides)
    {
        $this->inner_map = $inner_map;
        $this->overrides = $overrides;
    }
    public function lookup_mime_type(string $extension): ?string
    {
        return $this->overrides[$extension] ?? $this->inner_map->lookup_mime_type($extension);
    }
}