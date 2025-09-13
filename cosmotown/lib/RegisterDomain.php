<?php
/**
 * @copyright Swastik Chakraborty <hello@swastik.dev>
 * @link      http://swastik.dev/
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
 * Date: 14.09.2025
 * Time: 03.29 AM (IST)
 */

namespace WHMCS\Module\Registrar\Cosmotown;

class RegisterDomain extends ApiClient
{
    /**
     * @throws \Exception
     */
    public function execute()
    {
        $payload = array(
            "coupon_id" => $this->params['PromotionCode'],
            "items" => [
                [
                    "name" => $this->params["domainname"],
                    "years" => (int)$this->params["regperiod"]
                ]
            ]
        );

        $this->call('registerdomains', $payload);
    }
}
