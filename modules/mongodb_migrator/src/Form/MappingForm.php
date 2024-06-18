<?php

namespace Drupal\mongodb_migrator\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mongodb_migrator\Service\MongoDBService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the mapping settings form.
 */
class MappingForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mapping.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mangodb_mapping_form';
  }

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;
  /**
   * The MongoDB service.
   *
   * @var \Drupal\mongodb_migrator\Service\MongoDBService
   */
  protected $mongoService;

  /**
   * Constructs a Mapping config object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\mongodb_migrator\Service\MongoDBService $mongo_service
   *   The MangoDB Service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityFieldManagerInterface $entity_field_manager, MongoDBService $mongo_service) {
    parent::__construct($config_factory);
    $this->entityFieldManager = $entity_field_manager;
    $this->mongoService = $mongo_service;
  }

  /**
   * Creates an instance.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_field.manager'),
      $container->get('mongodb_migrator.mongo_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get fields of the custom entity.
    $fields = $this->entityFieldManager->getFieldDefinitions('qed42_assignment', 'qed42_assignment');

    $core_fields = ['id', 'uuid', 'status', 'created', 'changed'];
    // Builds an array of fields as options.
    foreach ($fields as $field_id => $field) {
      // Avoid showing core fields, as they shall not be modified.
      if (in_array($field_id, $core_fields)) {
        continue;
      }

      $options[$field_id] = ($field->getLabel() . ' (' . $field_id . ')');
    }
    // Adding dynamic field mapping settings.
    $mappings = $this->config('mapping.settings')->get('field_mappings') ?? [];

    foreach ($this->mongoService->getCollectionFieldKeys() as $mapping) {
      $form['field_mappings' . $mapping] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Field Mappings for the MongoDB Fields => @mapping', ['@mapping' => $mapping]),
      ];
      $form['field_mappings' . $mapping][$mapping . '-source_field'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Source Field'),
        '#default_value' => $mapping,
        '#disabled' => TRUE,
      ];
      $form['field_mappings' . $mapping][$mapping . '-target_field'] = [
        '#type' => 'select',
        '#title' => $this->t('Drupal Fields'),
        '#options' => $options,
        '#default_value' => $mappings[$mapping] ?? '',
        '#required' => TRUE,
      ];
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Saving the configuration.
    $mongoDB = $this->mongoService->getCollectionFieldKeys();
    $config = $this->config('mapping.settings');
    // Save field mappings.
    $mappings = [];
    foreach ($mongoDB as $mapping) {
      $mappings[$mapping] = $form_state->getValue($mapping . '-target_field');
    }
    // Save field mappings.
    $config->set('field_mappings', $mappings);
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
