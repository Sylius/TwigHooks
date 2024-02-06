<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\NameGenerator;

final class TemplateNameGenerator implements NameGeneratorInterface
{
    public function generate(string $input, string ...$parts): string
    {
        $templatePath = $this->normalizeTemplatePath($input);
        $normalizedParts = array_map($this->normalizeString(...), $parts);

        return implode('.', array_merge([$templatePath], $normalizedParts));
    }

    private function normalizeTemplatePath(string $templatePath): string
    {
        $parts = explode('/', $templatePath);
        $resultParts = [];

        foreach ($parts as $part) {
            $resultPart = str_replace(['@', '.html.twig'], '', $part);
            $resultPart = $this->normalizeString($resultPart);
            $resultParts[] = $resultPart;
        }

        return implode('.', $resultParts);
    }

    private function normalizeString(string $string): string
    {
        $result = trim($string, '_');
        /** @var string $result */
        $result = preg_replace('/(?<!^)[A-Z]/', '_$0', $result);

        return strtolower($result);
    }
}
