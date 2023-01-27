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

Trait StripeCustomerTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeCustomerId;

    public function getStripeCustomerId(): ?string
    {
        return $this->stripeCustomerId;
    }

    public function setStripeCustomerId(?string $customerId): self
    {
        $this->stripeCustomerId = $customerId;

        return $this;
    }
}