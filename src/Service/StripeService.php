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

/**
 * This service provide access to a Stripe/StripeClient and helper methods
 * 
 * @since 1.0.0
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
class StripeService implements StripeServiceInterface
{
    private string $api_secret_key;
    private ?string $api_version;
    private static ?StripeClient $client = null;

    public function __construct(string $api_secret_key, ?string $api_version)
    {
        $this->api_secret_key = $api_secret_key;
        $this->api_version = $api_version;
    }

    /**
     * Stripe->getClient()
     * 
     * @return StripeClient initialized with your Stripe API key
     */
    public function getClient(): StripeClient
    {
        if (is_null(self::$client)) {
            self::$client = new StripeClient([
                'api_key' => $this->api_secret_key,
                'stripe_version' => $this->api_version,
            ]);
        }

        return self::$client;
    }

    /**
     * Stripe->isLive()
     * 
     * @return bool indicate if your key grant live access
     */
    public function isLive(): bool
    {
        return str_starts_with(
            $this->api_secret_key,
            'sk_live'
        );
    }
}