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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StripeTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('toBaseUnit', [StripeTwigRuntime::class, 'toBaseUnit']),
        ];
    }
}