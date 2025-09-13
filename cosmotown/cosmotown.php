<?php
/**
 * @copyright Swastik Chakraborty <hello@swastik.dev>
 * @link      http://swastik.dev/
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
 * Date: 14.09.2025
 * Time: 03.29 AM (IST)
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Domains\DomainLookup\ResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;
use WHMCS\Module\Registrar\Cosmotown\ApiClient;

require_once __DIR__ .'/constants.php';
require_once __DIR__ .'/lib/functions.php';

function cosmotown_MetaData()
{
    return array(
        'DisplayName' => 'Cosmotown',
        'APIVersion' => '1.0',
    );
}

function cosmotown_getConfigArray()
{
    $pluginModeOptions = [
        COSMOTOWN_PLUGIN_MODE_LIVE,
        COSMOTOWN_PLUGIN_MODE_TEST_3
    ];

    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'Cosmotown Registrar',
        ],
        'APIKey' => [
            'FriendlyName' => 'API Key',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your API key here. To get your api key, go to My Account section in cosmotown.com, then click Reseller API link on the left hand side. C/p the key here. DON\'T include your password.',
        ],
        'PluginMode' => [
            'FriendlyName' => 'Plugin Mode',
            'Type' => 'radio',
            'Options' => implode(",", $pluginModeOptions),
            'Description' => 'Choose plugin behavior',
        ],
    ];
}

function _cosmotown_callApi($params, $endpoint, $method = 'GET', $postData = [], $getData = [])
{
    $apiKey = $params['APIKey'];
    $pluginMode = $params['PluginMode'];

    switch ($pluginMode) {
        case COSMOTOWN_PLUGIN_MODE_TEST_3:
            $baseUrl = COSMOTOWN_API_TEST_3_URL;
            break;
        default:
            $baseUrl = COSMOTOWN_API_LIVE_URL;
    }

    $url = $baseUrl . ltrim($endpoint, '/');

    if ($method === 'GET' && !empty($getData)) {
        $url .= '?' . http_build_query($getData);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);


    $headers = [
        'X-API-TOKEN: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json',
    ];

    if (!in_array($method, ['GET', 'DELETE']) && !empty($postData)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        throw new Exception('cURL Error: ' . curl_error($ch));
    }
    curl_close($ch);
    
    logModuleCall(
        'cosmotown',
        $endpoint,
        $postData ?: $getData,
        $response,
        $httpCode
    );

    $decodedResponse = json_decode($response, true);

    if ($httpCode >= 400 || (isset($decodedResponse['status']) && $decodedResponse['status'] === 'error')) {
        $errorMessage = $decodedResponse['message'] ?? $decodedResponse['errormessage'] ?? 'An unknown API error occurred.';
        throw new Exception("API Error: {$errorMessage} (HTTP Code: {$httpCode})");
    }

    return $decodedResponse;
}

function cosmotown_ClientAreaAllowedFunctions() {
    return [
        'DnsManagement' => 'DnsManagement',
    ];
}

function cosmotown_ClientAreaCustomButtonArray() {
    return [
        'Manage DNS Records' => 'DnsManagement',
    ];
}

function cosmotown_DnsManagement($params)
{
    $domainName = $params['sld'] . '.' . $params['tld'];
    $domainId = $params['domainid'];
    $vars = [];
    $editableTypes = ['A', 'AAAA', 'CNAME', 'MX', 'TXT'];

    try {

        // DNS RECORDS SAVE (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submittedRecords = $_POST['hostname'] ?? [];
            $payload = [];

            foreach ($editableTypes as $type) {
                $payload[$type] = [];
            }
            
            $whmcsDnsRecords = []; // Prepare to build the new record list for display

            foreach ($submittedRecords as $i => $hostname) {
                if (empty($hostname) && empty($_POST['value'][$i])) continue;

                $recordType = strtoupper(trim($_POST['type'][$i]));
                if (!in_array($recordType, $editableTypes)) continue;
                
                $hostValue = trim($hostname);
                $addressValue = trim($_POST['value'][$i]);
                $host = ($hostValue === '@') ? '' : $hostValue;
                $priority = (int)($_POST['priority'][$i] ?? 10);

                $recordData = [
                    'host' => $host,
                    'ttl' => 300,
                    'priority' => $priority,
                ];
                
                if ($recordType === 'TXT') {
                    $recordData['content'] = $addressValue;
                } else {
                    $recordData['pointto'] = $addressValue;
                }
                
                $payload[$recordType][] = $recordData;

                // Immediate display DNS records
                $whmcsDnsRecords[] = [
                    'hostname' => $hostValue,
                    'type'     => $recordType,
                    'address'  => $addressValue,
                    'priority' => ($recordType === 'MX') ? $priority : '',
                ];
            }

            $filteredPayload = array_filter($payload);

            $postData = [
                'domain' => $domainName,
                'records' => $filteredPayload,
            ];
            
            _cosmotown_callApi($params, "savedomaindnssettings", 'POST', $postData);

            $vars['saveSuccess'] = 'DNS records have been updated successfully.';
            $vars['dnsrecords'] = $whmcsDnsRecords; 

        } else {
            // Page Load (GET request)
            $response = _cosmotown_callApi($params, "getdomaindnssettings", 'GET', [], ['domain' => $domainName]);
            
            $whmcsDnsRecords = [];
            $apiRecordGroups = $response['records'] ?? [];

            foreach ($apiRecordGroups as $type => $recordsArray) {
                if (!in_array($type, $editableTypes) || !is_array($recordsArray)) continue;

                foreach ($recordsArray as $record) {
                    $addressValue = $record['content'] ?? $record['pointto'] ?? '';
                    
                    $whmcsDnsRecords[] = [
                        'hostname' => ($record['host'] === '' || $record['host'] === null) ? '@' : $record['host'],
                        'type'     => $type,
                        'address'  => $addressValue,
                        'priority' => $record['priority'] ?? '',
                    ];
                }
            }
            $vars['dnsrecords'] = $whmcsDnsRecords;
        }

    } catch (Exception $e) {
        $vars['errorMessage'] = 'Error: ' . $e->getMessage();
    }

    return [
        'templatefile' => 'dnsmanagement',
        'breadcrumb' => [
            'clientarea.php?action=domaindetails&id=' . $domainId . '&modop=custom&a=DnsManagement' => 'DNS Management',
        ],
        'vars' => $vars,
    ];
}