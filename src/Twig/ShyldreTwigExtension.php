<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Twig;

use Nimzero\StripeBundle\Service\Stripe;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StripeTwigExtension extends AbstractExtension
{
    private Stripe $stripe;

    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }

    public function getFunctions(): array
    {
        // is_safe => [html] allow the html to be interpreted instead of escaped
        return [
            new TwigFunction('shyldreEmbed', [$this, 'embed'], ['is_safe' => ['html']]),
            new TwigFunction('shyldreScript', [$this, 'script'], ['is_safe' => ['html']]),
        ];
    }

    public function embed(): string
    {
        return $this->stripe;
    }

    public function script(String $pageId, String $customerId): string
    {
        return $this->stripe;
    }
}