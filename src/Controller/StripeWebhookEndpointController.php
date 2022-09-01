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

class StripeWebhookEndpointController extends AbstractController
{
    /**
     * Stripe Webhook Endpoint
     * 
     * This route serves as the endpoint for the Stripe webhook.
     * Upon receiving a request the the potential event will be reconstructed
     * and validated using the configured webhook secret.
     *
     * if the request isn't a Stripe event or couldn't be validated a HTTP 400 BAD REQUEST is returned.
     */
    public function StripeWebhookEndpoint(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        /**
         * Get the signature from the headers
         * @var string
         */
        $signature_header = $request->headers->get('STRIPE_SIGNATURE');

        // Get the payload of the request
        $payload = $request->getContent();

        /**
         * Get configured values
         * @var string
         * */ 
        $secret = $this->getParameter('nimzero.stripe_bundle.stripe.webhook_secret');
        /** @var int */
        $tolerance = $this->getParameter('nimzero.stripe_bundle.stripe.tolerance');

        try {
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

        $type = 'nimzero.stripe_bundle'.'.'.$event->type;
        $stripeEvent = new StripeEvent($event);
        $return = $eventDispatcher->dispatch($stripeEvent, $type);

        /** @var \Nimzero\StripeBundle\Event\StripeEvent $return */
        if ($return->isFailed()) {
            return new Response($return->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response($return->getMessage(), Response::HTTP_OK);
    }
}