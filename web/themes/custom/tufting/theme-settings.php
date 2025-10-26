<?php

declare(strict_types=1);

/**
 * @file
 * Theme settings form for Tufting Time theme.
 */

use Drupal\Core\Form\FormState;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function tufting_form_system_theme_settings_alter(array &$form, FormState $form_state): void {

  $form['tufting'] = [
    '#type' => 'details',
    '#title' => t('Tufting Time'),
    '#open' => TRUE,
  ];

  $form['tufting']['sales_telephone'] = [
    '#type' => 'textfield',
    '#title' => t('Sales Telephone Number'),
    '#default_value' => theme_get_setting('sales_telephone'),
  ];

  $form['tufting']['sales_email'] = [
    '#type' => 'email',
    '#title' => t('Sales Email'),
    '#default_value' => theme_get_setting('sales_email'),
  ];

  $form['tufting']['sales_whatsapp'] = [
    '#type' => 'textfield',
    '#title' => t('Sales WhatsApp URL'),
    '#default_value' => theme_get_setting('sales_whatsapp'),
  ];

  $form['tufting']['main_showroom'] = [
    '#type' => 'textfield',
    '#title' => t('Main Showroom Name'),
    '#default_value' => theme_get_setting('main_showroom'),
  ];

  $form['tufting']['main_showroom_address'] = [
    '#type' => 'textfield',
    '#title' => t('Main Showroom Address'),
    '#default_value' => theme_get_setting('main_showroom_address'),
  ];



}
