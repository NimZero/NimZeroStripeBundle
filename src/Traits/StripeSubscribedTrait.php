<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

Trait StripeSubscribedTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeSubscriptionId;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $stripeSubscriptionStatus;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $stripeSubscriptionStartDate;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeSubscriptionProductId;

    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripeSubscriptionId;
    }

    public function setStripeSubscriptionId(?string $stripeSubscriptionId): self
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;

        return $this;
    }

    public function getStripeSubscriptionStatus(): ?string
    {
        return $this->stripeSubscriptionStatus;
    }

    public function setStripeSubscriptionStatus(?string $stripeSubscriptionStatus): self
    {
        $this->stripeSubscriptionStatus = $stripeSubscriptionStatus;

        return $this;
    }

    public function getStripeSubscriptionStartDate(): ?\DateTimeImmutable
    {
        return $this->stripeSubscriptionStartDate;
    }

    public function setStripeSubscriptionStartDate(?\DateTimeImmutable $stripeSubscriptionStartDate): self
    {
        $this->stripeSubscriptionStartDate = $stripeSubscriptionStartDate;

        return $this;
    }

    public function getStripeSubscriptionProductId(): ?string
    {
        return $this->stripeSubscriptionProductId;
    }

    public function setStripeSubscriptionProductId(?string $stripeSubscriptionProductId): self
    {
        $this->stripeSubscriptionProductId = $stripeSubscriptionProductId;

        return $this;
    }
}