<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\LiveComponent;

use Sylius\TwigHooks\Bag\DataBag;
use Sylius\TwigHooks\Bag\ScalarDataBag;
use Sylius\TwigHooks\Hook\Metadata\HookMetadata;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

trait HookableLiveComponentTrait
{
    #[LiveProp(hydrateWith: 'hydrateHookableMetadata', dehydrateWith: 'dehydrateHookableMetadata')]
    #[ExposeInTemplate('hookable_metadata')]
    public ?HookableMetadata $hookableMetadata = null;

    public function hydrateHookableMetadata($data): HookableMetadata
    {
        return new HookableMetadata(
            new HookMetadata($data['renderedBy'], new DataBag()),
            new DataBag(),
            new ScalarDataBag(json_decode($data['configuration'], true)),
            $data['prefixes'] ?? [],
        );
    }

    public function dehydrateHookableMetadata(?HookableMetadata $metadata = null): array
    {
        return $metadata === null ? [] : [
            'renderedBy' => $metadata->renderedBy->name,
            'configuration' => json_encode($metadata->configuration->all()),
            'prefixes' => $metadata->prefixes,
        ];
    }
}
