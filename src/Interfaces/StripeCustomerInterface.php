<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Interfaces;

/**
 * Intended to be used with Nimzero\StripeBundle\Traits\StripeCustomerTrait
 */
interface StripeCustomerInterface
{
    public function getStripeCustomerId(): ?string;

    public function setStripeCustomerId(?string $customerId): self;
}