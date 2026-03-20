<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection\Generation;

use function array_unique;
use function join;
use const PHP_EOL;
class Extension_To_Mime_Type_Map_Generator
{
    private \League\Mime_Type_Detection\Generation\Mime_Type_Provider $provider;
    public function __construct(Mime_Type_Provider $provider)
    {
        $this->provider = $provider;
    }
    public function dump(string $class_name): string
    {
        /** @var string[] $mimeTypes */
        $mime_types = [];
        $reverse_lookup = [];
        $compiled_reverse_lookup = [];
        foreach ($this->provider->provide_mime_types() as $mime_type_for_extension) {
            $mime_types[$mime_type_for_extension->extension()] = PHP_EOL . "        '{$mime_type_for_extension->extension()}' => '{$mime_type_for_extension->mime_type()}',";
            $extensions = $reverse_lookup[$mime_type_for_extension->mime_type()] ?? [];
            $extensions[] = $mime_type_for_extension->extension();
            $reverse_lookup[$mime_type_for_extension->mime_type()] = $extensions;
            $extensions_as_code = '[\'' . join('\', \'', array_unique($extensions)) . '\']';
            $compiled_reverse_lookup[$mime_type_for_extension->mime_type()] = PHP_EOL . "        '{$mime_type_for_extension->mime_type()}' => {$extensions_as_code},";
        }
        ksort($mime_types, SORT_NATURAL);
        $template = file_get_contents(__DIR__ . '/ExtensionToMimeTypeMap.php.template');
        $template = str_replace('ExtensionToMimeTypeMapClass', $class_name, $template);
        $template = str_replace(' = [\'ext2mime\']', ' = [' . join('', $mime_types) . PHP_EOL . '    ]', $template);
        return str_replace(' = [\'mime2ext\']', ' = [' . join('', $compiled_reverse_lookup) . PHP_EOL . '    ]', $template);
    }
}