<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Command;

use Nimzero\StripeBundle\Service\Stripe;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SubscriberFromWebhookCommand extends Command
{
  protected static $defaultName = 'nzstripe:subscriber:fromWebhook';

  private Stripe $stripe;
  private UrlGeneratorInterface $urlGenerator;

  public function __construct(Stripe $stripe, UrlGeneratorInterface $urlGenerator)
  {
    parent::__construct();
    $this->stripe = $stripe;
    $this->urlGenerator = $urlGenerator;
  }

  protected function configure()
  {
    $this->addArgument('webhook', InputArgument::REQUIRED, 'The id of the webhook to create a subscriber for');
  }

  protected function interact(InputInterface $input, OutputInterface $output)
  {
    if (!$input->getArgument('webhook')) {
      $io = new SymfonyStyle($input, $output);

      $wh = $io->ask('Enter the webhook id', null, [Validator::class, 'notBlank']);
      $input->setArgument('webhook', $wh);
    }
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $stripeClient = $this->stripe->getClient();

    try {
      $webhook = $stripeClient->webhookEndpoints->retrieve($input->getArgument('webhook'));
    } catch (\Stripe\Exception\ApiErrorException $e) {
      $io->error('Stripe API error: ' . $e->getMessage());
      return Command::FAILURE;
    }

    $events = $webhook->enabled_events;

    $command = $this->getApplication()->find('make:nzstripe:subscriber');

    $arguments = [
        'name'    => 'Webhook',
        'event'  => $events,
    ];

    $makerInput = new ArrayInput($arguments);
    $returnCode = $command->run($makerInput, $output);

    $id = $webhook->id;
    $events = join(', ', $events);
    $secret = $webhook->secret;

    if ($returnCode === Command::SUCCESS) {
      $io->success([
        "Generated subscriber for webhook: $id",
        "Webhook secret for config: $secret",
        "Events: $events",
      ]);
  
      return Command::SUCCESS;
    }
    
    return Command::FAILURE;
  }
}
