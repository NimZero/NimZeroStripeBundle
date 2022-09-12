<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Tests;

use Nimzero\StripeBundle\Event\StripeEvent;
use PHPUnit\Framework\TestCase;

class StripeEventTest extends TestCase
{
  public function testEvent(): void
  {
    $mock = new \Stripe\Event('evt_456');
    $mock->data = ['object' => new \Stripe\StripeObject('obj_123')];
    $event = new StripeEvent($mock);

    $this->assertSame($mock, $event->getEvent(), 'The StripeEvent object is not the same');
    $this->assertSame($mock->data->object, $event->getObject(), 'The StripeEvent->data->object is not the same');

    $this->assertNull($event->getMessage(), 'Message not correctly initialized');
    $this->assertFalse($event->isFailed(), 'Failed not correctly initialized');

    $event->failed('fail');

    $this->assertTrue($event->isFailed(), 'Failed value not updated');
    $this->assertSame('fail', $event->getMessage(), 'Message not correctly set');

    $event->setMessage('override');
    
    $this->assertSame('override', $event->getMessage(), 'Message not correctly set');
  }
}