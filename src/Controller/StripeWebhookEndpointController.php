<?php

/*
 * This file is part of the Stripe Bundle.
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

/**
 * This controller provides a default webhook endpoint to handle Stripe events
 * 
 * @since 1.0.0
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
class StripeWebhookEndpointController extends AbstractController
{
    /**
     * Stripe Webhook Endpoint
     * 
     * This route serves as the endpoint for the Stripe webhook.
     * Upon receiving a request the potential event will be reconstructed
     * and validated using the configured webhook secret.
     *
     * if the request isn't a Stripe event or couldn't be validated a HTTP 400 BAD REQUEST is returned.
     */
    public function StripeWebhookEndpoint(Request $request, EventDispatcherInterface $eventDispatcher): Response
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
         * @var string
         * */ 
        $secret = $this->getParameter('nimzero.stripe_bundle.stripe.webhook_secret');

        /** 
         * The configured tolerance
         * @var int
         */
        $tolerance = $this->getParameter('nimzero.stripe_bundle.stripe.tolerance');

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
        $type = 'nimzero.stripe_bundle'.'.'.$event->type;

        // Create the event to be dispatched
        $stripeEvent = new StripeEvent($event);

        /**
         * The event after processing
         * @var StripeEvent
         */
        $return = $eventDispatcher->dispatch($stripeEvent, $type);

        if ($return->isFailed()) {
            // The event processing has been marked as failed durring processing, respond with HTTP 500 error and given message
            return new Response($return->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // The event has been processed respond with HTTP 200 and given message
        return new Response($return->getMessage(), Response::HTTP_OK);
    }
}