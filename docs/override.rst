========
Override
========

To override the defaults route of the bundle remove the import you made on `setup`_ step 1.
Then add the following to your ``config/routes.yaml` and modify the path as usual::

    // config/routes.yaml
    nimzero.stripe_bundle.stripe.endpoint:
        path: <your path>
        controller: Nimzero\StripeBundle\Controller\StripeWebhookEndpointController::StripeWebhookEndpoint
        methods: POST

.. _`Setup`: setup.rst