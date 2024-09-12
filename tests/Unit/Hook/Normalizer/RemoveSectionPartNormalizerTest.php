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

namespace Tests\Sylius\TwigHooks\Unit\Hook\Normalizer;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hook\Normalizer\Prefix\RemoveSectionPartNormalizer;

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
