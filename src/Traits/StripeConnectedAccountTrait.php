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

Trait StripeConnectedAccountTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeAccountId;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $stripeAccountType;

    public function getStripeAccountId(): ?string
    {
        return $this->stripeAccountId;
    }

    public function setStripeAccountId(?string $accountId): self
    {
        $this->stripeAccountId = $accountId;

        return $this;
    }

    public function getStripeAccountType(): ?string
    {
        return $this->stripeAccountType;
    }

    public function setStripeAccountType(?string $stripeAccountType): self
    {
        $this->stripeAccountType = $stripeAccountType;

        return $this;
    }
}