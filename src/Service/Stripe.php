<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Service;

use Stripe\StripeClient;

class Stripe
{
    private string $api_secret_key;
    private static ?StripeClient $client = null;

    public function __construct(string $api_secret_key)
    {
        $this->api_secret_key = $api_secret_key;
    }

    public function getClient(): StripeClient
    {
        if (is_null(self::$client)) {
            self::$client = new StripeClient($this->api_secret_key);
        }

        return self::$client;
    }
}