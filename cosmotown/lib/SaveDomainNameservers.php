<?php
/**
 * @copyright Swastik Chakraborty <hello@swastik.dev>
 * @link      http://swastik.dev/
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
 * Date: 14.09.2025
 * Time: 03.29 AM (IST)
 */

namespace WHMCS\Module\Registrar\Cosmotown;

class SaveDomainNameservers extends ApiClient
{
    /**
     * @throws \Exception
     */
    public function execute()
    {
        $nameservers = [];
        if (!empty($this->params['ns1'])) {
            $nameservers[] = $this->params['ns1'];
        }
        if (!empty($this->params['ns2'])) {
            $nameservers[] = $this->params['ns2'];
        }
        if (!empty($this->params['ns3'])) {
            $nameservers[] = $this->params['ns3'];
        }
        if (!empty($this->params['ns4'])) {
            $nameservers[] = $this->params['ns4'];
        }
        if (!empty($this->params['ns5'])) {
            $nameservers[] = $this->params['ns5'];
        }
        $payload = array(
            "domain" => $this->params["domainname"],
            "nameservers" => $nameservers
        );
        $this->call('savedomainnameservers', $payload);
    }
}
