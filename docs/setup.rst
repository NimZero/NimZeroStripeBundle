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

| Optionaly, you can define the Stripe api version to use in ``config/packages/nimzero_stripe.yaml``
| Leaving the value empty or null will use the version deined in your Stripe account::

    // config/packages/nimzero_stripe.yaml
        stripe:
            # The Stripe api version to use, set to null to use your Stripe account version
            api_version: <version in YYYY-MM-DD format>

Step 3: Stripe webhook
~~~~~~~~~~~~~~~~~~~~~~

Create a `webhook`_ to receive updates from Stripe and add it's signing secret to your .env::

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