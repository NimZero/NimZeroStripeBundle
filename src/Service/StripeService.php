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
    private array $stripe_config;
    private static ?StripeClient $client = null;

    public function __construct(array $stripe_config)
    {
        $this->stripe_config = $stripe_config;
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
                'api_key' => $this->stripe_config['api_secret_key'],
                'stripe_version' => $this->stripe_config['api_version'],
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
            $this->stripe_config['api_secret_key'],
            'sk_live'
        );
    }

    public function getApiSecretKey(): string
    {
        return $this->stripe_config['api_secret_key'];
    }

    public function getApiPublicKey(): string
    {
        return $this->stripe_config['api_public_key'];
    }

    public function getApiVersion(): ?string
    {
        return $this->stripe_config['api_version'];
    }
}