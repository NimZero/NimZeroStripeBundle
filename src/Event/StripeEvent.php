<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;
use Stripe\Event as StripeLibEvent;

class StripeEvent extends Event
{
    protected StripeLibEvent $stripeEvent;
    protected ?string $message;
    protected int $responseStatus;

    public function __construct(StripeLibEvent $stripeEvent)
    {
        $this->stripeEvent = $stripeEvent;
        $this->responseStatus = Response::HTTP_OK;
        $this->message = null;
    }

    public function resendWebhook(?string $reason = null, int $status = Response::HTTP_BAD_REQUEST, bool $stopPropagation = true)
    {
        if ($status < 300 || $status >=600 ) {
            throw new \Exception("To resend a webhook Stripe need a status in the 3XX, 4XX or 5XX categories");
        }

        if (false === is_null($reason)) {
            $this->message = $reason;
        }

        $this->responseStatus = $status; 

        if (true === $stopPropagation) {
            $this->stopPropagation();
        }
    }

    public function getStripeEvent(): StripeLibEvent
    {
        return $this->stripeEvent;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setResponseStatus(int $status): self
    {
        $this->responseStatus = $status;

        return $this;
    }

    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }
}