<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Integration\Twig\Runtime;

use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMetadataMotherObject;
use Twig\Error\RuntimeError;

/**
 * @group kernel-required
 */
final class HooksRuntimeTest extends KernelTestCase
{
    public function testItReturnsHookableMetadata(): void
    {
        $runtime = $this->getTestSubject();
        $metadata = HookableMetadataMotherObject::some();
        $context = ['hookable_metadata' => $metadata];

        $this->assertSame($metadata, $runtime->getHookableMetadata($context));
    }

    public function testItThrowsExceptionWhenTryingToAccessHookableMetadataFromNonHookable(): void
    {
        $runtime = $this->getTestSubject();
        $context = [];

        $this->expectException(RuntimeError::class);
        $this->expectExceptionMessage('Trying to access hookable context inside a non-hookable.');

        $runtime->getHookableMetadata($context);
    }

    public function testItReturnsHookableContext(): void
    {
        $runtime = $this->getTestSubject();
        $context = new ParameterBag();
        $metadata = HookableMetadataMotherObject::withContext($context);

        $this->assertSame($context, $runtime->getHookableContext(['hookable_metadata' => $metadata]));
    }

    public function testItReturnsHookableConfiguration(): void
    {
        $runtime = $this->getTestSubject();
        $configuration = new ParameterBag();
        $metadata = HookableMetadataMotherObject::withConfiguration($configuration);

        $this->assertSame($configuration, $runtime->getHookableConfiguration(['hookable_metadata' => $metadata]));
    }

    private function getTestSubject(): HooksRuntime
    {
        return $this->getContainer()->get(HooksRuntime::class);
    }
}
