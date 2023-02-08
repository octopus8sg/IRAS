<?php

use CRM_Irasdonation_ExtensionUtil as E;

use \Firebase\JWT\JWT;

class CRM_Irasdonation_Utils
{
    public const SAVE_LOG = 'save_log';
    public const SEND_CONTACT = 'send_contact';
    public const SEND_CONTRIBUTION = 'send_contribution';
    public const USER_TYPES = 'user_types';
    public const REFRESH_TOKEN = 'refresh_token';
    public const REDIRECT_URI = 'redirect_uri';
    public const CLIENT_SECRET = 'client_secret';
    public const CLIENT_ID = 'client_id';
    public const SETTINGS_NAME = "Dmszoho Settings";
    public const SETTINGS_SLUG = 'dmszoho_settings';
    public const ZOHO_CONTACT_CUSTOM_GROUP = 'Zoho contact fields';
    public const ZOHO_CONTRIBUTION_CUSTOM_GROUP = 'Zoho contribution fields';
    public const ZOHO_RECIEPT_ID = 'Zoho Reciept ID';
    public const SEND_CONTRIBUTION_TO_ZOHO = 'Send Contribution to Zoho';
    public const ZOHO_CONTACT_ID = 'Zoho Contact ID';
    public const ZOHO_CONTACT_PERSON_ID = 'Zoho Contact Person ID';
    public const SEND_CONTACT_TO_ZOHO = 'Send Contact to Zoho';
//'Zoho Reciept ID', 'Send Contribution to Zoho'

