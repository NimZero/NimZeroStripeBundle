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
   * @return bool
   */
  public function isLive();
}