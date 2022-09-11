<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Nimzero\StripeBundle\Event\StripeEvent;

class <?= $class_name; ?> implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
<?php foreach ($events as $index => $event): ?>
      '<?= $event ?>' => [
        ['<?= $methods[$index]; ?>', 0]
      ],
<?php endforeach ?>
    ];
  }

<?php foreach ($methods as $method) : ?>
  public function <?= $method ?>(StripeEvent $event): void
  {
    // ...
  }

<?php endforeach ?>
}