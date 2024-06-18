<?php

declare(strict_types=1);

namespace Drupal\qed42_assignment\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\qed42_assignment\Qed42AssignmentInterface;

/**
 * Defines the qed42 assignment entity class.
 *
 * @ContentEntityType(
 *   id = "qed42_assignment",
 *   label = @Translation("Qed42 Assignment"),
 *   label_collection = @Translation("Qed42 Assignments"),
 *   label_singular = @Translation("qed42 assignment"),
 *   label_plural = @Translation("qed42 assignments"),
 *   label_count = @PluralTranslation(
 *     singular = "@count qed42 assignments",
 *     plural = "@count qed42 assignments",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\qed42_assignment\Qed42AssignmentListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\qed42_assignment\Qed42AssignmentAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\qed42_assignment\Form\Qed42AssignmentForm",
 *       "edit" = "Drupal\qed42_assignment\Form\Qed42AssignmentForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *       "revision-delete" = \Drupal\Core\Entity\Form\RevisionDeleteForm::class,
 *       "revision-revert" = \Drupal\Core\Entity\Form\RevisionRevertForm::class,
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *       "revision" = \Drupal\Core\Entity\Routing\RevisionHtmlRouteProvider::class,
 *     },
 *   },
 *   base_table = "qed42_assignment",
 *   revision_table = "qed42_assignment_revision",
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer qed42_assignment",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "collection" = "/admin/content/qed42-assignment",
 *     "add-form" = "/qed42-assignment/add",
 *     "canonical" = "/qed42-assignment/{qed42_assignment}",
 *     "edit-form" = "/qed42-assignment/{qed42_assignment}/edit",
 *     "delete-form" = "/qed42-assignment/{qed42_assignment}/delete",
 *     "delete-multiple-form" = "/admin/content/qed42-assignment/delete-multiple",
 *     "revision" = "/qed42-assignment/{qed42_assignment}/revision/{qed42_assignment_revision}/view",
 *     "revision-delete-form" = "/qed42-assignment/{qed42_assignment}/revision/{qed42_assignment_revision}/delete",
 *     "revision-revert-form" = "/qed42-assignment/{qed42_assignment}/revision/{qed42_assignment_revision}/revert",
 *     "version-history" = "/qed42-assignment/{qed42_assignment}/revisions",
 *   },
 *   field_ui_base_route = "entity.qed42_assignment.settings",
 * )
 */
final class Qed42Assignment extends RevisionableContentEntityBase implements Qed42AssignmentInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setRevisionable(TRUE)
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the qed42 assignment was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the qed42 assignment was last edited.'));

    return $fields;
  }

}
