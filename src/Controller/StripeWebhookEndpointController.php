<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Nimzero\StripeBundle\Event\StripeEvent;
use Nimzero\StripeBundle\Service\StripeHelper;

class StripeWebhookEndpointController extends AbstractController
{
    public function stripeWebhookEndpoint(string $mode, StripeHelper $helper, Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        /**
         * The signature from the headers
         * @var string
         */
        $signature_header = $request->headers->get('STRIPE_SIGNATURE');

        /**
         * The payload of the request
         * @var string
         */
        $payload = $request->getContent();

        /**
         * The webhook signing secret
         */
        if ('live' === $mode) {
            $secret = $helper->getWebhookSecret(live: true);
        }
        else {
            $secret = $helper->getWebhookSecret(live: false);
        }

        /** 
         * The configured tolerance
         * @var int
         */
        $tolerance = $helper->getWebhookTolerance();

        try {
            // Use the Stripe lib to validate and build the event from the request
            $event = \Stripe\Webhook::constructEvent(
                $payload, $signature_header, $secret, $tolerance
            );

        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Unexpected Value', Response::HTTP_BAD_REQUEST);

        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('Signature Verification Failed', Response::HTTP_BAD_REQUEST);
        }

        // Prefix the stripe event name with the bundle name
        $type = 'stripe_bundle'.'.'.$event->type;

        // Create the event to be dispatched
        $stripeEvent = new StripeEvent($event);

        /**
         * The event after processing
         * @var StripeEvent
         */
        $processedEvent = $eventDispatcher->dispatch($stripeEvent, $type);

        return new Response($processedEvent->getMessage(), $processedEvent->getResponseStatus());
    }
}