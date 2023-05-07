<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Exception;

class StripeModeDisabledException extends \Exception
{
    public function __construct(string $mode, int $code = 0, \Throwable $previous = null)
    {
        if ('test' === $mode) {
            $message = "'test' mode is disabled!";
        } else {
            $message = "'live' mode is disabled!";
        }

        parent::__construct($message, $code, $previous);
    }
}