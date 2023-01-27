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

use Nimzero\StripeBundle\Interfaces\StripeSubscribedInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;

class HasActiveSubscriptionVoter implements CacheableVoterInterface
{
    const NAME = 'has_active_subscription';

    public function supportsAttribute(string $attribute): bool
    {
        return $attribute === self::NAME;
    }

    public function supportsType(string $subjectType): bool
    {
        return is_subclass_of($subjectType, StripeSubscribedInterface::class);
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        return 'active' === $subject->getStripeSubscriptionStatus();
    }
}