    /**
     * @param $input
     * @param $preffix_log
     */
    public static function writeLog($input, $preffix_log = "Dmszoho Log")
    {
        try {
            if (self::getSaveLog()) {
                if (is_object($input)) {
                    $masquerade_input = (array)$input;
                } else {
                    $masquerade_input = $input;
                }
                if (is_array($masquerade_input)) {
                    $fields_to_hide = ['Signature'];
                    foreach ($fields_to_hide as $field_to_hide) {
                        unset($masquerade_input[$field_to_hide]);
                    }
                    Civi::log()->debug($preffix_log . "\n" . print_r($masquerade_input, TRUE));
                    return;
                }

                Civi::log()->debug($preffix_log . "\n" . $masquerade_input);
                return;
            }
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Dmszoho Configuration Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getSaveLog(): bool
    {
        $result = false;
        try {
            $result_ = self::getDnszohoSettings(self::SAVE_LOG);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getSendContact(): bool
    {
        $result = false;
        try {
            $result_ = self::getDnszohoSettings(self::SEND_CONTACT);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Send Contact Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getSendContribution(): bool
    {
        $result = false;
        try {
            $result_ = self::getDnszohoSettings(self::SEND_CONTRIBUTION);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Send Contribution Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }


    /**
     * @return string
     */

    public static function getRefreshToken(): string
    {
        $result = "";
        try {
            $result = strval(self::getDnszohoSettings(self::REFRESH_TOKEN));
//            self::writeLog($result, 'getValidateUEN');
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    public static function getClientID(): string
    {
        $result = "";
        try {
            $result = strval(self::getDnszohoSettings(self::CLIENT_ID));
//            self::writeLog($result, 'getValidateUEN');
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    public static function getClientSecret(): string
    {
        $result = "";
        try {
            $result = strval(self::getDnszohoSettings(self::CLIENT_SECRET));
//            self::writeLog($result, 'getValidateUEN');
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    public static function getRedirectURI(): string
    {
        $result = "";
        try {
            $result = strval(self::getDnszohoSettings(self::REDIRECT_URI));
//            self::writeLog($result, 'getValidateUEN');
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    public static function getAccessTokenURL(): string
    {
        $refresh_token = self::getRefreshToken();
        $client_id = self::getClientID();
        $client_secret = self::getClientSecret();
        $redirect_uri = self::getRedirectURI();
        if ($refresh_token == "") return "";
        if ($client_id == "") return "";
        if ($client_secret == "") return "";
        if ($redirect_uri == "") return "";
        $result = "https://accounts.zoho.com/oauth/v2/token?refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret&redirect_uri=$redirect_uri&grant_type=refresh_token";
        try {
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return mixed
     */
    public static function getAccessToken()
    {
        $url = self::getAccessTokenURL();
        $client = new GuzzleHttp\Client();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';

        try {
            $response = $client->request('POST', $url, [
                'user_agent' => $user_agent,
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/*+json',
                    'X-VPS-Timeout' => '45',
                    'X-VPS-VIT-Integration-Product' => 'CiviCRM',
                    'X-VPS-Request-ID' => strval(rand(1, 1000000000)),
                ],
            ]);
            $decoded = json_decode($response->getBody(), true);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            CRM_Core_Error::statusBounce('Dnszoho Error: Request error ', null, $e->getMessage());
            throw new CRM_Core_Exception('Dnszoho Error: Request error: ' . $e->getMessage());
        } catch (Exception $e) {
            CRM_Core_Error::statusBounce('Dnszoho Error: Another error: ', null, $e->getMessage());
            throw new CRM_Core_Exception('Dnszoho Error: Another error: ' . $e->getMessage());
        }
        return $decoded['access_token'];
    }

    /**
     * @return mixed
     */
    public static function createNewContact(\CRM_Contact_DAO_Contact $dnscontact)
    {

        $contact_id = intval($dnscontact->id);
        $label_zoho_contact_id = self::ZOHO_CONTACT_ID;
        $custom_field_zoho_contact_id = 'custom_' . self::get_custom_field_id($label_zoho_contact_id);
        $label_zoho_send_contact_id = self::SEND_CONTACT_TO_ZOHO;
        $custom_field_zoho_send_contact = 'custom_' . self::get_custom_field_id($label_zoho_send_contact_id);
        $getparams['entityID'] = $contact_id;
        $getparams[$custom_field_zoho_contact_id] = 1;
        $getparams[$custom_field_zoho_send_contact] = 1;
//        self::writeLog($getparams, 'getparams');
        $contact_custom_fields = CRM_Core_BAO_CustomValueTable::getValues($getparams);
        $has_zoho_contact = false;
        if (isset($contact_custom_fields[$custom_field_zoho_contact_id])) {
            if ($contact_custom_fields[$custom_field_zoho_contact_id]) {
                $has_zoho_contact = true;
                self::writeLog(strval($has_zoho_contact), 'has_zoho_contact');
            }
        }
        $custom_val_zoho_contact_id = $contact_custom_fields[$custom_field_zoho_contact_id];
        $custom_val_zoho_send_contact = (bool)$contact_custom_fields[$custom_field_zoho_send_contact];
        if ($has_zoho_contact) {
            return;
        }
        if (!$custom_val_zoho_send_contact) {
            return;
        }
        $setparams['entityID'] = $contact_id;
        $email = self::getPrimaryEmail($contact_id);
        $phone = self::getPrimaryPhone($contact_id);
        $nric = strval($dnscontact->external_identifier);
        $contact_type = $dnscontact->contact_type;
        $contact_sub_type = $dnscontact->contact_sub_type;
        if ($contact_sub_type) {
            $s_contact_sub_type = CRM_Utils_Array::implodePadded(CRM_Utils_Array::explodePadded($contact_sub_type), ' ');
            $contact_type = $contact_type . ' ' . trim($s_contact_sub_type);
        }


        self::writeLog($email, 'email');
        self::writeLog($phone, 'phone');
        self::writeLog($phone, 'phone');
        self::writeLog($nric, 'nric');
        self::writeLog($contact_type, 'contact_sub_type');


        $access_token = self::getAccessToken();
////        print($access_token);
        $client = new GuzzleHttp\Client();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';
        $url = "https://books.zoho.com/api/v3/contacts";
////        $url = "https://uza.uz";
        $payload = [];
        $random_string = CRM_Utils_String::createRandom(8, CRM_Utils_String::ALPHANUMERIC);
        $first_name = $last_name = $phone = $email = $contact_type = "";
//        $contact_type = $dnscontact['contact_type'];
        $contact_person = [];

//        if (array_key_exists('contact_sub_type', $dnscontact)) {
//            if ($dnscontact['contact_sub_type']) {
//                $contact_sub_type = CRM_Utils_Array::implodePadded($dnscontact['contact_sub_type'], ' ');
//                $contact_type = $dnscontact['contact_type'] . ' ' . trim($contact_sub_type);
//            }
//        }
////        $payload["customer_sub_type"] = $contact_type;
//        if (array_key_exists('first_name', $dnscontact)) {
//            if ($dnscontact['first_name']) {
//                $first_name = $dnscontact['first_name'];
//            }
//            $contact_person["first_name"] = $first_name;
//        }
//        if (array_key_exists('last_name', $dnscontact)) {
//            if ($dnscontact['last_name']) {
//                $last_name = $dnscontact['last_name'];
//            }
//            $contact_person["last_name"] = $last_name;
//        }
//        $payload['contact_name'] = $first_name . ' ' . $last_name . "_" . $random_string;
//        if (array_key_exists('email', $dnscontact)) {
//            if ($dnscontact['email']) {
//                if (is_array($dnscontact['email']))
//                    $email_array = array_shift($dnscontact['email']);
//                if (array_key_exists('email', $email_array)) {
//                    $email = $email_array['email'];
//                }
//            }
//            $contact_person["email"] = $email;
//        }
//        if (array_key_exists('phone', $dnscontact)) {
//            if ($dnscontact['phone']) {
//                if (is_array($dnscontact['phone']))
//                    $phone_array = array_shift($dnscontact['phone']);
//                if (array_key_exists('phone', $phone_array)) {
//                    $phone = $phone_array['phone'];
//                }
//            }
//            $contact_person["phone"] = $phone;
//        }
//        $payload["contact_persons"] = [$contact_person];
////        self::writeLog($dnscontact, 'dnscontact');
//        self::writeLog($payload, 'payload');
//        $jpayload = json_encode($payload);
//        self::writeLog($jpayload, 'jpayload');
//        try {
////            print($url);
//            $response = $client->request('POST', $url,
//                [
//                    'body' => $jpayload,
//
//                    'user_agent' => $user_agent,
//                    'headers' => [
//                        'Accept' => '*/*',
//                        'Content-Type' => 'application/json',
//                        'X-VPS-Timeout' => '45',
//                        'X-VPS-VIT-Integration-Product' => 'CiviCRM',
//                        'X-VPS-Request-ID' => strval(rand(1, 1000000000)),
//                        'Authorization' => "Zoho-oauthtoken " . $access_token
//                    ],
//                ]
//            );
//
//            $decoded = json_decode($response->getBody(), true);
//            if (array_key_exists('contact', $decoded)) {
//        $setparams[$custom_field_zoho_contact_id] = 323321123;
//        CRM_Core_BAO_CustomValueTable::setValues($setparams);

//                $dnscontact['external_identifier'] = $decoded['contact']['contact_id'];
//            }
//        } catch (GuzzleHttp\Exception\GuzzleException $e) {
//            try {
//                CRM_Core_Error::statusBounce('Dnszoho Error: Request error ', null, $e->getMessage());
//            } catch (Exception $ex) {
//                throw new CRM_Core_Exception('Dnszoho Error: Request error: ' . $e->getMessage());
//            }
//        } catch (Exception $e) {
//            try {
//                CRM_Core_Error::statusBounce('Dnszoho Error: Request error ', null, $e->getMessage());
//            } catch (Exception $ex) {
//                throw new CRM_Core_Exception('Dnszoho Error: Request error: ' . $e->getMessage());
//            }
//        }
//        return $decoded;

    }

    public static function createNewContribution(&$dnscontribution)
    {
//        $access_token = self::getAccessToken();
////        print($access_token);
//        $client = new GuzzleHttp\Client();
//        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';
//        $url = "https://books.zoho.com/api/v3/contacts";
////        $url = "https://uza.uz";
//        $payload = [];
//        $random_string = CRM_Utils_String::createRandom(8, CRM_Utils_String::ALPHANUMERIC);
//        $first_name = $last_name = $phone = $email = $contact_type = "";
//        $contact_type = $dnscontact['contact_type'];
//        $contact_person = [];
//        if (array_key_exists('contact_sub_type', $dnscontact)) {
//            if ($dnscontact['contact_sub_type']) {
//                $contact_sub_type = CRM_Utils_Array::implodePadded($dnscontact['contact_sub_type'], ' ');
//                $contact_type = $dnscontact['contact_type'] . ' ' . trim($contact_sub_type);
//            }
//        }
////        $payload["customer_sub_type"] = $contact_type;
//        if (array_key_exists('first_name', $dnscontact)) {
//            if ($dnscontact['first_name']) {
//                $first_name = $dnscontact['first_name'];
//            }
//            $contact_person["first_name"] = $first_name;
//        }
//        if (array_key_exists('last_name', $dnscontact)) {
//            if ($dnscontact['last_name']) {
//                $last_name = $dnscontact['last_name'];
//            }
//            $contact_person["last_name"] = $last_name;
//        }
//        $payload['contact_name'] = $first_name . ' ' . $last_name . "_" . $random_string;
//        if (array_key_exists('email', $dnscontact)) {
//            if ($dnscontact['email']) {
//                if (is_array($dnscontact['email']))
//                    $email_array = array_shift($dnscontact['email']);
//                if (array_key_exists('email', $email_array)) {
//                    $email = $email_array['email'];
//                }
//            }
//            $contact_person["email"] = $email;
//        }
//        if (array_key_exists('phone', $dnscontact)) {
//            if ($dnscontact['phone']) {
//                if (is_array($dnscontact['phone']))
//                    $phone_array = array_shift($dnscontact['phone']);
//                if (array_key_exists('phone', $phone_array)) {
//                    $phone = $phone_array['phone'];
//                }
//            }
//            $contact_person["phone"] = $phone;
//        }
//        $payload["contact_persons"] = [$contact_person];
////        self::writeLog($dnscontact, 'dnscontact');
//        self::writeLog($payload, 'payload');
//        $jpayload = json_encode($payload);
//        self::writeLog($jpayload, 'jpayload');
//        try {
////            print($url);
//            $response = $client->request('POST', $url,
//                [
//                    'body' => $jpayload,
//
//                    'user_agent' => $user_agent,
//                    'headers' => [
//                        'Accept' => '*/*',
//                        'Content-Type' => 'application/json',
//                        'X-VPS-Timeout' => '45',
//                        'X-VPS-VIT-Integration-Product' => 'CiviCRM',
//                        'X-VPS-Request-ID' => strval(rand(1, 1000000000)),
//                        'Authorization' => "Zoho-oauthtoken " . $access_token
//                    ],
//                ]
//            );
//
//            $decoded = json_decode($response->getBody(), true);
//            if (array_key_exists('contact', $decoded)) {
//                $dnscontact['external_identifier'] = $decoded['contact']['contact_id'];
//            }
//        } catch (GuzzleHttp\Exception\GuzzleException $e) {
//            try {
//                CRM_Core_Error::statusBounce('Dnszoho Error: Request error ', null, $e->getMessage());
//            } catch (Exception $ex) {
//                throw new CRM_Core_Exception('Dnszoho Error: Request error: ' . $e->getMessage());
//            }
//        } catch (Exception $e) {
//            try {
//                CRM_Core_Error::statusBounce('Dnszoho Error: Request error ', null, $e->getMessage());
//            } catch (Exception $ex) {
//                throw new CRM_Core_Exception('Dnszoho Error: Request error: ' . $e->getMessage());
//            }
//        }
//        return $decoded;
        $custom = $dnscontribution['custom'];
    }

    /**
     * @param string $error_message
     * @param string $error_title
     */
    public static function showErrorMessage(string $error_message, string $error_title): void
    {
        $session = CRM_Core_Session::singleton();
        $userContext = $session->readUserContext();
        CRM_Core_Session::setStatus($error_message, $error_title, 'error');
        CRM_Utils_System::redirect($userContext);
    }

    /**
     * @param $form
     * @param string $element_name
     */
    public static function removeRules(&$form, string $element_name): void
    {
        try {
            $element = $form->getElement($element_name);
            if ($element) {
                $form->removeElement($element_name, true);
                $form->addElement($element);
            }
        } catch (\Exception $e) {
            self::writeLog($e->getMessage(), 'removeRules');
        }
    }

    /**
     * @return mixed
     */
    protected static function getDnszohoSettings($setting = null)
    {
        $simple_settings = CRM_Core_BAO_Setting::getItem(self::SETTINGS_NAME, self::SETTINGS_SLUG);
        if ($setting === null) {
            if (is_array($simple_settings)) {
                return $simple_settings;
            }
            $simple_settings = [];
            return $simple_settings;
        }
        if ($setting) {
            $return_setting = CRM_utils_array::value($setting, $simple_settings);
            if (!$return_setting) {
                return false;
            }
            return $return_setting;
        }
    }

    /**
     * Check if a custom field exists given only the `label`, but we want to
     * check `name` first, then fall back to `label`.
     * Being a little bit paranoid but it's not clear if it's possible that a
     * really old install might have had the `name` generated differently than
     * the way core currently does it, since we never used to set the `name`
     * ourselves, so that's why we fall back to `label`.
     *
     * @param int $custom_group_id
     * @param string $field_label
     * @return bool
     */
    static function _custom_field_exists($custom_group_id, $field_label)
    {
        $params = array(
            'custom_group_id' => $custom_group_id,
            'name' => CRM_Utils_String::munge($field_label, '_', 64),
            'label' => $field_label,
            'options' => array('or' => array(array('name', 'label'))),
            'version' => 3,
        );
        $result = civicrm_api('custom_field', 'get', $params);
        return ($result['count'] != 0);
    }

    /**
     * @param $field_label
     * @return bool|int
     */
    static function get_custom_field_id($field_label)
    {
        $params = array(
//            'custom_group_id' => $custom_group_id,
            'name' => CRM_Utils_String::munge($field_label, '_', 64),
            'label' => $field_label,
            'options' => array('or' => array(array('name', 'label'))),
            'version' => 3,
        );
        $result = civicrm_api('custom_field', 'get', $params);
        if ($result['count'] != 0) {
            foreach ($result['values'] as $id => $detail) {
                $custom_field_id = $id;
//                $custom_group_id = $detail['custom_group_id'];
            }
            return intval($custom_field_id);
        }
        return ($result['count'] != 0);
    }

    /**
     * @param $title
     * @param $extends
     * @return int|string
     */
    public static function get_custom_group_id($title, $extends)
    {
        $custom_group_id = 0;
        $params = array(
            'title' => $title,
            'version' => 3,
        );

        require_once 'api/api.php';
        $result = civicrm_api('custom_group', 'get', $params);

        if ($result['count'] == 0) {
            $group = array(
                'title' => $title,
                'extends' => $extends,
                'collapse_display' => 0,
                'style' => 'Inline',
                'is_active' => 1,
                'version' => 3
            );
            $result = civicrm_api('custom_group', 'create', $group);
        }
        foreach ($result['values'] as $id => $detail) {
            $custom_group_id = $id;
        }
        return intval($custom_group_id);
    }

    public static function getPrimaryEmail($contactID)
    {
        // fetch the primary email
        $query = "
   SELECT civicrm_email.email as email
     FROM civicrm_contact
LEFT JOIN civicrm_email    ON ( civicrm_contact.id = civicrm_email.contact_id )
    WHERE civicrm_email.is_primary = 1
      AND civicrm_contact.id = %1";
        $p = [1 => [$contactID, 'Integer']];
        $dao = CRM_Core_DAO::executeQuery($query, $p);

        $email = NULL;
        if ($dao->fetch()) {
            $email = $dao->email;
        }
        return $email;
    }

    public static function getPrimaryPhone($contactID)
    {
        // fetch the primary phone
        $query = "
   SELECT civicrm_phone.phone as phone
     FROM civicrm_contact
LEFT JOIN civicrm_phone   ON ( civicrm_contact.id = civicrm_phone.contact_id )
    WHERE civicrm_phone.is_primary = 1
      AND civicrm_contact.id = %1";
        $p = [1 => [$contactID, 'Integer']];
        $dao = CRM_Core_DAO::executeQuery($query, $p);

        $phone = NULL;
        if ($dao->fetch()) {
            $phone = $dao->phone;
        }
        return $phone;
    }

}