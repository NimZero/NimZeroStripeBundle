============
Installation
============

Make sure Composer is installed globally, as explained in the
`installation chapter`_ of the Composer documentation.

----------------------------------

Applications that use Symfony Flex
----------------------------------

Step 1: Configure Composer
~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the bundle flex recipe repository to your ``composer.json``::

    // composer.json
    "extra": {
        "symfony": {
            "endpoint": [
                "https://api.github.com/repos/NimZero/FlexRecipes/contents/index.json",
                "flex://defaults"
            ]
        }
    }

Step 2: Download the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Open a command console, enter your project directory and execute:

.. code-block:: bash

    $ composer require nimzero/stripe-bundle

Applications that don't use Symfony Flex
----------------------------------------

Step 1: Download the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Open a command console, enter your project directory and execute:

.. code-block:: bash

    $ composer require nimzero/stripe-bundle

Step 2: Copy the configuration
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Copy the configuration file::

    vendor/nimzero/stripe-bundle/config/packages/stripe_bundle.yaml > config/packages/stripe_bundle.yaml

Step 3: Enable the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Then, enable the bundle by adding it to the list of registered bundles
in the ``config/bundles.php`` file of your project::

    // config/bundles.php
    return [
        // ...
        Nimzero\StripeBundle\NimzeroStripeBundle::class => ['all' => true],
    ];


----------------------------------


Next step:
==========
`Setup`_ the bundle.

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md
.. _`Setup`: setup.rst