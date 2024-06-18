<?php

declare(strict_types=1);

namespace Drupal\qed42_assignment;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the qed42 assignment entity type.
 *
 * phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
 *
 * @see https://www.drupal.org/project/coder/issues/3185082
 */
final class Qed42AssignmentAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account): AccessResult {
    if ($account->hasPermission($this->entityType->getAdminPermission())) {
      return AccessResult::allowed()->cachePerPermissions();
    }

    return match($operation) {
      'view' => AccessResult::allowedIfHasPermission($account, 'view qed42_assignment'),
      'update' => AccessResult::allowedIfHasPermission($account, 'edit qed42_assignment'),
      'delete' => AccessResult::allowedIfHasPermission($account, 'delete qed42_assignment'),
      'delete revision' => AccessResult::allowedIfHasPermission($account, 'delete qed42_assignment revision'),
      'view all revisions', 'view revision' => AccessResult::allowedIfHasPermissions($account, ['view qed42_assignment revision', 'view qed42_assignment']),
      'revert' => AccessResult::allowedIfHasPermissions($account, ['revert qed42_assignment revision', 'edit qed42_assignment']),
      default => AccessResult::neutral(),
    };
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL): AccessResult {
    return AccessResult::allowedIfHasPermissions($account, ['create qed42_assignment', 'administer qed42_assignment'], 'OR');
  }

}
