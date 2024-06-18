<?php

namespace Drupal\mongodb_migrator\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Drupal\mongodb_migrator\Service\MongoDBService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MigrationPreImportSubscriber  event subscriber.
 */
class MigrationPreImportSubscriber implements EventSubscriberInterface {


  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The MongoDB service.
   *
   * @var \Drupal\mongodb_migrator\Service\MongoDBService
   */
  protected $mongoService;

  /**
   * Constructs a new CustomMigrationSubscriber.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\mongodb_migrator\Service\MongoDBService $mongo_service
   *   The MongoDB service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MongoDBService $mongo_service) {
    $this->configFactory = $config_factory;
    $this->mongoService = $mongo_service;
  }

  /**
   * {@inheritdoc}
   */
  public function onPreImport(MigrateImportEvent $event) {
    $migration = $event->getMigration();
    // Don't do anything if migration is not the one you want to alter.
    if ($migration->getPluginId() !== 'mongodb_to_custom_entity') {
      return;
    }
    $process = $migration->getProcess();
    // Load the field mappings from configuration.
    $config = $this->configFactory->get('mapping.settings');
    $field_mappings = $config->get('field_mappings') ?? [];

    // Initialize an empty array to store the values not present.
    $notPresentInSecondArray = [];

    foreach ($field_mappings as $key => $value) {
      if (!array_key_exists($value, $process)) {
        $notPresentInSecondArray[$key] = $value;
      }
    }
    foreach ($notPresentInSecondArray as $key => $value) {
      $resultKeys = $this->findKeysBySourceValue($process, $key);
      // Replace the keys in the process array.
      foreach ($resultKeys as $resultKey) {
        $process[$value] = $process[$resultKey];
        unset($process[$resultKey]);
      }
    }
    $migration->setProcess($process);
  }

  /**
   * {@inheritdoc}
   */
  public function findKeysBySourceValue($array, $sourceValue) {
    $keys = [];
    foreach ($array as $key => $values) {
      foreach ($values as $value) {
        if (isset($value['source'])) {
          // Ensure $sources is always an array.
          $sources = (array) $value['source'];
          if (in_array($sourceValue, $sources)) {
            $keys[] = $key;
            // Break to avoid adding the same key multiple times.
            break;
          }
        }
      }
    }
    return $keys;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [
      MigrateEvents::PRE_IMPORT => 'onPreImport',
    ];
    return $events;
  }

}
