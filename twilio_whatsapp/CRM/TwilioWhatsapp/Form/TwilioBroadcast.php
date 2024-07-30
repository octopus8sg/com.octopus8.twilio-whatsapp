<?php

use CRM_TwilioWhatsapp_ExtensionUtil as E;
require_once __DIR__ . "/../../../vendor/autoload.php";

use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_TwilioWhatsapp_Form_TwilioBroadcast extends CRM_Core_Form {

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {

    // Add the textarea for the message
    $this->add('textarea', 'message', E::ts('Message'), ['rows' => 5, 'cols' => 40, 'placeholder' => 'Enter your message here...'], TRUE);


    $this->assign('helpText', 'You can use the following placeholders: {name}, {phone}, {email}');

    // Fetch the groups for the dropdown
    $groups = $this->getGroups();
    $this->add('select', 'group_id', E::ts('Select Group'), $groups, TRUE);

    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    // Export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * Fetch groups from CiviCRM
   *
   * @return array
   */
  protected function getGroups(): array {
    $groups = [];
    $result = civicrm_api3('Group', 'get', [
      'sequential' => 1,
      'is_active' => 1,
      'return' => ['id', 'title'],
    ]);
    if (!empty($result['values'])) {
      foreach ($result['values'] as $group) {
        $groups[$group['id']] = $group['title'];
      }
    }
    return $groups;
  }

  /**
   * Send broadcast message via Twilio
   *
   * @param string $message
   * @param array $contacts
   * @throws \Twilio\Exceptions\ConfigurationException
   */
  protected function sendBroadcastMessage(string $message, array $contacts): void {
    $sid = '';
    $token = '';

    try {
      $client = new Client($sid, $token);
      
      foreach ($contacts as $contact) {
        // Ensure the phone number has +65 in front
        $phoneNumber = $contact['phone'];
        if (strpos($phoneNumber, '+65') !== 0) {
          $phoneNumber = '+65' . $phoneNumber;
        }

        // Assuming contact['phone'] contains the phone number
        $personalizedMessage = str_replace(
          ['{name}', '{phone}', '{email}', '{address}'], 
          [$contact['display_name'], $phoneNumber, $contact['email'] ?? '', $contact['address'] ?? ''], 
          $message
        );

         $client->messages->create(
          'whatsapp:' . $phoneNumber, // to
          [
            'from' => 'whatsapp:+6582413196', 
            'body' => $personalizedMessage,
          ]
        );
      }
    } catch (ConfigurationException $e) {
      CRM_Core_Error::debug_log_message($e->getMessage());
    } catch (Exception $e) {
      CRM_Core_Error::debug_log_message($e->getMessage());
    }
  }

  /**
   * Fetch contacts from the selected group
   *
   * @param int $groupId
   * @return array
   */
  protected function getGroupContacts(int $groupId): array {
    $contacts = [];
    $result = civicrm_api3('Contact', 'get', [
      'group' => $groupId,
      'sequential' => 1,
      'return' => ['id', 'display_name', 'phone', 'email'],
    ]);
    if (!empty($result['values'])) {
      foreach ($result['values'] as $contact) {
        // Assuming the phone field is present and valid
        if (!empty($contact['phone'])) {
          $contacts[] = $contact;
        }
      }
    }

    Civi::log()->info($contacts); 
    return $contacts;
  }

  public function postProcess(): void {
    $values = $this->exportValues();

    $message = $values['message'];
    $groupId = (int) $values['group_id'];

    // Fetch contacts from the selected group
    $contacts = $this->getGroupContacts($groupId);

    // Send the broadcast message
    $this->sendBroadcastMessage($message, $contacts);

    // Log the result
    CRM_Core_Error::debug_var('Broadcast Message Sent', [
      'message' => $message,
      'group_id' => $groupId,
      'contacts' => $contacts,
    ]);

    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames(): array {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
