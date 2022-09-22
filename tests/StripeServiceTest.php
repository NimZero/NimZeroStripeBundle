<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Tests;

use Nimzero\StripeBundle\Service\StripeService;
use PHPUnit\Framework\TestCase;

class StripeServiceTest extends TestCase
{
  public function testIsLive(): void
  {
    $stripe = new StripeService('sk_live_xxxxxxxxxxxxxxx');

    $this->assertTrue($stripe->isLive(), 'Stripe service isLive() should return true with key sk_live_xxxxxxxxxxxxxxx');

    $stripe = new StripeService('sk_test_xxxxxxxxxxxxxxx');

    $this->assertFalse($stripe->isLive(), 'Stripe service isLive() should return false with key sk_test_xxxxxxxxxxxxxxx');
  }

  public function testGetClient()
  {
    $stripe = new StripeService('sk_live_xxxxxxxxxxxxxxx');

    $client = $stripe->getClient();

    $this->assertSame($client->getApiKey(), 'sk_live_xxxxxxxxxxxxxxx', 'StripeClient not initialized correctly, invalid API key');
  }
}