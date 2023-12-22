<?php

namespace Drupal\mbgna_settings\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure MBGNA Settings settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mbgna_settings_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mbgna_settings.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mbgna_settings.settings');

    $roles = [];

    foreach(\Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple() as $role) {
      $roles[$role->id()] = $role->label();
    }

    $form['membership_user_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Membership roles'),
      '#default_value' => $config->get('membership_user_roles') ? $config->get('membership_user_roles') : '',
      '#description' => $this->t('Check all roles that designate memberships.'),
      '#options' => $roles,
    ];

    $form['privileged_user_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Privileged roles'),
      '#default_value' => $config->get('privileged_user_roles') ? $config->get('privileged_user_roles') : '',
      '#description' => $this->t('Check all roles that designate privileged administrators.'),
      '#options' => $roles,
    ];

    $form['staff_user_role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Staff role'),
      '#default_value' => $config->get('staff_user_role') ? $config->get('staff_user_role') : '',
      '#description' => $this->t('Check the role that designate staff.'),
      '#options' => $roles,
    ];

    $form['intern_user_role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Intern role'),
      '#default_value' => $config->get('intern_user_role') ? $config->get('intern_user_role') : '',
      '#description' => $this->t('Check the role that designate interns.'),
      '#options' => $roles,
    ];

    $form['author_user_role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Author role'),
      '#default_value' => $config->get('author_user_role') ? $config->get('author_user_role') : '',
      '#description' => $this->t('Check the role that designate authors.'),
      '#options' => $roles,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // if ($form_state->getValue('example') != 'example') {
    //   $form_state->setErrorByName('example', $this->t('The value is not correct.'));
    // }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mbgna_settings.settings')->set('membership_user_roles', $form_state->getValue('membership_user_roles'))->save();
    $this->config('mbgna_settings.settings')->set('privileged_user_roles', $form_state->getValue('privileged_user_roles'))->save();
    $this->config('mbgna_settings.settings')->set('staff_user_role', $form_state->getValue('staff_user_role'))->save();
    $this->config('mbgna_settings.settings')->set('intern_user_role', $form_state->getValue('intern_user_role'))->save();
    $this->config('mbgna_settings.settings')->set('author_user_role', $form_state->getValue('author_user_role'))->save();
    parent::submitForm($form, $form_state);
  }

}
