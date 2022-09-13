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

interface StripeInterface
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