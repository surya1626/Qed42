<?php

namespace Drupal\mongodb_migrator\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use MongoDB\Client;

/**
 * Mongodb Service.
 */
class MongoDBService {
  /**
   * The client.
   *
   * @var mixed
   */
  protected $client;
  /**
   * The database.
   *
   * @var mixed
   */
  protected $database;

  /**
   * The collection.
   *
   * @var mixed
   */
  protected $collection;

  public function __construct(ConfigFactoryInterface $config_factory) {
    $config = $config_factory->get('mongodb.settings');
    $uri = $config->get('mongodb_url');
    $dbName = $config->get('mangodb_database');
    $collection = $config->get('mangodb_collection');
    $this->client = new Client($uri);
    $this->collection = $this->client->{$dbName}->{$collection};
  }

  /**
   * Get the data in array from MongoDB.
   */
  public function getDataInArray() {
    $json = $this->collection->find();
    $array = json_decode(json_encode($json->toArray(), TRUE), TRUE);
    return $array;
  }

  /**
   * Get the Field from MongoDB.
   */
  public function getCollectionFieldKeys() {
    // Use the aggregate method to get field keys.
    $pipeline = [
        ['$limit' => 1],
    ];

    $document = $this->collection->aggregate($pipeline)->toArray();
    if (empty($document)) {
      return [];
    }

    // Get the keys from the first document.
    return array_keys((array) $document[0]);
  }

}
