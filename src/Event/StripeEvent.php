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

/**
 * StripeEvent is a base class to process Stripe events
 * 
 * This class is ussed by the bundle's webhook endpoint
 * 
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
class StripeEvent extends Event
{
    protected Bool $failed;
    protected ?String $message;
    protected \Stripe\Event $event;

    /**
     * @param \Stripe\Event $event The Stripe event object.
     */
    public function __construct(\Stripe\Event $event)
    {
        $this->failed = false;
        $this->message = null;
        $this->event = $event;
    }

    /**
     * StripeEvent->failed()
     * 
     * Set the event processing as failed and provide an error message wich is sent in a HTTP 500 (internal server error) response.
     * The message can be overriden by the setMessage() methode.
     */
    public function failed(String $message = null): void
    {
        $this->failed = true;
        $this->message = $message;
    }

    /**
     * StripeEvent->isFailed()
     * 
     * @return bool true if processing has failed false otherwise
     */
    public function isFailed(): Bool
    {
        return $this->failed;
    }

    /**
     * StripeEvent->setMessage()
     * 
     * Allow to set a message that will be returned in the response.
     * Override the message set with the failed() method.
     */
    public function setMessage(?String $message): void
    {
        $this->message = $message;
    }

    /**
     * StripeEvent->getMessage()
     * 
     * @return ?string the message set by StripeEvent->failed() or StripeEvent->setMessage()
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * StripeEvent->getEvent()
     * 
     * @return \Stripe\Event the Stripe\Event object created when receiving the event
     */
    public function getEvent(): \Stripe\Event
    {
        return $this->event;
    }

    /**
     * StripeEvent->getObject()
     * 
     * @return \Stripe\StripeObject the object from the event
     */
    public function getObject(): \Stripe\StripeObject
    {
        /** @phpstan-ignore-next-line */
        return $this->event->data->object;
    }

    /**
     * StripeEvent->getConnectAccount()
     * 
     * Used by the Stripe Connect API.
     * @return ?string the ID of the Stripe account concerned by the event.
     */
    public function getConnectAccount(): ?String
    {
        return $this->event->account != "" ? $this->event->account : null;
    }
}