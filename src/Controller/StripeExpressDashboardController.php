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

use Nimzero\StripeBundle\Interfaces\StripeConnectedAccountInterface;
use Nimzero\StripeBundle\Security\IsExpressAccountVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Nimzero\StripeBundle\Service\StripeHelper;

class StripeExpressDashboardController extends AbstractController
{
    public function stripeExpressDashboardController(string $mode, StripeHelper $stripeHelper): Response
    {
        /** @var ?StripeConnectedAccountInterface */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(IsExpressAccountVoter::NAME, $user, 'User is not an Stripe Express account');

        $loginLink = $stripeHelper->getClient(live: 'live' === $mode)->accounts->createLoginLink($user->getStripeAccountId());

        return $this->redirect($loginLink->url);
    }
}