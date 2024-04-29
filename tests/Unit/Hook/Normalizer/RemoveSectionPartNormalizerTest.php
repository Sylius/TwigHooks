<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hook\Normalizer;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hook\Normalizer\RemoveSectionPartNormalizer;

final class RemoveSectionPartNormalizerTest extends TestCase
{
    public function testItRemovesSectionPartFromName(): void
    {
        $removeSectionPartNormalizer = new RemoveSectionPartNormalizer(separator: '#');

        $this->assertSame('hook_name_section', $removeSectionPartNormalizer->normalize('hook_name_section'));
        $this->assertSame('hook_name', $removeSectionPartNormalizer->normalize('hook_name#section'));
        $this->assertSame('hook_name.section', $removeSectionPartNormalizer->normalize('hook_name.section'));
    }

    public function testItRemovesSectionPartFromNameWithUsingOtherSeparatorThanHash(): void
    {
        $removeSectionPartNormalizer = new RemoveSectionPartNormalizer(separator: '|');

        $this->assertSame('hook_name_section', $removeSectionPartNormalizer->normalize('hook_name_section'));
        $this->assertSame('hook_name', $removeSectionPartNormalizer->normalize('hook_name|section'));
        $this->assertSame('hook_name.section', $removeSectionPartNormalizer->normalize('hook_name.section'));
    }

    public function testItSkipsRemovingSectionPartIfSeparatorIsFalse(): void
    {
        $removeSectionPartNormalizer = new RemoveSectionPartNormalizer(separator: false);

        $this->assertSame('hook_name_section', $removeSectionPartNormalizer->normalize('hook_name_section'));
        $this->assertSame('hook_name#section', $removeSectionPartNormalizer->normalize('hook_name#section'));
        $this->assertSame('hook_name.section', $removeSectionPartNormalizer->normalize('hook_name.section'));
    }
}
