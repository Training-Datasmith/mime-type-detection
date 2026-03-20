<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use Php_Unit\Framework\Test_Case;
class Empty_Extension_To_Mime_Type_Map_Test extends Test_Case
{
    /**
     * @test
     */
    public function lookup_up_mimetypes_results_in_no_result(): void
    {
        $map = new Empty_Extension_To_Mime_Type_Map();
        $this->assert_null($map->lookup_mime_type('jpg'));
        $this->assert_null($map->lookup_mime_type('jpeg'));
        $this->assert_null($map->lookup_mime_type('svg'));
    }
}