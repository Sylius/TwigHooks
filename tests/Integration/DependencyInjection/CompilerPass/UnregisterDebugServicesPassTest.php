<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Integration\DependencyInjection\CompilerPass;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\TwigHooks\DependencyInjection\CompilerPass\UnregisterDebugServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class UnregisterDebugServicesPassTest extends AbstractCompilerPassTestCase
{
    public function testItRemovesServicesTaggedWithDebugTagWhenDebugIsDisabled(): void
    {
        $this->setDefinition('non_debug_service', $this->createDummyDefinitionWithTag('non_debug_service'));
        $this->setDefinition('some_service', $this->createDummyDefinitionWithTag(UnregisterDebugServicesPass::DEBUG_TAG));

        $this->setParameter('kernel.debug', false);
        $this->compile();

        $this->assertContainerBuilderHasService('non_debug_service');
        $this->assertContainerBuilderNotHasService('some_service');
    }

    public function testItDoesNotRemoveServicesTaggedWithDebugTagWhenDebugIsEnabled(): void
    {
        $this->setDefinition('non_debug_service', $this->createDummyDefinitionWithTag('non_debug_service'));
        $this->setDefinition('some_service', $this->createDummyDefinitionWithTag(UnregisterDebugServicesPass::DEBUG_TAG));

        $this->setParameter('kernel.debug', true);
        $this->compile();

        $this->assertContainerBuilderHasService('non_debug_service');
        $this->assertContainerBuilderHasService('some_service');
    }

    private function createDummyDefinitionWithTag(string $tag): Definition
    {
        $definition = new Definition(\stdClass::class);
        $definition->addTag($tag);

        return $definition;
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new UnregisterDebugServicesPass());
    }
}
