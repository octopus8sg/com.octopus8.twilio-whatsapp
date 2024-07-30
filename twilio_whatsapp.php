<?php

require_once 'twilio_whatsapp.civix.php';

use CRM_TwilioWhatsapp_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function twilio_whatsapp_civicrm_config(&$config): void {
  _twilio_whatsapp_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function twilio_whatsapp_civicrm_install(): void {
  _twilio_whatsapp_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function twilio_whatsapp_civicrm_enable(): void {
  _twilio_whatsapp_civix_civicrm_enable();
  
}


function twilio_whatsapp_civicrm_navigationMenu(&$menu) {
  _twilio_whatsapp_civix_insert_navigation_menu($menu, '', array(
    'label' => ts('Broadcast Whatsapp Message'),
    'name' => 'TwilioBroadcast',
    'url' => 'civicrm/whatsapp-broadcast',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
}