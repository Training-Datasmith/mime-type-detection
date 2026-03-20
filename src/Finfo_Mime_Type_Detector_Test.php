<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection;

use Php_Unit\Framework\Test_Case;
class Finfo_Mime_Type_Detector_Test extends Test_Case
{
    private \League\Mime_Type_Detection\Finfo_Mime_Type_Detector $detector;
    protected function set_up(): void
    {
        $this->detector = new Finfo_Mime_Type_Detector();
    }
    /**
     * @test
     */
    public function detecting_a_csv(): void
    {
        $mime_type = $this->detector->detect_mime_type('something.csv', '');
        $this->assert_equals('text/csv', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_mime_type_from_a_path(): void
    {
        $mime_type = $this->detector->detect_mime_type_from_path('something.svg');
        $this->assert_equals('image/svg+xml', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_mime_type_from_contents(): void
    {
        /** @var string $contents */
        $contents = file_get_contents(__DIR__ . '/../test_files/flysystem.svg');
        $mime_type = $this->detector->detect_mime_type('flysystem.svg', $contents);
        $this->assert_string_starts_with('image/svg', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_mime_type_from_buffer(): void
    {
        /** @var string $contents */
        $contents = file_get_contents(__DIR__ . '/../test_files/flysystem.svg');
        $mime_type = $this->detector->detect_mime_type_from_buffer($contents);
        $this->assert_string_starts_with('image/svg', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_mime_type_from_sampled_buffer(): void
    {
        $this->detector = new Finfo_Mime_Type_Detector('', null, 5);
        /** @var string $contents */
        $contents = file_get_contents(__DIR__ . '/../test_files/flysystem.svg');
        $mime_type = $this->detector->detect_mime_type_from_buffer($contents);
        $this->assert_string_starts_with('image/svg', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_from_contents_falls_back_to_extension_detection(): void
    {
        $mime_type = $this->detector->detect_mime_type('flysystem.svg', '');
        $this->assert_string_starts_with('image/svg+xml', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_from_a_file_location(): void
    {
        $mime_type = $this->detector->detect_mime_type_from_file(__DIR__ . '/../test_files/flysystem.svg');
        $this->assert_string_starts_with('image/svg', $mime_type);
    }
    /**
     * @test
     */
    public function detecting_uses_extensions_when_a_resource_is_presented(): void
    {
        /** @var resource $handle */
        $handle = fopen(__DIR__ . '/../test_files/flysystem.svg', 'r+');
        fclose($handle);
        $mime_type = $this->detector->detect_mime_type('flysystem.svg', $handle);
        $this->assert_equals('image/svg+xml', $mime_type);
    }
}