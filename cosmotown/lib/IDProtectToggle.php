<?php
/**
 * @copyright Swastik Chakraborty <hello@swastik.dev>
 * @link      http://swastik.dev/
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
 * Date: 14.09.2025
 * Time: 03.29 AM (IST)
 */

namespace WHMCS\Module\Registrar\Cosmotown;

class IDProtectToggle extends ApiClient
{
    /**
     * @throws \Exception
     */
    public function execute()
    {
        $this->call('domaininfo?domain=' . $this->params["domainname"], [], 'GET');
        
        $result = $this->getResults();
        
        $payload = array(
            "domain" => $this->params["domainname"],
            "options" => [
                "enable_private_whois" =>(bool) $this->params['protectenable'],
                "lock_domain" => $result['whois_privacy'],
                "enable_auto_billing" => $result['auto_billing']
            ]
        );
        $this->call('domaininfo', $payload);
    }
}
