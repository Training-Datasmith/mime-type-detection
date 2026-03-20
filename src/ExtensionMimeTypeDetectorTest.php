<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use Generator;
use Php_Unit\Framework\Test_Case;
class Extension_Mime_Type_Detector_Test extends Test_Case
{
    /**
     * @test
     *
     * @dataProvider expectedLookupResults
     */
    public function looking_up_mimetype(string $path, ?string $expected_mime_type): void
    {
        $detector = new Extension_Mime_Type_Detector();
        $this->assert_equals($expected_mime_type, $detector->detect_mime_type($path, 'contents'));
        $this->assert_equals($expected_mime_type, $detector->detect_mime_type_from_file($path));
        $this->assert_equals($expected_mime_type, $detector->detect_mime_type_from_path($path));
    }
    /**
     * @test
     */
    public function detecting_from_bugger_always_returns_null(): void
    {
        $detector = new Extension_Mime_Type_Detector();
        /** @var string $contents */
        $contents = file_get_contents(__DIR__ . '/../test_files/flysystem.svg');
        $mime_type = $detector->detect_mime_type_from_buffer($contents);
        $this->assert_null($mime_type);
    }
    public static function expected_lookup_results(): Generator
    {
        yield ['thing.jpg', 'image/jpeg'];
        yield ['file.svg', 'image/svg+xml'];
        yield ['nothing.lol', null];
    }
}