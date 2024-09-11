<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            'template' => 'index.html.twig',
        ]);

        $result = $this->getTestSubject()->merge($hookable);

        $this->assertInstanceOf(HookableTemplate::class, $result);
        $this->assertSame('some_hook', $result->hookName);
        $this->assertSame('some_template_hookable', $result->name);
        $this->assertSame('index.html.twig', $result->template);
    }

    public function testItOverridesEarlierHookableWithLaterHookableWithSameTypes(): void
    {
        $earlierHookable = HookableTemplateMotherObject::with([
            'hookName' => 'common.some_hook',
            'name' => 'some_template',
            'template' => 'index.html.twig',
        ]);
        $laterHookable = HookableTemplateMotherObject::with([
            'hookName' => 'app.some_hook',
            'name' => 'some_template',
            'template' => 'base.html.twig',
        ]);

        $result = $this->getTestSubject()->merge($earlierHookable, $laterHookable);

        $this->assertInstanceOf(HookableTemplate::class, $result);
        $this->assertSame('app.some_hook', $result->hookName);
        $this->assertSame('some_template', $result->name);
        $this->assertSame('base.html.twig', $result->template);
    }

    public function testItOverridesEarlierHookableWithLaterHookableWithDifferentTypesAndTheComponentIsTheLastOne(): void
    {
        $earlierHookable = HookableTemplateMotherObject::with([
            'hookName' => 'common.some_hook',
            'name' => 'some_template',
            'template' => 'index.html.twig',
        ]);
        $laterHookable = HookableComponentMotherObject::with([
            'hookName' => 'app.some_hook',
            'name' => 'some_template',
            'component' => 'app:form',
        ]);

        $result = $this->getTestSubject()->merge($earlierHookable, $laterHookable);

        $this->assertInstanceOf(HookableComponent::class, $result);
        $this->assertSame('app.some_hook', $result->hookName);
        $this->assertSame('some_template', $result->name);
        $this->assertSame('app:form', $result->component);
    }

    public function testItOverridesEarlierHookableWithLaterHookableWithDifferentTypesAndTheTemplateIsTheLastOne(): void
    {
        $earlierHookable = HookableComponentMotherObject::with([
            'hookName' => 'common.some_hook',
            'name' => 'some_template',
            'component' => 'app:form',
        ]);
        $laterHookable = HookableTemplateMotherObject::with([
            'hookName' => 'app.some_hook',
            'name' => 'some_template',
            'template' => 'index.html.twig',
        ]);

        $result = $this->getTestSubject()->merge($earlierHookable, $laterHookable);

        $this->assertInstanceOf(HookableTemplate::class, $result);
        $this->assertSame('app.some_hook', $result->hookName);
        $this->assertSame('some_template', $result->name);
        $this->assertSame('index.html.twig', $result->template);
    }

    private function getTestSubject(): HookableMergerInterface
    {
        return new HookableMerger();
    }
}
