<?php

use CRM_Irasdonation_ExtensionUtil as E;
use Symfony\Component\EventDispatcher\GenericEvent;

class CRM_Irasdonation_Utils
{
    public const SAVE_LOG = [
        'slug' => 'save_log',
        'name' => 'Save Log',
        'description' => "Write debugging output to CiviCRM log file"];
    public const SETTINGS_NAME = "IRAS Settings";
    public const SETTINGS_SLUG = 'iras_settings';
    public const IRASUSER = 'iras_user';
    public const CODE = 'iras_code';
    public const CODETIME = 'iras_codetime';
    public const STATE = 'iras_state';
    public const LOGINTIME = 'iras_login_time';
    public const ACCESSTOKEN = 'iras_access_token';
    public const PREFIX = [
        'slug' => 'prefix',
        'name' => 'Receipt No Prefix',
        'description' => "Max. 3 characters. Receipt numbers are formed by appending the CiviCRM Contribution ID to this prefix..\n"
            . "Receipt numbers must be unique within your organization. If you also issue tax receipts using another system,\n"
            . " you can use the prefix to ensure uniqueness\n"
            . "(e.g. enter 'OCT' here so all receipts issued through CiviCRM are OCT000001, OCT0000002, etc.)"];
    public const CLIENT_ID = [
        'slug' => 'client_id',
        'name' => 'Client ID',
        'description' => "String containing the client ID of the application invoking IRAS API.\n"
            . "This value will be provided to the application vendor by IRAS.\n"
            . "E.g. a1234b5c-1234-abcd-efgh-a1234b5cdef"];
    public const CLIENT_SECRET = [
        'slug' => 'client_secret',
        'name' => 'Client Secret',
        'description' => "String containing the client secret of the application invoking IRAS API.\n"
            . "This value will be provided to the application vendor by IRAS.\n"
            . "E.g. a12345bC67e8fG9a12345bC67e8fG9a12345bC67e8fG9"];
    public const RECIEPT_ID = [
        'slug' => 'reciept_id',
        'name' => 'Receipt ID Custom Field',
        'description' => "Custom field to get Reciept ID from (leave blank to use defaults)"];
    public const RECIEPT_DATE = [
        'slug' => 'reciept_date',
        'name' => 'Receipt Date Custom Field',
        'description' => "Custom field to get Reciept Date from (leave blank to use defaults)"];
    public const ORGANIZATION_TYPE = [
        'slug' => 'organization_type',
        'name' => 'Organization type',
        'description' => "Must be one of the following: \n"
            . "UEN-BUSINESS\n"
            . "UEN-LOCAL CO\n"
            . "UEN-OTHERS\n"
            . "ASGD\n"
            . "ITR"];
    public const ORGANISATION_ID = [
        'slug' => 'organisation_id',
        'name' => 'Organization ID/UEN',
        'description' => "Must be one of the following:\n"
            . "UEN-BUSINESS = NNNNNNNNC\n"
            . "UEN-LOCAL CO = YYYYNNNNNC\n"
            . "UEN-OTHERS = TYYPQNNNNC or SYYPQNNNNC\n"
            . "ASGD No. = ANNNNNNNC\n"
            . "ITR No. = NNNNNNNNNC\n"
            . "Where: C = Check digit; N = Numeric; Y = Year"];
    public const ORGANISATION_NAME = [
        'slug' => 'organisation_name',
        'name' => 'Organization name',
        'description' => "Max. 60 characters"];
    public const AUTHORISED_PERSON_ID = [
        'slug' => 'authorised_person_id',
        'name' => 'Authorized User ID(SingpassID)',
        'description' => "The ID Number of person submitting the Donation Information.\n"
            . "Must be one of the following:\n"
            . "Valid NRIC with prefix S/T\n"
            . "Valid FIN with prefix F/G/M\n"
            . "Valid ASGD as ANNNNNNNC\n"
            . "Valid ITR as NNNNNNNNNC"];
    public const AUTHORISED_PERSON_NAME = [
        'slug' => 'authorised_person_name',
        'name' => 'Authorized user full name',
        'description' => "Max. 30 characters"];
    public const AUTHORISED_PERSON_DESIGNATION = ['slug' => 'authorised_person_designation',
        'name' => 'Authorized user designation',
        'description' => "Max. 30 characters"];
    public const AUTHORISED_PERSON_PHONE = [
        'slug' => 'authorised_person_phone',
        'name' => 'Authorized user phone number',
        'description' => "Min. 8 digits"];
    public const AUTHORISED_PERSON_EMAIL = [
        'slug' => 'authorised_person_email',
        'name' => 'Authorized user email',
        'description' => "Max. 50 characters"];
    public const IRAS_API_URL = [
        'slug' => 'iras_api_url',
        'name' => 'IRAS API URL',
        'description' => "For Sandbox Testing:\n"
            . "https://apisandbox.iras.gov.sg/iras/sb\n"
            . "For Production Usage:\n"
            . "https://apiservices.iras.gov.sg/iras/prod"];
    public const CALLBACK_URL = [
        'slug' => 'callback_url',
        'name' => 'Callback URL',
        'description' => "Callback URL to use Corppass via the registration form"];
    public const VALIDATE_ONLY = [
        'slug' => 'validate_only',
        'name' => 'Validate only',
        'description' => "If checked, the API will perform validation of the donation information without submission.\n"
            . "Otherwise, the API will perform validation of the donation information and submission to IRAS."];
    public const MIN_AMOUNT = ['slug' => 'min_amount',
        'name' => 'Minimum amount($)',
        'description' => "Minimum amount of Contribution to include to IRAS Donation Report"];
    public const TYPES = array(
        '5' => 'UEN-BUSINESS',
        '6' => 'UEN-LOCAL CO',
        'U' => 'UEN-OTHERS',
        'A' => 'ASGD',
        'I' => 'ITR'
    );

