<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Service;

use Stripe\StripeClient;

class StripeHelper
{
    private array $stripe_config;

    private ?StripeClient $liveClient = null;

    private ?StripeClient $testClient = null;

    public function __construct(array $stripe_config)
    {
        $this->stripe_config = $stripe_config;
    }

    /**
     * Return a configured singleton of the StripeClient in the requested mode 
     * 
     * @param bool $live - Choose between live and test mode
     * @throws \Exception - Thrown when request a disabled mode
     * @return StripeClient
     */
    public function getClient(bool $live = false): StripeClient
    {
        $config = [];

        if (!is_null($this->stripe_config['api_version'])) {
            $config['stripe_version'] = $this->stripe_config['api_version'];
        }

        if (true === $live) {
            if (false === $this->stripe_config['modes']['live']['enabled']) {
                throw new \Exception("Live mode is disabled.");
            }

            if (is_null($this->liveClient)) {
                $config['api_key'] = $this->stripe_config['modes']['live']['secret_key'];
                $this->liveClient = new StripeClient($config);
            }

            return $this->liveClient;
        }

        if (false === $this->stripe_config['modes']['test']['enabled']) {
            throw new \Exception("Test mode is disabled.");
        }

        if (is_null($this->testClient)) {
            $config['api_key'] = $this->stripe_config['modes']['test']['secret_key'];
            $this->testClient = new StripeClient($config);
        }

        return $this->testClient;
    }

    /**
     * Return an instance of the StripeClient in the requested mode with the given account
     * 
     * @param bool $live - Choose between live and test mode
     * @param string $account - The id of the connected account
     * @throws \Exception - Thrown when request a disabled mode
     * @return StripeClient
     */
    public function getConnectedAccountClient(string $account, bool $live = false): StripeClient
    {
        $config = [
            'stripe_account' => $account,
        ];

        if (!is_null($this->stripe_config['api_version'])) {
            $config['stripe_version'] = $this->stripe_config['api_version'];
        }

        if (true === $live) {
            if (false === $this->stripe_config['modes']['live']['enabled']) {
                throw new \Exception("Live mode is disabled.");
            }

            $config['api_key'] = $this->stripe_config['modes']['live']['secret_key'];
        } else {
            if (false === $this->stripe_config['modes']['test']['enabled']) {
                throw new \Exception("Test mode is disabled.");
            }

            $config['api_key'] = $this->stripe_config['modes']['test']['secret_key'];
        }

        return new StripeClient($config);
    }

    public function getApiVersion(): ?string
    {
        return $this->stripe_config['api_version'];
    }

    public function getPublicKey(bool $live): string
    {
        if (true === $live) {
            if (false === $this->stripe_config['modes']['live']['enabled']) {
                throw new \Exception("Live mode is disabled.");
            }

            return $this->stripe_config['modes']['live']['public_key'];
        }

        if (false === $this->stripe_config['modes']['test']['enabled']) {
            throw new \Exception("Test mode is disabled.");
        }

        return $this->stripe_config['modes']['test']['public_key'];
    }

    public function getSecretKey(bool $live): string
    {
        if (true === $live) {
            if (false === $this->stripe_config['modes']['live']['enabled']) {
                throw new \Exception("Live mode is disabled.");
            }

            return $this->stripe_config['modes']['live']['secret_key'];
        }

        if (false === $this->stripe_config['modes']['test']['enabled']) {
            throw new \Exception("Test mode is disabled.");
        }

        return $this->stripe_config['modes']['test']['secret_key'];
    }

    public function getWebhookSecret(bool $live): string
    {
        if (true === $live) {
            if (false === $this->stripe_config['modes']['live']['enabled']) {
                throw new \Exception("Live mode is disabled.");
            }

            return $this->stripe_config['modes']['live']['webhook_secret'];
        }

        if (false === $this->stripe_config['modes']['test']['enabled']) {
            throw new \Exception("Test mode is disabled.");
        }

        return $this->stripe_config['modes']['test']['webhook_secret'];
    }

    public function getWebhookTolerance(): int
    {
        return $this->stripe_config['tolerance'];
    }
}