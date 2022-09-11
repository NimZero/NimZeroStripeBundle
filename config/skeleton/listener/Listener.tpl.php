<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Nimzero\StripeBundle\Event\StripeEvent;

class <?= "$class_name\n"; ?>
{
<?php foreach ($methods as $method) : ?>
  public function <?= $method ?>(StripeEvent $event): void
  {
      // ...
  }

<?php endforeach ?>
}