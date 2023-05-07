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
use Nimzero\StripeBundle\Exception\StripeModeDisabledException;

class StripeHelper
{
    private array $stripe_config;

    private ?StripeClient $liveClient = null;

    private ?StripeClient $testClient = null;

    /** @var array<String, StripeClient> */
    private array $liveConnectClients = [];

    /** @var array<String, StripeClient> */
    private array $testConnectClients = [];

    public function __construct(array $stripe_config)
    {
        $this->stripe_config = $stripe_config;
    }

    /**
     * Return a configured singleton of the StripeClient in the requested mode 
     * 
     * @param bool $live - Choose between live and test mode
     * @throws StripeModeDisabledException - Thrown when requesting a disabled mode
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
                throw new StripeModeDisabledException('live');
            }

            if (is_null($this->liveClient)) {
                $config['api_key'] = $this->stripe_config['modes']['live']['secret_key'];
                $this->liveClient = new StripeClient($config);
            }

            return $this->liveClient;
        }

        if (false === $this->stripe_config['modes']['test']['enabled']) {
            throw new StripeModeDisabledException('live');
        }

        if (is_null($this->testClient)) {
            $config['api_key'] = $this->stripe_config['modes']['test']['secret_key'];
            $this->testClient = new StripeClient($config);
        }

        return $this->testClient;
    }

    /**
     * Return an singleton of the StripeClient in the requested mode with the given account
     * 
     * @param bool $live - Choose between live and test mode
     * @param string $account - The id of the connected account
     * @throws StripeModeDisabledException - Thrown when requesting a disabled mode
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
                throw new StripeModeDisabledException('live');
            }

            $config['api_key'] = $this->stripe_config['modes']['live']['secret_key'];

            if (array_key_exists($account, $this->liveConnectClients)) {
                return $this->liveConnectClients[$account];
            } else {
                $this->liveConnectClients[$account] = new StripeClient($config);

                return $this->liveConnectClients[$account];
            }
        } else {
            if (false === $this->stripe_config['modes']['test']['enabled']) {
                throw new StripeModeDisabledException('test');
            }

            $config['api_key'] = $this->stripe_config['modes']['test']['secret_key'];

            if (array_key_exists($account, $this->testConnectClients)) {
                return $this->testConnectClients[$account];
            } else {
                $this->testConnectClients[$account] = new StripeClient($config);

                return $this->testConnectClients[$account];
            }
        }
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

    public function priceToBaseUnit(int $amount, string $currency): int|float
    {
        $specials = [
            'BIF' => 1,
            'CLP' => 1,
            'DJF' => 1,
            'GNF' => 1,
            'JPY' => 1,
            'KMF' => 1,
            'KRW' => 1,
            'MGA' => 1,
            'PYG' => 1,
            'RWF' => 1,
            'UGX' => 1,
            'VND' => 1,
            'VUV' => 1,
            'XAF' => 1,
            'XOF' => 1,
            'XPF' => 1,
            'USD' => 2,
            'AED' => 2,
            'AFN' => 2,
            'ALL' => 2,
            'AMD' => 2,
            'ANG' => 2,
            'AOA' => 2,
            'ARS' => 2,
            'AUD' => 2,
            'AWG' => 2,
            'AZN' => 2,
            'BAM' => 2,
            'BBD' => 2,
            'BDT' => 2,
            'BGN' => 2,
            'BMD' => 2,
            'BND' => 2,
            'BOB' => 2,
            'BRL' => 2,
            'BSD' => 2,
            'BWP' => 2,
            'BYN' => 2,
            'BZD' => 2,
            'CAD' => 2,
            'CDF' => 2,
            'CHF' => 2,
            'CNY' => 2,
            'COP' => 2,
            'CRC' => 2,
            'CVE' => 2,
            'CZK' => 2,
            'DKK' => 2,
            'DOP' => 2,
            'DZD' => 2,
            'EGP' => 2,
            'ETB' => 2,
            'EUR' => 2,
            'FJD' => 2,
            'FKP' => 2,
            'GBP' => 2,
            'GEL' => 2,
            'GIP' => 2,
            'GMD' => 2,
            'GTQ' => 2,
            'GYD' => 2,
            'HKD' => 2,
            'HNL' => 2,
            'HRK' => 2,
            'HTG' => 2,
            'HUF' => 2,
            'IDR' => 2,
            'ILS' => 2,
            'INR' => 2,
            'ISK' => 2,
            'JMD' => 2,
            'KES' => 2,
            'KGS' => 2,
            'KHR' => 2,
            'KYD' => 2,
            'KZT' => 2,
            'LAK' => 2,
            'LBP' => 2,
            'LKR' => 2,
            'LRD' => 2,
            'LSL' => 2,
            'MAD' => 2,
            'MDL' => 2,
            'MKD' => 2,
            'MMK' => 2,
            'MNT' => 2,
            'MOP' => 2,
            'MRO' => 2,
            'MUR' => 2,
            'MVR' => 2,
            'MWK' => 2,
            'MXN' => 2,
            'MYR' => 2,
            'MZN' => 2,
            'NAD' => 2,
            'NGN' => 2,
            'NIO' => 2,
            'NOK' => 2,
            'NPR' => 2,
            'NZD' => 2,
            'PAB' => 2,
            'PEN' => 2,
            'PGK' => 2,
            'PHP' => 2,
            'PKR' => 2,
            'PLN' => 2,
            'QAR' => 2,
            'RON' => 2,
            'RSD' => 2,
            'RUB' => 2,
            'SAR' => 2,
            'SBD' => 2,
            'SCR' => 2,
            'SEK' => 2,
            'SGD' => 2,
            'SHP' => 2,
            'SLE' => 2,
            'SLL' => 2,
            'SOS' => 2,
            'SRD' => 2,
            'STD' => 2,
            'SZL' => 2,
            'THB' => 2,
            'TJS' => 2,
            'TOP' => 2,
            'TRY' => 2,
            'TTD' => 2,
            'TWD' => 2,
            'TZS' => 2,
            'UAH' => 2,
            'UYU' => 2,
            'UZS' => 2,
            'WST' => 2,
            'XCD' => 2,
            'YER' => 2,
            'ZAR' => 2,
            'ZMW' => 2,
            'BHD' => 3,
            'JOD' => 3,
            'KWD' => 3,
            'OMR' => 3,
            'TND' => 3,
        ];

        if (!in_array($currency, $specials)) {
            throw new \Exception("Unsupported currency", 1);
        }

        return $amount / ($specials[$currency]);
    }
}