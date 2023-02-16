<?php

use CRM_Irasdonation_ExtensionUtil as E;

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
        'name' => 'Reciept No Prefix',
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
        $session = CRM_Core_Session::singleton();
        $code = CRM_Utils_Request::retrieveValue('code', 'String', null);
        $state = CRM_Utils_Request::retrieveValue('state', 'String', null);
        self::writeLog($code, 'code');
        self::writeLog($state, 'state');
        $selfstate = $session->get(self::STATE);
        if ($selfstate != $state) {
            print_r(['code' => $code, 'state' => $state]);
            return print_r(['code' => $code, 'state' => $state], true);
        }
        $url = self::getIrasTokenURL();
        $redirectUrl = $session->popUserContext();
        $callbackUrl = self::getCallbackURL();
        $body = array(
            'code' => $code,
            'scope' => 'DonationSub',
            'callback_url' => $callbackUrl,
            'state' => $state,
        );
        $header = self::prepareHeader();
        $decoded = self::guzzlePost($url, $header, $body);
        try {
            $access_token = $decoded['data']['token'];
        } catch (Exception $e) {
            throw new CRM_Core_Exception('No token in a JSON in Response error: ' . $e->getMessage());
        }

        $now = time();

        $session->set(SELF::ACCESSTOKEN, $access_token);
        self::writeLog($access_token, "access_token");
        $session->set(SELF::LOGINTIME, $now);

        self::writeLog($redirectUrl, "redirect_url");
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
        $clientID = self::getClientID();
        self::writeLog($clientID, "clientID");
        $clientSecret = self::getClientSecret();
        self::writeLog($clientSecret, "clientSecret");
        self::writeLog($url, "url");
        $header = self::prepareHeader();
        $decodedresponse = self::guzzleGet($url, $header);
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
     * @param string $basis_year
     * @param $num_of_records
     * @param $total_donation_amount
     * @param $details
     * @return array
     */

    public static function prepareOnlineReportBody(string $basis_year, $num_of_records, $total_donation_amount, $details): array
    {
        $settings = CRM_Irasdonation_Utils::getSettings();
        $organisation_id_no = CRM_Utils_Array::value(CRM_Irasdonation_Utils::ORGANISATION_ID['slug'], $settings);
        $organisation_id_type = CRM_Utils_Array::value(CRM_Irasdonation_Utils::ORGANIZATION_TYPE['slug'], $settings);
        $organisation_name = CRM_Utils_Array::value(CRM_Irasdonation_Utils::ORGANISATION_NAME['slug'], $settings);
        $authorised_person_id = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_ID['slug'], $settings);
        $authorised_person_name = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_NAME['slug'], $settings);
        $authorised_person_designation = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_DESIGNATION['slug'], $settings);
        $telephone = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_PHONE['slug'], $settings);
        $authorised_person_email = CRM_Utils_Array::value(CRM_Irasdonation_Utils::AUTHORISED_PERSON_EMAIL['slug'], $settings);
        $validate_only = self::getValidateOnly();
        $batch_indicator = "O";
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
    address.supplemental_address_2  address_supplemental_address_2,
    address.postal_code  address_postal_code,
    CONCAT('$prefix', LPAD(RIGHT(contribution.id, 7), 7, 0)) receipt_no,
    contribution.receive_date date_of_donation,
    contribution.total_amount donation_amount
    FROM civicrm_contribution contribution 
         INNER JOIN civicrm_financial_type fintype ON fintype.id = contribution.financial_type_id and fintype.is_deductible = 1 
         INNER JOIN civicrm_contact contact ON contact.id = contribution.contact_id
         LEFT JOIN civicrm_address address ON address.contact_id = contact.id and address.is_primary = 1
         LEFT JOIN civicrm_o8_iras_donation donation ON contribution.id = donation.contribution_id        
    WHERE $where
    LIMIT 5000";
        self::writeLog($sql, "sql");
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
            if ($idType > 0) {
                $contribution_id = $result->contribution_id;
                $contact_name = str_replace(',', '', $result->contact_name);
                $donation_amount = round($result->donation_amount);
                $date_of_donation = date("Ymd", strtotime($result->date_of_donation));
                $receipt_no = $result->receipt_no;
                $donation = array(
                    'contribution_id' => $contribution_id,
                    'record_id' => $counter,
                    'id_type' => $idType,
                    'id_number' => $contact_external_identifier,
                    'individual_indicator' => '',
                    'contact_name' => $contact_name,
                    'address_line1' => $result->address_supplemental_address_1,
                    'address_line2' => $result->address_supplemental_address_2,
                    'postal_code' => $result->address_postal_code,
                    'donation_amount' => $donation_amount,
                    'date_of_donation' => $date_of_donation,
                    'receipt_num' => $receipt_no,
                    'type_of_donation' => 'O',
                    'naming_donation' => 'Z'
                );

                $online_donation = array(
                    'recordID' => $counter + 1,
                    'idType' => $idType,
                    'idNumber' => $contact_external_identifier,
                    'individualIndicator' => '',
                    'name' => $contact_name,
                    'addressLine1' => $result->address_supplemental_address_1,
                    'addressLine2' => $result->address_supplemental_address_2,
                    'postalCode' => $result->address_postal_code,
                    'donationAmount' => $donation_amount,
                    'dateOfDonation' => $date_of_donation,
                    'receiptNum' => $receipt_no,
                    'typeOfDonation' => 'O',
                    'namingDonation' => 'Z'
                );

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
                    'O',
                    'Z'];

                array_push($offline_donations, $offline_donation);
                array_push($online_donations, $online_donation);
                array_push($donations, $donation);
                $total += $donation_amount;
                $counter++;
            }
        }
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
            $client = new GuzzleHttp\Client();
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Guzzle';
            $response = $client->post($url, [
                'user_agent' => $user_agent,
                'headers' => $header,
                'json' => $body
            ]);
            self::writeLog($header, "guzzlePostheader");
            self::writeLog(json_encode($body), "guzzlePostbody");
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
            self::writeLog($responsebody, 'guzzlePostrespobody');
        } catch (Exception $e) {
            throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
        }

        try {
            $decoded = json_decode($responsebody, true);
            self::writeLog($decoded, 'decodedguzzlePost');
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
            self::writeLog($header, "guzzleGetheader");
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
            self::writeLog($responsebody, 'guzzlePostrespobody');
        } catch (Exception $e) {
            throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
        }

        try {
            $decoded = json_decode($responsebody, true);
            self::writeLog($decoded, 'decodedguzzlePost');
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
        header('Content-Disposition: attachment; filename="' . $f_name. '";');

        foreach ($csvData as $row) {
            fputcsv($f, $row, ",", '\'', "\\");
        }
        // fseek($f, 0);

        fclose($f);
        // $file = fpassthru($f);
        return $f_name;
    }

    /**
     * @param string $reportYear
     * @param $organisation_id
     * @param $startDate
     * @param $endDate
     * @param $includePrevious
     * @param $settings
     * @return array
     */
    public static function prepareOfflineReport($report_year, $organisation_id, $total, $counter, $offline_donations)
    {
        $offline_report_csv = array();
        $dataHead = [0, 7, $report_year, 7, 0, $organisation_id, null, null, null, null, null, null, null, null];
        array_push($offline_report_csv, $dataHead);
        $offline_report_csv[] = $offline_donations;
        $offline_report_csv = array_merge($offline_report_csv, $offline_donations);

        //generate buttom line of the report
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
    public static function saveDonationLogs(int $is_api, int $validate_only, $basis_year, $organisation_id_type, $organisation_id_no, $organisation_name, $batch_indicator, $authorised_person_name, $authorised_person_designation, $telephone, $authorised_person_email, $num_of_records, $total_donation_amount, string $response_body, $response_code, $generatedDate, $donations): void
    {
        $insert_response = "INSERT INTO civicrm_o8_iras_response_log (
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
                                                                $is_api,
                                                                $validate_only,
                                                                '$basis_year',
                                                                '$organisation_id_type',
            '$organisation_id_no',
            '$organisation_name',
            '$batch_indicator',
            '$authorised_person_name',
            '$authorised_person_designation',
            '$telephone',
            '$authorised_person_email',
            $num_of_records,
            $total_donation_amount, 
                                                                '$response_body', 
                                                                $response_code, 
                                                                '$generatedDate');";
        CRM_Core_DAO::executeQuery($insert_response, CRM_Core_DAO::$_nullArray);
        $result = CRM_Core_DAO::executeQuery('SELECT LAST_INSERT_ID() id;', CRM_Core_DAO::$_nullArray);

        while ($result->fetch()) {
            $response_log_id = $result->id;
        }

        foreach ($donations as $donation) {
            $contribution_id = $donation['contribution_id'];
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


            $insert_response = "INSERT IGNORE INTO civicrm_o8_iras_donation(
                                     contribution_id, 
                                     created_date) VALUES ($contribution_id, '$generatedDate');";
            CRM_Core_DAO::executeQuery($insert_response, CRM_Core_DAO::$_nullArray);
            $get_donation_id_sql = "SELECT id from civicrm_o8_iras_donation WHERE contribution_id = $contribution_id";
            $result = CRM_Core_DAO::executeQuery($get_donation_id_sql, CRM_Core_DAO::$_nullArray);
//                U::writeLog($result, "get_donation_id");
            CRM_Irasdonation_Utils::writeLog($get_donation_id_sql, "get_donation_id_sql");
            $donation_id = "NULL";
            while ($result->fetch()) {
                $donation_id = $result->id;
            }
            if (!$donation_id) {
                $donation_id = "NULL";
            }

            $insert_donation = "INSERT IGNORE INTO civicrm_o8_iras_donation_log(
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
iras_donation_id    ) VALUES ($record_id, 
                                     $id_type,             
                                    '$id_number',           
                                    '$individual_indicator',
                                    '$contact_name',                
                                    '$address_line1',       
                                    '$address_line2',      
                                    '$postal_code',         
                                     $donation_amount,     
                                    '$date_of_donation',    
                                    '$receipt_num',         
                                    '$type_of_donation',    
                                    '$naming_donation',
                              $response_log_id, 
                              $donation_id);";
            CRM_Core_DAO::executeQuery($insert_donation, CRM_Core_DAO::$_nullArray);
            $result = CRM_Core_DAO::executeQuery('SELECT LAST_INSERT_ID() id;', CRM_Core_DAO::$_nullArray);

            while ($result->fetch()) {
                $donation_log_id = $result->id;
            }

            $set_donation_log_id_sql = "UPDATE civicrm_o8_iras_donation set last_donation_log_id = $donation_log_id WHERE contribution_id = $contribution_id";
            $result = CRM_Core_DAO::executeQuery($set_donation_log_id_sql, CRM_Core_DAO::$_nullArray);
        }
    }

}