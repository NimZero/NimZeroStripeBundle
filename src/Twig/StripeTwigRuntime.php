<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Twig;

use Nimzero\StripeBundle\Service\StripeHelper;
use Twig\Extension\RuntimeExtensionInterface;

class StripeTwigRuntime implements RuntimeExtensionInterface
{
    private StripeHelper $helper;

    public function __construct(StripeHelper $stripeHelper)
    {
        $this->helper = $stripeHelper;
    }

    public function toBaseUnit(int $amount, string $currency): int|float
    {
        return $this->helper->priceToBaseUnit($amount, $currency);
    }
}