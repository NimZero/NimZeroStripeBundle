=====
Usage
=====

Services
~~~~~~~~

The bundle provide a Symfony service ``Nimzero\StripeBundle\Serivce\Stripe`` that can be autowired.
This service provide helpers:

- getClient a method wich give you access to a ``Stripe/StripeClient`` configured with your API secret key.
- isLive tells you wether your API keys are live or test

Events
~~~~~~

The bundle allows you to create `Symfony event listener or subscriber`_ for every Stripe `events`_ by simply prefixing the event type with ``nimzero.stripe_bundle.``

To do so the bundle provide a new event type ``StripeEvent`` and a integration into the `symfony/maker-bundle`

Through this object you can set the event processing as failed and provide a message to return.

Exemple with the customer.subscription events to get update when a subscription is created

.. code-block:: bash

    $ php bin/console make:nzstripe:subscriber subscription customer.subscription.created customer.subscription.updated customer.subscription.deleted

The above command will generate the symfony event subscriber class ``SubscriptionEventSubscriber`` that subscribes to:
 - nimzero.stripe_bundle.customer.subscription.created
 - nimzero.stripe_bundle.customer.subscription.updated
 - nimzero.stripe_bundle.customer.subscription.deleted

If you prefer using event listener a maker command is also available: ``make:nzstripe:listener`` it uses the same format as the subscriber command

| Alternatively if you already have your webhook configured you can use the ``nzstripe:subscriber:from_webhook`` command to generate a event subscriber for all it's events.
| Be carefull the command generate a subscriber for all the events not one for each.

.. _`Symfony event listener or subscriber`: https://symfony.com/doc/current/event_dispatcher.html
.. _`events`: https://stripe.com/docs/api/events/types