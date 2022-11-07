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
 * This interface is used to autowire the StripeService class.
 * 
 * @since 1.0.0
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
interface StripeServiceInterface
{
  /**
   * @return StripeClient
   */
  public function getClient();

  /**
   * @since 1.1.0
   * @return bool indicate if your api keys grant live access
   */
  public function isLive();

  /**
   * @since 1.1.0
   * @return string your Stripe API secret key
   */
  public function getApiSecretKey();
  
  /**
   * @since 1.1.0
   * @return string your Stripe API public key
   */
  public function getApiPublicKey();

  /**
   * @since 1.1.0
   * @return ?string your configured api version null if not set
   */
  public function getApiVersion();
}