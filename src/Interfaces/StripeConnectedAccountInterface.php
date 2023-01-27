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
 * Intended to be used with Nimzero\StripeBundle\Traits\StripeConnectedAccountTrait
 */
interface StripeConnectedAccountInterface
{
    const STANDARD = 'standard';
    const EXPRESS = 'express';
    const CUSTOM = 'custom';

    public function getStripeAccountId(): ?string;

    public function setStripeAccountId(?string $accountId): self;

    public function getStripeAccountType(): ?string;

    public function setStripeAccountType(?string $stripeAccountType): self;
}