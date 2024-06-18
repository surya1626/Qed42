<?php

namespace Drupal\mongodb_migrator\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;

/**
 * Source plugin for MongoDB.
 *
 * @MigrateSource(
 *   id = "mongodb_to_custom_entity"
 * )
 */
class MongodbSource extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    $mongodb = \Drupal::service('mongodb_migrator.mongo_service');
    return new \ArrayIterator($mongodb->getDataInArray());
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'title' => $this->t('Title'),
      // Define other fields.
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return (string) $this->configuration['database'];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      '_id' => [
        'type' => 'string',
        'alias' => 'm',
      ],
    ];
  }

}
