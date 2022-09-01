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

abstract class StripeSubscribed
{
    protected ?String $stripeCustomerId;

    public function getStripeCustomerId(): ?String
    {
        return $this->stripeCustomerId;
    }

    public function setStripeCustomerId(?String $stripeCustomerId): self
    {
        $this->stripeCustomerId = $stripeCustomerId;

        return $this;
    }
}