<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Security;

use Nimzero\StripeBundle\Interfaces\StripeConnectedAccountInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;

class IsExpressAccountVoter implements CacheableVoterInterface
{
    const NAME = 'is_express_account';

    public function supportsAttribute(string $attribute): bool
    {
        return $attribute === self::NAME;
    }

    public function supportsType(string $subjectType): bool
    {
        return is_subclass_of($subjectType, StripeConnectedAccountInterface::class);
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        return StripeConnectedAccountInterface::EXPRESS === $subject->getStripeAccountType() && null !== $subject->getStripeAccountId();
    }
}