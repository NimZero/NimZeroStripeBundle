=====
Usage
=====

The bundle allows you to create `Symfony event listener or subscriber`_ for every Stripe `events`_ by simply prefixing the event type with ``nimzero.stripe_bundle.``

To do so the bundle provide a new event type ``StripeEvent``

Through this object you can set the event processing as failed and provide a message to return.

Exemple with the customer.subscription.created event to get update when a subscription is created

.. code-block:: php

    // src/EventSubscriber/ExceptionSubscriber.php
    namespace App\EventSubscriber;

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use Symfony\Component\HttpKernel\KernelEvents;

    class ExceptionSubscriber implements EventSubscriberInterface
    {
        public static function getSubscribedEvents()
        {
            // return the subscribed events, their methods and priorities
            return [
                'nimzero.stripe_bundle.customer.subscription.created' => [
                    ['onSubscriptionCreated', 0]
                ],
            ];
        }

        public function onSubscriptionCreated(StripeEvent $event)
        {
            // ...
        }
    }


.. _`Symfony event listener or subscriber`: https://symfony.com/doc/current/event_dispatcher.html
.. _`events`: https://stripe.com/docs/api/events/types