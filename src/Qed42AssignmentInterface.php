<?php

declare(strict_types=1);

namespace Drupal\qed42_assignment;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a qed42 assignment entity type.
 */
interface Qed42AssignmentInterface extends ContentEntityInterface, EntityChangedInterface {

}
