<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hook\Normalizer;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hook\Normalizer\Name\CompositeNameNormalizer;
use Sylius\TwigHooks\Hook\Normalizer\Name\NameNormalizerInterface;

final class CompositeNameNormalizerTest extends TestCase
{
    public function testItNormalizesNameUsingPassedNormalizers(): void
    {
        $dummyNormalizer = $this->createMock(NameNormalizerInterface::class);
        $dummyNormalizer->expects($this->once())->method('normalize')->with('hook_name')->willReturn('hook_name_normalized');
        $zummyNormalizer = $this->createMock(NameNormalizerInterface::class);
        $zummyNormalizer->expects($this->once())->method('normalize')->with('hook_name_normalized')->willReturn('hook_name_normalized_normalized');

        $compositeNameNormalizer = new CompositeNameNormalizer([$dummyNormalizer, $zummyNormalizer]);
        $normalizedHookName = $compositeNameNormalizer->normalize('hook_name');

        $this->assertSame('hook_name_normalized_normalized', $normalizedHookName);
    }
}
