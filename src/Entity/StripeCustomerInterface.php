<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Entity;

/**
 * 
 * @method ?string getStripeCustomerId() returns the Stripe customer id of the user.
 * @method setStripeCustomerId(?string $stripeCustomerId) set the Stripe customer id of the user.
 * 
 * @since 1.0.0
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
interface StripeCustomerInterface
{
  /**
   * Returns the Stripe customer id of the user.
   * 
   * This value should be stored in a nullable string property.
   * 
   * @return ?string
   */
  public function getStripeCustomerId();
  
  /**
   * Sets the Stripe customer id of the user.
   * 
   * This value should be stored in a nullable string property.
   * 
   * @param ?string $stripeCustomerId
   * @return self
   */
  public function setStripeCustomerId(?string $stripeCustomerId);
}