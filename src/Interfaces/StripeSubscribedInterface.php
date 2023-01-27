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
 * Intended to be used with Nimzero\StripeBundle\Traits\StripeSubscribedTrait
 */
interface StripeSubscribedInterface
{
    public function getStripeSubscriptionId(): ?string;

    public function setStripeSubscriptionId(?string $stripeSubscriptionId): self;

    public function getStripeSubscriptionStatus(): ?string;

    public function setStripeSubscriptionStatus(?string $stripeSubscriptionStatus): self;

    public function getStripeSubscriptionStartDate(): ?\DateTimeImmutable;

    public function setStripeSubscriptionStartDate(?\DateTimeImmutable $stripeSubscriptionStartDate): self;

    public function getStripeSubscriptionProductId(): ?string;

    public function setStripeSubscriptionProductId(?string $stripeSubscriptionProductId): self;
}