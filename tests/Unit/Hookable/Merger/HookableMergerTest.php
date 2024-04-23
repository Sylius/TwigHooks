<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Merger;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Merger\HookableMerger;
use Sylius\TwigHooks\Hookable\Merger\HookableMergerInterface;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableComponentMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookableMergerTest extends TestCase
{
    public function testItReturnsUnchangedHookableWhenOnlyOneHookableIsPassed(): void
    {
        $hookable = HookableTemplateMotherObject::with([
            'hookName' => 'some_hook',
            'name' => 'some_template_hookable',
            'target' => 'index.html.twig',
        ]);

        $result = $this->getTestSubject()->merge($hookable);

        $this->assertInstanceOf(HookableTemplate::class, $result);
        $this->assertSame('some_hook', $result->hookName);
        $this->assertSame('some_template_hookable', $result->name);
        $this->assertSame('index.html.twig', $result->target);
    }

    public function testItOverridesEarlierHookableWithLaterHookableWithSameTypes(): void
    {
        $earlierHookable = HookableTemplateMotherObject::with([
            'hookName' => 'common.some_hook',
            'name' => 'some_template',
            'target' => 'index.html.twig',
        ]);
        $laterHookable = HookableTemplateMotherObject::with([
            'hookName' => 'app.some_hook',
            'name' => 'some_template',
            'target' => 'base.html.twig',
        ]);

        $result = $this->getTestSubject()->merge($earlierHookable, $laterHookable);

        $this->assertInstanceOf(HookableTemplate::class, $result);
        $this->assertSame('app.some_hook', $result->hookName);
        $this->assertSame('some_template', $result->name);
        $this->assertSame('base.html.twig', $result->target);
    }

    public function testItOverridesEarlierHookableWithLaterHookableWithDifferentTypesAndTheComponentIsTheLastOne(): void
    {
        $earlierHookable = HookableTemplateMotherObject::with([
            'hookName' => 'common.some_hook',
            'name' => 'some_template',
            'target' => 'index.html.twig',
        ]);
        $laterHookable = HookableComponentMotherObject::with([
            'hookName' => 'app.some_hook',
            'name' => 'some_template',
            'target' => 'app:form',
        ]);

        $result = $this->getTestSubject()->merge($earlierHookable, $laterHookable);

        $this->assertInstanceOf(HookableComponent::class, $result);
        $this->assertSame('app.some_hook', $result->hookName);
        $this->assertSame('some_template', $result->name);
        $this->assertSame('app:form', $result->target);
    }

    public function testItOverridesEarlierHookableWithLaterHookableWithDifferentTypesAndTheTemplateIsTheLastOne(): void
    {
        $earlierHookable = HookableComponentMotherObject::with([
            'hookName' => 'common.some_hook',
            'name' => 'some_template',
            'target' => 'app:form',
        ]);
        $laterHookable = HookableTemplateMotherObject::with([
            'hookName' => 'app.some_hook',
            'name' => 'some_template',
            'target' => 'index.html.twig',
        ]);

        $result = $this->getTestSubject()->merge($earlierHookable, $laterHookable);

        $this->assertInstanceOf(HookableTemplate::class, $result);
        $this->assertSame('app.some_hook', $result->hookName);
        $this->assertSame('some_template', $result->name);
        $this->assertSame('index.html.twig', $result->target);
    }

    private function getTestSubject(): HookableMergerInterface
    {
        return new HookableMerger();
    }
}
