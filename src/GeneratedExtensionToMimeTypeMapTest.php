<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use Generator;
use League\Mime_Type_Detection\Generation\Combined_Mime_Type_Provider;
use League\Mime_Type_Detection\Generation\Extension_To_Mime_Type_Map_Generator;
use League\Mime_Type_Detection\Generation\Flysystem_Provided_Mime_Type_Provider;
use League\Mime_Type_Detection\Generation\Js_Http_Mime_Db_Mime_Type_Provider;
use Php_Unit\Framework\Test_Case;
class Generated_Extension_To_Mime_Type_Map_Test extends Test_Case
{
    /**
     * @test
     *
     * @dataProvider expectedLookupResults
     */
    public function looking_up_mimetypes(string $extension, ?string $expected_mime_type): void
    {
        $map = new Generated_Extension_To_Mime_Type_Map();
        $actual = $map->lookup_mime_type($extension);
        $this->assert_equals($expected_mime_type, $actual);
    }
    public static function expected_lookup_results(): Generator
    {
        yield ['jpg', 'image/jpeg'];
        yield ['svg', 'image/svg+xml'];
        yield ['lol', null];
    }
    /**
     * @test
     */
    public function the_generated_map_should_be_up_to_date(): void
    {
        $dumper = new Extension_To_Mime_Type_Map_Generator(new Combined_Mime_Type_Provider(new Js_Http_Mime_Db_Mime_Type_Provider(), new Flysystem_Provided_Mime_Type_Provider()));
        $source = $dumper->dump('GeneratedExtensionToMimeTypeMap');
        $stored_source = file_get_contents(__DIR__ . '/GeneratedExtensionToMimeTypeMap.php');
        $this->assert_equals($source, $stored_source);
    }
    /**
     * @test
     *
     * @dataProvider expectedExtensionResults
     *
     * @param string[] $expectedExtensions
     */
    public function looking_up_extensions(string $mime_type, array $expected_extensions): void
    {
        // arrange
        $map = new Generated_Extension_To_Mime_Type_Map();
        // act
        $actual = $map->lookup_all_extensions($mime_type);
        // assert
        $this->assert_equals($expected_extensions, $actual);
    }
    public static function expected_extension_results(): Generator
    {
        yield ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', ['docx']];
        yield ['image/jpeg', ['jpeg', 'jpg', 'jpe', 'jfif']];
        yield ['image/svg+xml', ['svg', 'svgz']];
        yield ['lol/lol', []];
    }
}