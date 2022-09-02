=====
Setup
=====

Step 1: Import the bundle route
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the following lines in your ``config/routes.yaml`` file::

    // config/routes.yaml
    stripe_bundle:
        resource: '@NimzeroStripeBundle/config/routes.yaml'

Step 2: Stripe API
~~~~~~~~~~~~~~~~~~

Get your Stripe api keys from the `dashboard`_::

    // .env
    ###> nimzero/stripe-bundle ###
    STRIPE_API_SECRET_KEY='<secret key>'
    STRIPE_API_PUBLIC_KEY='<public key>'
    ###< nimzero/stripe-bundle ###

Step 3: Stripe webhook
~~~~~~~~~~~~~~~~~~~~~~

Create a `webhook`_ to receive updates from Stripe::

    // .env
    ###> nimzero/stripe-bundle ###
    STRIPE_WEBHOOK_SECRET='<webhook secret>'
    ###< nimzero/stripe-bundle ###


Next step:
==========
| `Use`_ the bundle.
| `Override`_ the route

.. _`dashboard`: https://dashboard.stripe.com/apikeys
.. _`webhook`: https://stripe.com/docs/webhooks
.. _`Use`: usage.rst
.. _`Override`: override.rst