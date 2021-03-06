<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ThemeBundle\Translation;

use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ThemeAwareTranslator implements TranslatorInterface, TranslatorBagInterface, WarmableInterface, LocaleAwareInterface, LegacyTranslatorInterface
{
    /** @var LegacyTranslatorInterface&TranslatorBagInterface */
    private $translator;

    /** @var ThemeContextInterface */
    private $themeContext;

    public function __construct(LegacyTranslatorInterface $translator, ThemeContextInterface $themeContext)
    {
        if (!$translator instanceof TranslatorBagInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The Translator "%s" must implement TranslatorInterface and TranslatorBagInterface.',
                get_class($translator)
            ));
        }

        $this->translator = $translator;
        $this->themeContext = $themeContext;
    }

    /**
     * Passes through all unknown calls onto the translator object.
     */
    public function __call(string $method, array $arguments)
    {
        $translator = $this->translator;
        $arguments = array_values($arguments);

        return $translator->$method(...$arguments);
    }

    /**
     * @psalm-suppress MissingParamType Two interfaces defining the same method
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $this->transformLocale($locale));
    }

    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $this->transformLocale($locale));
    }

    public function getLocale(): string
    {
        return $this->translator->getLocale();
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale): void
    {
        /** @var string $locale */
        $locale = $this->transformLocale($locale);

        $this->translator->setLocale($locale);
    }

    public function getCatalogue($locale = null): MessageCatalogueInterface
    {
        return $this->translator->getCatalogue($locale);
    }

    public function warmUp($cacheDir): void
    {
        if ($this->translator instanceof WarmableInterface) {
            $this->translator->warmUp($cacheDir);
        }
    }

    private function transformLocale(?string $locale): ?string
    {
        $theme = $this->themeContext->getTheme();

        if (null === $theme) {
            return $locale;
        }

        if (null === $locale) {
            $locale = $this->getLocale();
        }

        return $locale . '@' . str_replace('/', '-', $theme->getName());
    }
}
