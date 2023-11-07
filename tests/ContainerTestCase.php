<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

abstract class ContainerTestCase extends TestCase
{
    private ?ContainerBuilder $container = null;

    /** @var array<Extension> */
    private array $extensions = [];

    /** @var array<CompilerPassInterface> */
    private array $compilerPasses = [];

    /** @var array<string, mixed> */
    private array $configs = [];

    /** @var array<string, mixed> */
    private array $parameters = [];

    /** @var array<string, Definition> */
    private array $definitions = [];

    protected function setUp(): void
    {
        $this->getMinimalContainerConfiguration();
        $container = $this->createContainer();

        foreach ($this->extensions as $extension) {
            $container->registerExtension($extension);
        }

        foreach ($this->configs as $extension => $config) {
            $container->loadFromExtension($extension, $config);
        }

        foreach ($this->compilerPasses as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        foreach ($this->parameters as $name => $value) {
            $container->setParameter($name, $value);
        }

        foreach ($this->definitions as $id => $definition) {
            $container->setDefinition($id, $definition);
        }

        $container->compile();

        $this->container = $container;
    }

    protected function getContainer(): Container
    {
        if ($this->container === null) {
            throw new \RuntimeException('Container is not initialized. Did you forget to call parent::setUp()?');
        }

        return $this->container;
    }

    private function createContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $container->addCompilerPass(new class implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                foreach ($container->getDefinitions() as $definition) {
                    $definition->setPublic(true);
                }
            }
        });

        return $container;
    }

    protected function addParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    protected function addExtension(Extension $extension): void
    {
        $this->extensions[] = $extension;
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function addConfiguration(string $extension, array $config): void
    {
        $this->configs[$extension] = $config;
    }

    protected function addCompilerPass(CompilerPassInterface $compilerPass): void
    {
        $this->compilerPasses[] = $compilerPass;
    }

    protected function addDefinition(string $id, Definition $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    protected function getMinimalContainerConfiguration(): void
    {
    }
}
