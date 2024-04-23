<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig\Component;

use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

trait HookableComponentTrait
{
    #[ExposeInTemplate('hookable_metadata')]
    public HookableMetadata $hookableMetadata;
}
