<?php

namespace Drupal\mongodb_migrator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the mongodb settings form.
 */
class MongoDBConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mongodb.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mangodb_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load teh config form settings.
    $config = $this->config('mongodb.settings');

    $form['mongodb_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mongo DB URL'),
      '#default_value' => $config->get('mongodb_url') ?? '',
    ];
    $form['mangodb_database'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mongo DB Name'),
      '#default_value' => $config->get('mangodb_database') ?? '',
    ];
    $form['mangodb_collection'] = [
      '#type' => 'textfield',
      '#title' => $this->t('MangoDB Collection'),
      '#default_value' => $config->get('mangodb_collection') ?? '',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config('mongodb.settings')
    // Set the submitted configuration setting.
      ->set('mongodb_url', $form_state->getValue('mongodb_url'))
    // You can set multiple configurations at once by making
    // multiple calls to set().
      ->set('mangodb_database', $form_state->getValue('mangodb_database'))
      ->set('mangodb_collection', $form_state->getValue('mangodb_collection'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
