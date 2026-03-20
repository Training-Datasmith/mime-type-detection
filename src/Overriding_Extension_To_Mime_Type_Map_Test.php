<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use Php_Unit\Framework\Test_Case;
class Overriding_Extension_To_Mime_Type_Map_Test extends Test_Case
{
    /**
     * @test
     */
    public function overriding_a_mime_type_value(): void
    {
        $inner_map = new Generated_Extension_To_Mime_Type_Map();
        $overriding_map = new Overriding_Extension_To_Mime_Type_Map($inner_map, ['mp3' => 'custom/value']);
        $mp3 = $overriding_map->lookup_mime_type('mp3');
        $mp4 = $overriding_map->lookup_mime_type('mp4');
        self::assert_equals('custom/value', $mp3);
        self::assert_equals('video/mp4', $mp4);
    }
}