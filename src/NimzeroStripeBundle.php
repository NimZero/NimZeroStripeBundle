<?php

/*
 * This file is part of the Stripe Bundle.
 *
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NimzeroStripeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}