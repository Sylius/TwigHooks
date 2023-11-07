<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks;

use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

trait TwigTemplateTrait
{
    /**
     * @param array<string, mixed> $context
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate(string $templateName, array $context = []): RenderedTwigTemplate
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return new RenderedTwigTemplate($twig->render($templateName, $context));
    }
}
