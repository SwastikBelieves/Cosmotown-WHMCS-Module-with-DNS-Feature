<?php
/**
 * @copyright Swastik Chakraborty <hello@swastik.dev>
 * @link      http://swastik.dev/
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
 * Date: 14.09.2025
 * Time: 03.29 AM (IST)
 */

namespace WHMCS\Module\Registrar\Cosmotown;

class GetEPPCode extends ApiClient
{
    /**
     * @throws \Exception
     */
    public function execute()
    {
        $domain = $this->params["domainname"];
        $this->call('domainepp?domain=' . urlencode($domain), [], 'GET');
        
        $result = $this->getResults();

        if (!isset($result['auth_code'])) {
            throw new \Exception("Auth code not found in API response.");
        }

        return [
            'eppcode' => $result['auth_code'],
        ];
    }
}