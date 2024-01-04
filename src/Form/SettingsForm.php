<?php

namespace Drupal\mbgna_settings\Form;

use Drupal\commerce_payment\Entity\PaymentGateway;
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

    $form['description'] = [
      '#type' => 'markup',
      '#markup' => '<p>These settings supply information to custom MBGNA modules and themes for proper functioning of the website.</p>',
    ];

    $form['membership_user_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Membership roles'),
      '#default_value' => $config->get('membership_user_roles') ? $config->get('membership_user_roles') : '',
      '#description' => $this->t('Check all roles that designate memberships.'),
      '#options' => $roles,
      '#required' => TRUE,
    ];

    $form['privileged_user_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Privileged roles'),
      '#default_value' => $config->get('privileged_user_roles') ? $config->get('privileged_user_roles') : '',
      '#description' => $this->t('Check all roles that designate privileged administrators.'),
      '#options' => $roles,
      '#required' => TRUE,
    ];

    $form['staff_user_role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Staff role'),
      '#default_value' => $config->get('staff_user_role') ? $config->get('staff_user_role') : '',
      '#description' => $this->t('Check the role that designates staff.'),
      '#options' => $roles,
      '#required' => TRUE,
    ];

    $form['intern_user_role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Intern role'),
      '#default_value' => $config->get('intern_user_role') ? $config->get('intern_user_role') : '',
      '#description' => $this->t('Check the role that designates interns.'),
      '#options' => $roles,
      '#required' => TRUE,
    ];

    $form['author_user_role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Author role'),
      '#default_value' => $config->get('author_user_role') ? $config->get('author_user_role') : '',
      '#description' => $this->t('Check the role that designates authors.'),
      '#options' => $roles,
      '#required' => TRUE,
    ];

    // Generate options for payment gateways.
    $payment_gateways = PaymentGateway::loadMultiple();
    $payment_gateways_options = [];
    foreach ($payment_gateways as $payment_gateway) {
      $payment_gateways_options[$payment_gateway->id()] = $payment_gateway->label();
    }
    $form['promo_calc_pay_method'] = [
      '#type' => 'radios',
      '#title' => $this->t('Payment method for calcuating promotions'),
      '#default_value' => $config->get('promo_calc_pay_method') ? $config->get('promo_calc_pay_method') : '',
      '#description' => $this->t('Check the payment method for which to calculate per-product promotions. If not sure, use the most popular payment method.'),
      '#options' => $payment_gateways_options,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
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
    $this->config('mbgna_settings.settings')->set('promo_calc_pay_method', $form_state->getValue('promo_calc_pay_method'))->save();
    parent::submitForm($form, $form_state);
  }

}
