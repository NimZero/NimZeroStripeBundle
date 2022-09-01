<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StripeEvent extends Event
{
    protected Bool $failed;
    protected String $message;
    protected \Stripe\Event $event;

    /**
     * @param \Stripe\Event $event The Stripe event object.
     */
    public function __construct(\Stripe\Event $event)
    {
        $this->failed = false;
        $this->message = '';
        $this->event = $event;
    }

    /**
     * abstractStripeEvent->failed()
     * 
     * Set the event processing as failed and provide an error message wich is sent in the response.
     * The message can be overriden by the setMessage() methode.
     */
    public function failed(String $message = ''): void
    {
        $this->failed = true;
        $this->message = $message;
    }

    public function isFailed(): Bool
    {
        return $this->failed;
    }

    /**
     * abstractStripeEvent->setMessage()
     * 
     * Allow to set a message that will be returned in the response.
     * Override the message set with the failed() methode.
     */
    public function setMessage(String $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getEvent(): \Stripe\Event
    {
        return $this->event;
    }

    public function getObject(): \Stripe\StripeObject
    {
        /** @phpstan-ignore-next-line */
        return $this->event->data->object;
    }

    /**
     * abstractStripeEvent->getConnectAccount()
     * 
     * Return the ID of the Stripe account concerned by the event.
     * Used by the Stripe Connect API.
     */
    public function getConnectAccount(): ?String
    {
        return $this->event->account != "" ? $this->event->account : null;
    }
}