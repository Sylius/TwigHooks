<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\LiveComponent;

use Sylius\TwigHooks\Bag\DataBag;
use Sylius\TwigHooks\Hook\Metadata\HookMetadata;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

trait HookableLiveComponentTrait
{
    #[LiveProp(hydrateWith: 'hydrateHookableMetadata', dehydrateWith: 'dehydrateHookableMetadata')]
    #[ExposeInTemplate('hookable_metadata')]
    public HookableMetadata $hookableMetadata;

    public function hydrateHookableMetadata($data): HookableMetadata
    {
        return new HookableMetadata(
            new HookMetadata($data['renderedBy'], new DataBag()),
            new DataBag(),
            new DataBag(),
            $data['prefixes'] ?? [],
        );
    }

    public function dehydrateHookableMetadata(HookableMetadata $metadata): array
    {
        return [
            'renderedBy' => $metadata->renderedBy->name,
            'prefixes' => $metadata->prefixes,
        ];
    }
}
