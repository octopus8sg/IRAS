<?php

use CRM_Irasdonation_ExtensionUtil as E;

use \Firebase\JWT\JWT;

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
        'name' => 'Reciept ID Custom Field',
        'description' => "Custom field to get Reciept ID from (leave blank to use defaults)"];
    public const RECIEPT_DATE = [
        'slug' => 'reciept_date',
        'name' => 'Reciept Date Custom Field',
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

    public static function callbackUrl()
    {
//        try {
        $code = CRM_Utils_Request::retrieveValue('code', 'String', null);
        $state = CRM_Utils_Request::retrieveValue('state', 'String', null);
        $json = "{'code': '$code', 'state': '$state'}";
        self::writeLog($json, "json");

//            CRM_Utils_JSON::output("{'code': '$code', 'state': '$state'}");
        self::writeLog($json, "json2");
        $session = CRM_Core_Session::singleton();
//            CRM_Core_BAO_Navigation::resetNavigation();
        $redirectUrl = $session->popUserContext();
        $now = time();
        $session->set(SELF::ACCESSTOKEN, "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6Il9SQzZ4d09NdmJ0dDZhald1WmU2R2xncy1qM3dtNXJpQXlDVW9SYXNhLUkifQ");
        $session->set(SELF::LOGINTIME, $now);
        self::writeLog($redirectUrl, "redirect_url");
        CRM_Utils_System::redirect($redirectUrl);
//        } catch (Exception $e) {
//            self::writeLog($e->getMessage());
//        }
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
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

//    /**
//     * @return bool
//     */
//    public static function getSendContact(): bool
//    {
//        $result = false;
//        try {
//            $result_ = self::getSettings(self::SEND_CONTACT);
//            if ($result_ == 1) {
//                $result = true;
//            }
//            return $result;
//        } catch (\Exception $exception) {
//            $error_message = $exception->getMessage();
//            $error_title = 'Send Contact Config Required';
//            self::showErrorMessage($error_message, $error_title);
//        }
//    }
//
//    /**
//     * @return bool
//     */
//    public static function getSendContribution(): bool
//    {
//        $result = false;
//        try {
//            $result_ = self::getSettings(self::SEND_CONTRIBUTION);
//            if ($result_ == 1) {
//                $result = true;
//            }
//            return $result;
//        } catch (\Exception $exception) {
//            $error_message = $exception->getMessage();
//            $error_title = 'Send Contribution Config Required';
//            self::showErrorMessage($error_message, $error_title);
//        }
//    }


//    /**
//     * @return string
//     */
//
//    public static function getRefreshToken(): string
//    {
//        $result = "";
//        try {
//            $result = strval(self::getSettings(self::REFRESH_TOKEN));
////            self::writeLog($result, 'getValidateUEN');
//            return $result;
//        } catch (\Exception $exception) {
//            $error_message = $exception->getMessage();
//            $error_title = 'Write Log Config Required';
//            self::showErrorMessage($error_message, $error_title);
//        }
//    }

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

//    public static function getClientSecret(): string
//    {
//        $result = "";
//        try {
//            $result = strval(self::getSettings(self::CLIENT_SECRET));
////            self::writeLog($result, 'getValidateUEN');
//            return $result;
//        } catch (\Exception $exception) {
//            $error_message = $exception->getMessage();
//            $error_title = 'Write Log Config Required';
//            self::showErrorMessage($error_message, $error_title);
//        }
//    }
//
//    public static function getRedirectURI(): string
//    {
//        $result = "";
//        try {
//            $result = strval(self::getSettings(self::REDIRECT_URI));
////            self::writeLog($result, 'getValidateUEN');
//            return $result;
//        } catch (\Exception $exception) {
//            $error_message = $exception->getMessage();
//            $error_title = 'Write Log Config Required';
//            self::showErrorMessage($error_message, $error_title);
//        }
//    }
//
//    public static function getAccessTokenURL(): string
//    {
//        $refresh_token = self::getRefreshToken();
//        $client_id = self::getClientID();
//        $client_secret = self::getClientSecret();
//        $redirect_uri = self::getRedirectURI();
//        if ($refresh_token == "") return "";
//        if ($client_id == "") return "";
//        if ($client_secret == "") return "";
//        if ($redirect_uri == "") return "";
//        $result = "https://accounts.zoho.com/oauth/v2/token?refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret&redirect_uri=$redirect_uri&grant_type=refresh_token";
//        try {
//            return $result;
//        } catch (\Exception $exception) {
//            $error_message = $exception->getMessage();
//            $error_title = 'Write Log Config Required';
//            self::showErrorMessage($error_message, $error_title);
//        }
//    }

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
            case ($uen[0] == 'F' || $uen[0] == 'G') && is_numeric(substr($uen, 1, 7)):
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
            ->addWhere('data_type', '=', '\'Date\'')
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
            ->addJoin('CustomGroup AS custom_group', 'LEFT', ['custom_group_id', '=', 'custom_group.id'])
            ->addWhere('custom_group.extends', '=', 'Contribution')
            ->addWhere('data_type', '=', 'String')
            ->execute();
        foreach ($customFields as $customField) {
            $contributionStringCustomFields[$customField['name']] = $customField['label'];
        }
        return $contributionStringCustomFields;
    }

    /**
     * @param string $reportYear
     * @param $organization_type
     * @param $organisation_id
     * @param $organisation_name
     * @param $authorised_person_id
     * @param $authorised_person_name
     * @param $authorised_person_designation
     * @param $authorised_person_phone
     * @param $authorised_person_email
     * @param $counter
     * @param $total
     * @param $details
     * @return array
     */
    public static function prepareBody(string $reportYear, $counter, $total, $details): array
    {
        $settings = CRM_Irasdonation_Utils::getSettings();
        $organisation_id = CRM_Utils_Array::value(CRM_Irasdonation_Utils::ORGANISATION_ID['slug'], $settings);
        $organization_type = CRM_Utils_Array::value(CRM_Irasdonation_Utils::ORGANIZATION_TYPE['slug'], $settings);
        $organisation_name = CRM_Utils_Array::value(CRM_Irasdonation_Utils::ORGANISATION_NAME['slug'], $settings);
        $authorised_person_id = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_ID['slug'], $settings);
        $authorised_person_name = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_NAME['slug'], $settings);
        $authorised_person_designation = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_DESIGNATION['slug'], $settings);
        $authorised_person_phone = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_PHONE['slug'], $settings);
        $authorised_person_email = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_EMAIL['slug'], $settings);

        $body = array(
            'orgAndSubmissionInfo' => [
                'validateOnly' => 'true',
                'basisYear' => $reportYear,
                'organisationIDType' => $organization_type,
                'organisationIDNo' => $organisation_id,
                'organisationName' => $organisation_name,
                'batchIndicator' => 'O',
                'authorisedPersonIDNo' => $authorised_person_id,
                'authorisedPersonName' => $authorised_person_name,
                'authorisedPersonDesignation' => $authorised_person_designation,
                'telephone' => $authorised_person_phone,
                'authorisedPersonEmail' => $authorised_person_email,
                'numOfRecords' => $counter,
                'totalDonationAmount' => $total
            ],
            "donationDonorDtl" => $details
        );
        return $body;
    }

    /**
     * @param $client_id
     * @param $client_secret
     * @param string $access_token
     * @return array
     */
    public static function prepareHeader($client_id, $client_secret, string $access_token): array
    {
        $header = [
            "Accept: application/json",
            "charset: UTF-8",
            "Content-Type: application/json",
            "X-IBM-Client-Id: $client_id",
            "X-IBM-Client-Secret: $client_secret",
            "access_token: $access_token",
        ];
        return $header;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $includePrevious
     * @return array|void
     */
    public static function prepareReportDetails($startDate, $endDate, $includePrevious)
    {


        $reportYear = date("Y");


        $where = "UPPER(cdnlog.receipt_status)='ISSUED'";

        if ($includePrevious == 0) {
            $where .= " AND cdnlog.id NOT IN 
        (SELECT iras_donation.cdntaxreceipts_log_id FROM civicrm_o8_iras_donation iras_donation 
        WHERE iras_donation.created_date IS NOT NULL) ";
        }
        if ($startDate != null && $endDate != null) {
            $where .= " AND FROM_UNIXTIME(cdnlog.issued_on) >= '$startDate' AND FROM_UNIXTIME(cdnlog.issued_on) <= '$endDate'";
        }


        //generate header of report
        $sql = "SELECT SQL_CALC_FOUND_ROWS
    cdnlog.id cdnlog_id, 
    contact.sort_name contact_sort_name, 
    contact.external_identifier contact_external_identifier,
    address.supplemental_address_1 address_supplemental_address_1,
    address.supplemental_address_2  address_supplemental_address_2,
    address.postal_code  address_postal_code,
    RIGHT(cdnlog.receipt_no, 10) cdnlog_receipt_no,
    FROM_UNIXTIME(cdnlog.issued_on) cdnlog_issued_on,
    cdnlog.receipt_amount cdnlog_receipt_amount
    FROM cdntaxreceipts_log cdnlog 
    INNER JOIN cdntaxreceipts_log_contributions cdnlogcontrib ON cdnlogcontrib.receipt_id = cdnlog.id
    INNER JOIN civicrm_contribution contrib ON contrib.id = cdnlogcontrib.contribution_id  
    INNER JOIN civicrm_contact contact ON contact.id = cdnlog.contact_id 
    INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id   
    LEFT JOIN civicrm_address address ON address.id = contact.addressee_id
    WHERE $where
    LIMIT 5000";
        self::writeLog($sql, "sql");
        $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

        $totalRows = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $insert = '';
        $total = 0;
        $counter = 0;
        $generatedDate = date('Y-m-d H:i:s');

        $dataBody = array();
        $reportedIDs = array();
        //generate body of th report
        $details = array();
        while ($result->fetch()) {

            $idType = self::parsUENNumber($result->contact_external_identifier);
            self::writeLog($idType);
            if ($idType > 0) {
                $dataBody = array(
                    'recordID' => $counter + 1,
                    'idType' => $idType,
                    'idNumber' => $result->contact_external_identifier,
                    'individualIndicator' => '',
                    'name' => $result->contact_sort_name,
                    'addressLine1' => $result->address_supplemental_address_1,
                    'addressLine2' => $result->address_supplemental_address_2,
                    'postalCode' => '',
                    'donationAmount' => round($result->cdnlog_receipt_amount),
                    'dateOfDonation' => date("Ymd", strtotime($result->cdnlog_issued_on)),
                    'receiptNum' => $result->cdnlog_receipt_no,
                    'typeOfDonation' => 'O',
                    'namingDonation' => 'Z'
                );

                array_push($reportedIDs, $result->cdnlog_id);

                array_push($details, $dataBody);
                $total += $result->receipt_amount;
                $counter++;
            }
        }
        return array($totalRows, $total, $counter, $generatedDate, $reportedIDs, $details);
    }

}