    public const ID_TYPES =
        [8 => "A",
            10 => "I",
            35 => "U"];

    public const IRAS_CONTRIBUTION_CUSTOM_GROUP = 'Iras Contribution Fields';
    public const IRAS_INDIVIDUAL_INDICATOR = "Individual Indicator";
    public const IRAS_INDIVIDUAL_INDICATOR_DEFAULT = "IND";
    public const IRAS_INDIVIDUAL_INDICATOR_OPTIONS = ["IND" => "Individual",
        "NON" => "Non-Individual"];
    public const IRAS_INDIVIDUAL_INDICATOR_HELP = "individualIndicator
Individual (IND) or Non-Individual Indicator (NON).
Mandatory, if ID Type=A(AGSD) or I=ITR
To pass either IND or NON";
    public const IRAS_TYPE_OF_DONATION = 'Type of donation';
    public const IRAS_TYPE_OF_DONATION_DEFAULT = "O";
    public const IRAS_TYPE_OF_DONATION_OPTIONS = ["O" => "Outright Cash",
        "S" => "Share/Unit Trust",
        "A" => "Artifact",
        "L" => "Land/Building",
        "P" => "Public Sculpture"];
    public const IRAS_TYPE_OF_DONATION_HELP = "Must be one of the following values:
O = Outright Cash
S = Share/Unit Trust
A = Artifact
L = Land/Building
P = Public Sculpture
If TypeofDonation=S, the Donor ID Type must be NRIC or FIN or ASGD or ITR. If ASGD or ITR, the individualIndicator must be = IND";
    public const IRAS_NAMING_DONATION = 'Naming Donation';
    public const IRAS_NAMING_DONATION_DEFAULT = "Z";
    public const IRAS_NAMING_DONATION_OPTIONS = ["Z" => "Non naming donation",
        "N" => "Name IPC",
        "F" => "Name Facility"
    ];
    public const IRAS_NAMING_DONATION_HELP = 'Must be one of the following values: Z = Non naming donation (by default)
N = Name IPC (where the IPC (e.g. foundation) is named after the donor or his immediate and living family member.
F = Name Facility (where a facility belonging to an IPC has been named after the donor.)';

    public static function callbackUrl()
    {
        $session = CRM_Core_Session::singleton();
        $code = CRM_Utils_Request::retrieveValue('code', 'String', null);
        $state = CRM_Utils_Request::retrieveValue('state', 'String', null);
        $redirectUrl = $session->popUserContext();
        if (!$redirectUrl) {
            $redirectUrl = self::getSettings($state);
        }
        if (!$redirectUrl) {
            print_r(['code' => $code, 'state' => $state, 'redirectUrl' => $redirectUrl]);
        }
        $url = self::getIrasTokenURL();
        $callbackUrl = self::getCallbackURL();
        $body = array(
            'code' => $code,
            'scope' => 'DonationSub',
            'callback_url' => $callbackUrl,
            'state' => $state,
        );
        try {
        $header = self::prepareHeader();
        } catch (\Exception $e) {
            self::writeLog($e->getMessage());
            throw new CRM_Core_Exception('No token in a JSON in Response error: ' . $e->getMessage());
        }
        try {
            $decoded = self::guzzlePost($url, $header, $body);
        } catch (\Exception $e) {
            self::writeLog($e->getMessage());
            throw new CRM_Core_Exception('No token in a JSON in Response error: ' . $e->getMessage());
        }
        try {
            $access_token = $decoded['data']['token'];
        } catch (\Exception $e) {
            self::writeLog($e->getMessage());
            throw new CRM_Core_Exception('No token in a JSON in Response error: ' . $e->getMessage());
        }

        $now = time();

        $session->set(self::ACCESSTOKEN, $access_token);
        self::setSettings(self::ACCESSTOKEN, $access_token);
//        self::writeLog($access_token, "access_token");
        $session->set(self::LOGINTIME, $now);
        self::setSettings(self::LOGINTIME, $now);
//        self::writeLog($redirectUrl, "redirect_url");
        CRM_Utils_System::redirect($redirectUrl);
    }


    /**
     * @param $input
     * @param $preffix_log
     */
    public static function writeLog($input, $preffix_log = "Iras Log")
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
            self::writeLog($exception->getMessage());
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
            $result_ = self::getSettings(self::SAVE_LOG['slug']);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            self::writeLog($exception->getMessage());
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getValidateOnly(): bool
    {
        $result = false;
        try {
            $result_ = self::getSettings(self::VALIDATE_ONLY['slug']);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            self::writeLog($exception->getMessage());
            $error_message = $exception->getMessage();
            $error_title = 'Validate Only Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getIrasLoginURL()
    {
        $result = false;
        try {
            $result = self::getSettings(self::IRAS_API_URL['slug']);
            $result = $result . "/Authentication/CorpPassAuth";
            return $result;
        } catch (\Exception $exception) {
            self::writeLog($exception->getMessage());
            $error_message = $exception->getMessage();
            $error_title = 'Iras Login Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getIrasReportURL()
    {
        $result = false;
        try {
            $result = self::getSettings(self::IRAS_API_URL['slug']);
            $result = $result . "/DonationCP/submit";
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Iras Login Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getIrasTokenURL()
    {
        $result = false;
        try {
            $result = self::getSettings(self::IRAS_API_URL['slug']);
            $result = $result . "/Authentication/CorpPassToken";
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Iras Login Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getCallbackURL()
    {
        $result = false;
        try {
            $result = self::getSettings(self::CALLBACK_URL['slug']);
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Iras Login Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return string
     */
    public static function getClientID(): string
    {
        $result = "";
        try {
            $result = strval(self::getSettings(self::CLIENT_ID['slug']));
//            self::writeLog($result, 'getValidateUEN');
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return string
     */
    public static function getClientSecret(): string
    {
        $result = "";
        try {
            $result = strval(self::getSettings(self::CLIENT_SECRET['slug']));
//            self::writeLog($result, 'getValidateUEN');
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @param $url
     * @param $payload
     * @return \Psr\Http\Message\StreamInterface
     * @throws CRM_Core_Exception
     */
    public static function getLoginResponse($url)
    {
//        $clientID = self::getClientID();
//        self::writeLog($clientID, "clientID");
//        $clientSecret = self::getClientSecret();
//        self::writeLog($clientSecret, "clientSecret");
//        self::writeLog($url, "url");
        try {
            $header = self::prepareHeader();
        } catch (\Exception $exception) {
            self::writeLog($exception->getMessage());
            return null;
        }
        try {
            $decodedresponse = self::guzzleGet($url, $header);
        } catch (\Exception $exception) {
            self::writeLog($exception->getMessage());
            return null;
        }
        return $decodedresponse;
    }

//    /**
//     * @return mixed
//     */
//    public static function getAccessToken()
//    {
//        $url = self::getAccessTokenURL();
//        $client = new GuzzleHttp\Client();
//        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';
//
//        try {
//            $response = $client->request('POST', $url, [
//                'user_agent' => $user_agent,
//                'headers' => [
//                    'Accept' => 'text/plain',
//                    'Content-Type' => 'application/*+json',
//                    'X-VPS-Timeout' => '45',
//                    'X-VPS-VIT-Integration-Product' => 'CiviCRM',
//                    'X-VPS-Request-ID' => strval(rand(1, 1000000000)),
//                ],
//            ]);
//            $decoded = json_decode($response->getBody(), true);
//        } catch (GuzzleHttp\Exception\GuzzleException $e) {
//            CRM_Core_Error::statusBounce('Dnszoho Error: Request error ', null, $e->getMessage());
//            throw new CRM_Core_Exception('Dnszoho Error: Request error: ' . $e->getMessage());
//        } catch (Exception $e) {
//            CRM_Core_Error::statusBounce('Dnszoho Error: Another error: ', null, $e->getMessage());
//            throw new CRM_Core_Exception('Dnszoho Error: Another error: ' . $e->getMessage());
//        }
//        return $decoded['access_token'];
//    }


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
     * @param string $error_message
     * @param string $error_title
     */
    public static function showStatusMessage(string $message, string $title): void
    {
        $session = CRM_Core_Session::singleton();
        $userContext = $session->readUserContext();
        CRM_Core_Session::setStatus($message, $title, 'warning', array('expires' => 2000));
//        CRM_Utils_System::redirect($userContext);
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
    public static function getSettings($setting = null)
    {
        $settings = CRM_Core_BAO_Setting::getItem(self::SETTINGS_NAME, self::SETTINGS_SLUG);
        if ($setting === null) {
            if (is_array($settings)) {
                return $settings;
            }
            $settings = [];
            return $settings;
        }
        if ($setting) {
            $return_setting = CRM_utils_array::value($setting, $settings);
            if (!$return_setting) {
                return false;
            }
            return $return_setting;
        }
    }


    public static function setSettings($setting_name, $setting_value)
    {
        $settings = CRM_Core_BAO_Setting::getItem(self::SETTINGS_NAME, self::SETTINGS_SLUG);
        if (is_array($settings)) {
            $settings[$setting_name] = $setting_value;
        }
        $s = CRM_Core_BAO_Setting::setItem($settings, self::SETTINGS_NAME, self::SETTINGS_SLUG);

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

    public static function parsUENNumber($uen)
    {
        $idTypes = ["nric" => 1, "fin" => 2, "uenb" => 5, "uenl" => 6, "asgd" => 8, "itr" => 10, "ueno" => 35];
        if ($uen == null) return 0;
        switch ($uen) {
            case ($uen[0] == 'S' || $uen[0] == 'T') && is_numeric(substr($uen, 1, 7)):
                return $idTypes['nric'];
            case ($uen[0] == 'F' || $uen[0] == 'G' || $uen[0] == 'M') && is_numeric(substr($uen, 1, 7)):
                return $idTypes['fin'];
            case (strlen($uen) < 10 && is_numeric(substr($uen, 0, 8))):
                return $idTypes['uenb'];
            case (((int)substr($uen, 0, 4)) >= 1800 && ((int)substr($uen, 0, 4)) <= date("Y")) && is_numeric(substr($uen, 4, 5)):
                return $idTypes['uenl'];
            case ($uen[0] == 'A' && is_numeric(substr($uen, 1, 7))):
                return $idTypes['asgd'];
            case (is_numeric(substr($uen, 0, 9))):
                return $idTypes['itr'];
            case (($uen[0] == 'T' || $uen[0] == 'S' || $uen[0] == 'R') && is_numeric(substr($uen, 1, 2)) && !is_numeric(substr($uen, 3, 2)) && is_numeric(substr($uen, 5, 4))):
                return $idTypes['ueno'];
            default:
                return 0;
        }
    }

    public static function parsUENNumber_for_online($uen)
    {
        $idTypes = ["nric" => 1, "fin" => 2, "uenb" => 5, "uenl" => 6, "asgd" => 8, "itr" => 10, "ueno" => 35];
        if ($uen == null) return 0;
        switch ($uen) {
            case ($uen[0] == 'S' || $uen[0] == 'T') && is_numeric(substr($uen, 1, 7)):
                return $idTypes['nric'];
            case ($uen[0] == 'F' || $uen[0] == 'G' || $uen[0] == 'M') && is_numeric(substr($uen, 1, 7)):
                return $idTypes['fin'];
            case (strlen($uen) < 10 && is_numeric(substr($uen, 0, 8))):
                return $idTypes['uenb'];
            case (((int)substr($uen, 0, 4)) >= 1800 && ((int)substr($uen, 0, 4)) <= date("Y")) && is_numeric(substr($uen, 4, 5)):
                return $idTypes['uenl'];
            case ($uen[0] == 'A' && is_numeric(substr($uen, 1, 7))):
                return $idTypes['asgd'];
            case (is_numeric(substr($uen, 0, 9))):
                return $idTypes['itr'];
            case (($uen[0] == 'T' || $uen[0] == 'S' || $uen[0] == 'R') && is_numeric(substr($uen, 1, 2)) && !is_numeric(substr($uen, 3, 2)) && is_numeric(substr($uen, 5, 4))):
                return $idTypes['ueno'];
            default:
                return 0;
        }
    }

    public static function getContributionDateCustomFields()
    {
        $contributionDateCustomFields = [];
        $customFields = \Civi\Api4\CustomField::get(FALSE)
            ->addSelect('name', 'label')
            ->addJoin('CustomGroup AS custom_group', 'LEFT', ['custom_group_id', '=', 'custom_group.id'])
            ->addWhere('custom_group.extends', '=', 'Contribution')
            ->addWhere('data_type', '=', 'Date')
            ->execute();
        foreach ($customFields as $customField) {
            $contributionDateCustomFields[$customField['name']] = $customField['label'];
        }
        return $contributionDateCustomFields;
    }

    public static function getContributionStringCustomFields()
    {
        $contributionStringCustomFields = [];
        $customFields = \Civi\Api4\CustomField::get(FALSE)
            ->addSelect('name', 'label')
            ->addJoin('CustomGroup AS custom_group', 'INNER', ['custom_group_id', '=', 'custom_group.id'])
            ->addWhere('custom_group.extends', '=', 'Contribution')
            ->addWhere('data_type', '=', 'String')
            ->execute();
        foreach ($customFields as $customField) {
            $contributionStringCustomFields[$customField['name']] = $customField['label'];
        }
        return $contributionStringCustomFields;
    }

    /**
     * @param string $basis_year
     * @param $num_of_records
     * @param $total_donation_amount
     * @param $details
     * @return array
     */

    public static function prepareOnlineReportBody(string $basis_year, $num_of_records, $total_donation_amount, $details, $batch_indicator = "O"): array
    {
        $settings = CRM_Irasdonation_Utils::getSettings();
        $organisation_id_no = CRM_Utils_Array::value(self::ORGANISATION_ID['slug'], $settings);
        $organisation_id_type = CRM_Utils_Array::value(self::ORGANIZATION_TYPE['slug'], $settings);
        $organisation_name = CRM_Utils_Array::value(self::ORGANISATION_NAME['slug'], $settings);
        $authorised_person_id = CRM_Utils_Array::value(self::AUTHORISED_PERSON_ID['slug'], $settings);
        $authorised_person_name = CRM_Utils_Array::value(self::AUTHORISED_PERSON_NAME['slug'], $settings);
        $authorised_person_designation = CRM_Utils_Array::value(self::AUTHORISED_PERSON_DESIGNATION['slug'], $settings);
        $telephone = CRM_Utils_Array::value(self::AUTHORISED_PERSON_PHONE['slug'], $settings);
        $authorised_person_email = CRM_Utils_Array::value(self::AUTHORISED_PERSON_EMAIL['slug'], $settings);
        $validate_only = self::getValidateOnly();
//        $batch_indicator = "O";
        $body = array(
            'orgAndSubmissionInfo' => [
                'validateOnly' => $validate_only,
                'basisYear' => $basis_year,
                'organisationIDType' => $organisation_id_type,
                'organisationIDNo' => $organisation_id_no,
                'organisationName' => $organisation_name,
                'batchIndicator' => $batch_indicator,
                'authorisedPersonIDNo' => $authorised_person_id,
                'authorisedPersonName' => $authorised_person_name,
                'authorisedPersonDesignation' => $authorised_person_designation,
                'telephone' => $telephone,
                'authorisedPersonEmail' => $authorised_person_email,
                'numOfRecords' => $num_of_records,
                'totalDonationAmount' => $total_donation_amount
            ],
            "donationDonorDtl" => $details
        );
        return array($body,
            $validate_only,
            $basis_year,
            $organisation_id_type,
            $organisation_id_no,
            $organisation_name,
            $batch_indicator,
            $authorised_person_name,
            $authorised_person_designation,
            $telephone,
            $authorised_person_email,
            $num_of_records,
            $total_donation_amount);
    }

    /**
     * @param $client_id
     * @param $client_secret
     * @param string $access_token
     * @return array
     */
    public static function prepareHeader(string $access_token = null): array
    {
        $client_id = self::getClientID();
        $client_secret = self::getClientSecret();
        $header = [
            "Accept" => "application/json",
            "charset" => "utf-8",
            "Content-Type" => "application/json",
            "X-IBM-Client-Id" => $client_id,
            "X-IBM-Client-Secret" => $client_secret,
            'X-VPS-Timeout' => '45',
            'X-VPS-Timeout' => '45',
            'X-VPS-Request-ID' => strval(rand(1, 1000000000)),
        ];
        if ($access_token) {
            $header["access_token"] = $access_token;
        }
        return $header;
    }

    /**
     * @param $name
     * @return int|string|null
     */
    public static function getContributionStatusID($name)
    {
        return CRM_Utils_Array::key($name, \CRM_Contribute_PseudoConstant::contributionStatus(NULL, 'name'));
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $includePrevious
     * @return array|void
     */
    public static function prepareDonations($startDate, $endDate, $includePrevious)
    {
        $settings = self::getSettings();
        $min_amount = CRM_Utils_Array::value(CRM_Irasdonation_Utils::MIN_AMOUNT['slug'], $settings);
        $prefix = CRM_Utils_Array::value(CRM_Irasdonation_Utils::PREFIX['slug'], $settings);

        $completed = self::getContributionStatusID('Completed');
        $where = " contact.external_identifier IS NOT NULL AND contribution.contribution_status_id = $completed ";

        if ($includePrevious == 0) {
            $where .= " AND donation.id IS NULL";
        }

        if ($startDate != null) {
            $where .= " AND contribution.receive_date >= '$startDate'";
        }

        if ($endDate != null) {
            $where .= " AND contribution.receive_date <= '$endDate'";
        }

        if ($min_amount != null) {
            $where .= " AND contribution.total_amount >= $min_amount";
        }

        //generate header of report
        $sql = "SELECT
    contribution.id contribution_id, 
    contact.sort_name contact_name, 
    contact.external_identifier contact_external_identifier,
    address.supplemental_address_1 address_supplemental_address_1,
    address.street_address  address_street_address,
    address.postal_code  address_postal_code,
    CONCAT('$prefix', LPAD(RIGHT(contribution.id, 7), 7, 0)) receipt_no,
    contribution.receive_date date_of_donation,
    contribution.total_amount donation_amount
    FROM civicrm_contribution contribution 
         INNER JOIN civicrm_financial_type fintype ON fintype.id = contribution.financial_type_id and fintype.is_deductible = 1 
         INNER JOIN civicrm_contact contact ON contact.id = contribution.contact_id
         LEFT JOIN civicrm_address address ON address.contact_id = contact.id and address.is_primary = 1
         LEFT JOIN civicrm_o8_iras_donation donation ON contribution.id = donation.contribution_id        
    WHERE $where";
//        self::writeLog($sql, "sql");
        $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

        $totalRows = 100;
        $insert = '';
        $total = 0;
        $counter = 0;
        $generatedDate = date('Y-m-d H:i:s');

        //generate body of th report
        $online_donations = array();
        $offline_donations = array();
        $donations = array();
        while ($result->fetch()) {

            $contact_external_identifier = $result->contact_external_identifier;
            $idType = self::parsUENNumber($contact_external_identifier);
            self::writeLog($idType, 'idtype');
            if (!($idType > 0)) {
                $idType = 1;
            }
            if ($idType > 0) {
                $string_id_type = strval($idType);
                $ind = "IND";

                if ($idType > 6) {
                    $string_id_type = self::ID_TYPES[$idType];
//                    $ind = "NON";

                }
                if ($idType > 2) {
//                    $string_id_type = self::ID_TYPES[$idType];
                    $ind = "NON";

                }
                $contribution_id = $result->contribution_id;
                $individual_indicator = self::getIrasCustomValue(self::IRAS_INDIVIDUAL_INDICATOR, $contribution_id, self::IRAS_INDIVIDUAL_INDICATOR_DEFAULT);
                $type_of_donation = self::getIrasCustomValue(self::IRAS_TYPE_OF_DONATION, $contribution_id, self::IRAS_TYPE_OF_DONATION_DEFAULT);
                $naming_donation = self::getIrasCustomValue(self::IRAS_NAMING_DONATION, $contribution_id, self::IRAS_NAMING_DONATION_DEFAULT);
                $contact_name = str_replace(',', '', $result->contact_name);
                $address_line1 = $result->address_street_address;
                $address_line2 = $result->address_supplemental_address_1;
//                if(strlen($address_line1) == 0){
//                    $address_line1 = "not given";
//                }
//                if(strlen($address_line1) > 30){
//                    $address_line1 = substr($address_line2, 0, 30);
//                }
//                if(strlen($address_line2) == 0){
//                    $address_line2 = "not given";
//                }
//                if(strlen($address_line2) > 30){
//                    $address_line2 = substr($address_line2, 0, 30);
//                }
                $donation_amount = round($result->donation_amount);
                $date_of_donation = date("Ymd", strtotime($result->date_of_donation));
                $receipt_no = $result->receipt_no;
                $donation = array(
                    'contribution_id' => $contribution_id,
                    'record_id' => $counter,
                    'id_type' => $string_id_type,
                    'id_number' => $contact_external_identifier,
                    'individual_indicator' => $individual_indicator,
                    'contact_name' => $contact_name,
                    'address_line1' => $address_line1,
                    'address_line2' => $address_line2,
                    'postal_code' => $result->address_postal_code,
                    'donation_amount' => $donation_amount,
                    'date_of_donation' => $date_of_donation,
                    'receipt_num' => $receipt_no,
                    'type_of_donation' => $type_of_donation,
                    'naming_donation' => $naming_donation
                );
                if (strlen($donation['address_line1']) == 0 or strlen($donation['address_line1']) > 30) {
                    unset($donation['address_line1']);
                }
                if (strlen($donation['address_line2']) == 0 or strlen($donation['address_line2']) > 30) {
                    unset($donation['address_line2']);
                }
                if (strlen($donation['postal_code']) == 0 or strlen($donation['postal_code']) > 30) {
                    unset($donation['postal_code']);
                }
                $online_donation = array(
                    'recordID' => $counter + 1,
                    'idType' => $string_id_type,
                    'idNumber' => $contact_external_identifier,
                    'individualIndicator' => $individual_indicator,
                    'name' => $contact_name,
                    'addressLine1' => $address_line1,
                    'addressLine2' => $address_line2,
                    'postalCode' => $result->address_postal_code,
                    'donationAmount' => $donation_amount,
                    'dateOfDonation' => $date_of_donation,
                    'receiptNum' => $receipt_no,
                    'typeOfDonation' => $type_of_donation,
                    'namingDonation' => $naming_donation
                );
                if (strlen($online_donation['address_line1']) == 0 or strlen($online_donation['address_line1']) > 30) {
                    unset($online_donation['address_line1']);
                }
                if (strlen($online_donation['address_line2']) == 0 or strlen($online_donation['address_line2']) > 30) {
                    unset($online_donation['address_line2']);
                }
                if (strlen($online_donation['postal_code']) == 0 or strlen($online_donation['postal_code']) > 30) {
                    unset($online_donation['postal_code']);
                }
                $offline_donation = [1,
                    $idType,
                    $contact_external_identifier,
                    $contact_name,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $donation_amount,
                    $date_of_donation,
                    $receipt_no,
                    $type_of_donation,
                    $naming_donation];

                array_push($offline_donations, $offline_donation);
                array_push($online_donations, $online_donation);
                array_push($donations, $donation);
                $total += $donation_amount;
                $counter++;
            }
        }
        $totalRows = $counter;
        return array($totalRows, $total, $counter, $generatedDate, $donations, $online_donations, $offline_donations);
    }

    /**
     * @param string $url
     * @param array $header
     * @param array $body
     * @return array
     */
    public static function guzzlePost(string $url, array $header, array $body): array
    {
        try {
            $client = new GuzzleHttp\Client([
                'timeout' => 120, // Timeout in seconds
            ]);
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';
            $response = $client->post($url, [
                'user_agent' => $user_agent,
                'headers' => $header,
                'json' => $body
            ]);
//            self::writeLog($header, "guzzlePostheader");
//            self::writeLog(json_encode($body), "guzzlePostbody");
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            self::writeLog($e->getMessage(), 'Error: Request error ');
            CRM_Core_Error::statusBounce('Error: Request error ', null, $e->getMessage());
            throw new CRM_Core_Exception('Error: Request error: ' . $e->getMessage());
        } catch (Exception $e) {
            self::writeLog($e->getMessage(), 'Error: Another error: ');
            CRM_Core_Error::statusBounce('Error: Another error: ', null, $e->getMessage());
            throw new CRM_Core_Exception('Error: Another error: ' . $e->getMessage());
        }
        try {
            $responsebody = $response->getBody();
//            self::writeLog($responsebody, 'guzzlePostrespobody');
        } catch (Exception $e) {
            throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
        }

        try {
            $decoded = json_decode($responsebody, true);
//            self::writeLog($decoded, 'decodedguzzlePost');
        } catch (Exception $e) {
            throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
        }
        return $decoded;
    }

    /**
     * @param string $url
     * @param array $header
     * @param array $body
     * @return array
     */
    public static function guzzleGet(string $url, array $header): array
    {
        try {
            $client = new GuzzleHttp\Client();
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';
            $response = $client->get($url, [
                'user_agent' => $user_agent,
                'headers' => $header
            ]);
//            self::writeLog($header, "guzzleGetheader");
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            self::writeLog($e->getMessage(), 'Error: Request error ');
            CRM_Core_Error::statusBounce('Error: Request error ', null, $e->getMessage());
            throw new CRM_Core_Exception('Error: Request error: ' . $e->getMessage());
        } catch (Exception $e) {
            self::writeLog($e->getMessage(), 'Error: Another error: ');
            CRM_Core_Error::statusBounce('Error: Another error: ', null, $e->getMessage());
            throw new CRM_Core_Exception('Error: Another error: ' . $e->getMessage());
        }
        try {
            $responsebody = $response->getBody();
//            self::writeLog($responsebody, 'guzzlePostrespobody');
        } catch (Exception $e) {
            throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
        }

        try {
            $decoded = json_decode($responsebody, true);
//            self::writeLog($decoded, 'decodedguzzlePost');
        } catch (Exception $e) {
            throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
        }
        return $decoded;
    }

    public static function generateCsv($csvData)
    {
        $f = fopen('php://output', 'w');
        $f_name = "report_" . date('dmY_His') . ".csv";
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $f_name . '";');

        foreach ($csvData as $row) {
            fputcsv($f, $row, ",", '\'', "\\");
        }
        // fseek($f, 0);

        fclose($f);
        // $file = fpassthru($f);
        return $f_name;
    }

    /**
     * @param $report_year
     * @param $organisation_id
     * @param $total
     * @param $counter
     * @param $offline_donations
     * @return array
     */

    public static function prepareOfflineReport($report_year, $organisation_id, $total, $counter, $offline_donations)
    {
        $offline_report_csv = array();
        $dataHead = [0, 7, $report_year, 7, 0, $organisation_id, null, null, null, null, null, null, null, null];
        array_push($offline_report_csv, $dataHead);
        $offline_report_csv = array_merge($offline_report_csv, $offline_donations);

        $dataBottom = [2, $counter, $total, null, null, null, null, null, null, null, null, null, null, null];
        array_push($offline_report_csv, $dataBottom);
        return $offline_report_csv;
    }

    /**
     * @param int $is_api
     * @param int $validate_only
     * @param $basis_year
     * @param $organisation_id_type
     * @param $organisation_id_no
     * @param $organisation_name
     * @param $batch_indicator
     * @param $authorised_person_name
     * @param $authorised_person_designation
     * @param $telephone
     * @param $authorised_person_email
     * @param $num_of_records
     * @param $total_donation_amount
     * @param string $response_body
     * @param $response_code
     * @param $generatedDate
     * @param $donations
     */
    public static function saveDonationLogs(int $is_api,
                                            int $validate_only,
                                            $basis_year,
                                            $organisation_id_type,
                                            $organisation_id_no,
                                            $organisation_name,
                                            $batch_indicator,
                                            $authorised_person_name,
                                            $authorised_person_designation,
                                            $telephone,
                                            $authorised_person_email,
                                            $num_of_records,
                                            $total_donation_amount,
                                            string $response_body,
                                            $response_code,
                                            $generatedDate,
                                            $donations): void
    {
//        $response_body = CRM_Core_DAO::escapeStrings($response_body);
        $database = CRM_Core_DAO::executeQuery("INSERT IGNORE INTO civicrm_o8_iras_response_log (
                                          is_api,
                                          validate_only,
                                          basis_year,
                                          organisation_id_type,
                                          organisation_id_no,
                                          organisation_name,
                                          batch_indicator,
                                          authorised_person_name,
                                          authorised_person_designation,
                                          telephone,
                                          authorised_person_email,
                                          num_of_records,
                                          total_donation_amount,
                                          response_body, 
                                          response_code, 
                                          created_date) VALUES (
                                            %1, %2, %3, %4, %5, %6, %7, %8, %9, %10, %11, %12, %13, %14, %15, %16)",
            array(
                1 => array($is_api, 'Integer'),
                2 => array($validate_only, 'Integer'),
                3 => array($basis_year, 'String'),
                4 => array($organisation_id_type, 'String'),
                5 => array($organisation_id_no, 'String'),
                6 => array($organisation_name, 'String'),
                7 => array($batch_indicator, 'String'),
                8 => array($authorised_person_name, 'String'),
                9 => array($authorised_person_designation, 'String'),
                10 => array($telephone, 'String'),
                11 => array($authorised_person_email, 'String'),
                12 => array($num_of_records, 'Integer'),
                13 => array($total_donation_amount, 'Float'), // Assuming $total_donation_amount is a floating-point number
                14 => array($response_body, 'String'),
                15 => array($response_code, 'Integer'),
                16 => array($generatedDate, 'String'),
            )
        );
        $result = CRM_Core_DAO::executeQuery('SELECT LAST_INSERT_ID() id;', CRM_Core_DAO::$_nullArray);

        while ($result->fetch()) {
            $response_log_id = $result->id;
        }


        $log_donation = function ($donations, $response_log_id, $donkey) {
            $generatedDate = date('Y-m-d H:i:s');
            $sizeofchung = sizeof($donations);
            if ($sizeofchung == 0) {
                return;
            }
            $firstdonid = $donations[0]['contribution_id'];
            $lastdonid = $donations[$sizeofchung - 1]['contribution_id'];
//            self::writeLog($firstdonid, "first of chunk");
//            self::writeLog($lastdonid, "last of chunk");
//            self::writeLog($donkey, "key of chunk");
//            self::writeLog($sizeofchung, "in_event_sizeofchung");
            foreach ($donations as $key => $donation_) {
                $donation = $donations[$key];
                // Connect to the database
                $donation = $donations[$key];
                $contribution_id = $donation['contribution_id'];
//                self::writeLog('Step 1: ' . $firstdonid . "_" . $donkey . "_" . $key . "_" . $contribution_id, "in_event_contribution_id");

                $record_id = $donation['record_id'];
                $id_type = $donation['id_type'];
                $id_number = $donation['id_number'];
                $individual_indicator = $donation['individual_indicator'];
                $contact_name = $donation['contact_name'];
                $address_line1 = $donation['address_line1'];
                $address_line2 = $donation['address_line2'];
                $postal_code = $donation['postal_code'];
                $donation_amount = $donation['donation_amount'];
                $date_of_donation = $donation['date_of_donation'];
                $receipt_num = $donation['receipt_num'];
                $type_of_donation = $donation['type_of_donation'];
                $naming_donation = $donation['naming_donation'];


//                $insert_response = "INSERT IGNORE INTO civicrm_o8_iras_donation(
//                                     contribution_id,
//                                     created_date) VALUES ($contribution_id, '$generatedDate');";
                try {
                    $database = CRM_Core_DAO::executeQuery("INSERT IGNORE INTO civicrm_o8_iras_donation(
                                     contribution_id,
                                     created_date) VALUES (%1, %2)",
                        array(
                            1 => array($contribution_id, 'Integer'), // Assuming $contribution_id is an integer
                            2 => array($generatedDate, 'String'),     // Assuming $generatedDate is a string
                        )
                    );
//                    self::writeLog('Step 2: ' . $firstdonid . "_" . $donkey . "_" . $key . "_" . $contribution_id, "in_event_contribution_id");
                } catch (Exception $e) {
                    self::writeLog($e->getMessage(), "INSERT IGNORE INTO civicrm_o8_iras_donation");
                }

                $get_donation_id_sql = "SELECT id from civicrm_o8_iras_donation WHERE contribution_id = $contribution_id";
                try {
                    $result = CRM_Core_DAO::executeQuery($get_donation_id_sql, CRM_Core_DAO::$_nullArray);
//                    self::writeLog('Step 3: ' . $firstdonid . "_" . $donkey . "_" . $key . "_" . $contribution_id, "in_event_contribution_id");

                } catch (Exception $e) {
                    self::writeLog($e->getMessage());
                }
//                                self::writeLog($result, "get_donation_id");
////                CRM_Irasdonation_Utils::writeLog($get_donation_id_sql, "get_donation_id_sql");
                $donation_id = "NULL";
                while ($result->fetch()) {
                    $donation_id = $result->id;
                }
                if (!$donation_id) {
                    $donation_id = "NULL";
                }


//                try {
                    $database = \CRM_Core_DAO::executeQuery("INSERT IGNORE INTO civicrm_o8_iras_donation_log(
                                     record_id,
                                     id_type,
                                     id_number,
                                     individual_indicator,
                                     contact_name,
                                     address_line1,
                                     address_line2,
                                     postal_code,
                                     donation_amount,
                                     date_of_donation,
                                     receipt_num,
                                     type_of_donation,
                                     naming_donation,
                                     iras_response_id,
                                     iras_donation_id
                                   ) VALUES (%1, %2, %3, %4, %5, %6, %7, %8, %9, %10, %11, %12, %13, %14, %15)",
                        array(
                            1 => array(intval($record_id), 'Integer'),
                            2 => array(strval($id_type), 'String'),
                            3 => array(strval($id_number), 'String'),
                            4 => array(strval($individual_indicator), 'String'),
                            5 => array(strval($contact_name), 'String'),
                            6 => array(strval($address_line1), 'String'),
                            7 => array(strval($address_line2), 'String'),
                            8 => array(strval($postal_code), 'String'),
                            9 => array(intval($donation_amount), 'Integer'), // Assuming $donation_amount is a floating-point number
                            10 => array(strval($date_of_donation), 'String'),
                            11 => array(strval($receipt_num), 'String'),
                            12 => array(strval($type_of_donation), 'String'),
                            13 => array(strval($naming_donation), 'String'),
                            14 => array(intval($response_log_id), 'Integer'),
                            15 => array(intval($donation_id), 'Integer'),
                        )
                    );
                    $queryString = CRM_Core_DAO::getLog();
                    self::writeLog($queryString);
//                    self::writeLog('Step 4: ' . $firstdonid . "_" . $donkey . "_" . $key . "_" . $contribution_id, "in_event_contribution_id");

//                } catch (Exception $e) {
//
//                    self::writeLog($e->getMessage(), "INSERT IGNORE INTO civicrm_o8_iras_donation_log");
//                }

                try {
                    $result = CRM_Core_DAO::executeQuery('SELECT LAST_INSERT_ID() id;', CRM_Core_DAO::$_nullArray);
                } catch (Exception $e) {
                    self::writeLog($e->getMessage());
                }

                try {
                    while ($result->fetch()) {
                        $donation_log_id = $result->id;


                    }
//                    self::writeLog('Step 6: ' . $firstdonid . "_" . $donkey . "_" . $key . "_" . $contribution_id, "in_event_contribution_id");
                } catch (Exception $e) {
                    self::writeLog($e->getMessage());
                }

//
                $set_donation_log_id_sql = "UPDATE IGNORE civicrm_o8_iras_donation set last_donation_log_id = $donation_log_id WHERE contribution_id = $contribution_id";
                try {
                    $result = CRM_Core_DAO::executeQuery($set_donation_log_id_sql, CRM_Core_DAO::$_nullArray);

                } catch (Exception $e) {
                    self::writeLog($e->getMessage());
                }
            }
            return $result;
        };

        // Add an event listener for the BackgroundProcessEvent
        $listeners = Civi::dispatcher()->getListeners('addedDonations');
        if ((!$listeners) || sizeof($listeners) == 0) {
            Civi::dispatcher()->addListener('addZohoDonations', function (GenericEvent $event) {
                // Get the function and its parameters from the event
                $function = $event->getArgument('function');
                $parameters = $event->getArgument('parameters');
//                self::writeLog($parameters[2], "key");
//                self::writeLog($parameters[1], "response_log_id");
                // Call the function with its parameters
                call_user_func_array($function, $parameters);

            });
        }

// Create a new instance of the GenericEvent class


// Define the function to execute in the background

// Define the parameters to pass to the function
        $chunks = array_chunk($donations, 250);
        foreach ($chunks as $key => $chunk) {
            $backgroundEvent = new GenericEvent();
            $myParameters = [$chunk, $response_log_id, $key];
// Set the function and its parameters as arguments of the event
            $backgroundEvent->setArgument('function', $log_donation);
            $backgroundEvent->setArgument('parameters', $myParameters);
            self::showStatusMessage('Sent a chunk #' . $key . " to dispatcher", "Dispatcher");
// Dispatch the BackgroundProcessEvent event
            Civi::dispatcher()->dispatch('addZohoDonations', $backgroundEvent);
        }
    }

    /**
     * @param int $contact_id
     * @return int
     */
    public static function getIrasCustomValue(string $custom_field_name, int $entity_id, string $default_value): string
    {
        $custom_value = $default_value;
        $iras_custom_field = 'custom_' . self::get_custom_field_id($custom_field_name);
        $getparams['entityID'] = $entity_id;
        $getparams[$iras_custom_field] = 1;
//        self::writeLog($getparams, 'getparams');
        $contact_custom_fields = CRM_Core_BAO_CustomValueTable::getValues($getparams);
        if (isset($contact_custom_fields[$iras_custom_field])) {
            if ($contact_custom_fields[$iras_custom_field]) {
                $custom_value = strval($contact_custom_fields[$iras_custom_field]);
//                self::writeLog($custom_value, 'custom_field: ' . $iras_custom_field);
                return $custom_value;
            }
        }
        return $custom_value;
    }

}



