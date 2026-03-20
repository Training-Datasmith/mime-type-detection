<?php

declare (strict_types=1);
namespace League\Mime_Type_Detection\Generation;

class Combined_Mime_Type_Provider implements Mime_Type_Provider
{
    /**
     * @var MimeTypeProvider[]
     */
    private array $providers;
    public function __construct(Mime_Type_Provider ...$providers)
    {
        $this->providers = $providers;
    }
    public function provide_mime_types(): array
    {
        $result = [];
        foreach ($this->providers as $provider) {
            array_push($result, ...$provider->provide_mime_types());
        }
        return $result;
    }
}