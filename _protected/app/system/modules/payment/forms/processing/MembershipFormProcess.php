<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2014, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Payment / Form / Processing
 */
namespace PH7;
defined('PH7') or exit('Restricted access');

use
PH7\Framework\Cache\Cache,
PH7\Framework\Url\HeaderUrl,
PH7\Framework\Mvc\Router\Uri;

class MembershipFormProcess extends Form
{

    public function __construct()
    {
        parent::__construct();

        $aData = [
            'name' => $this->httpRequest->post('name'),
            'description' => $this->httpRequest->post('description'),
            'permissions' => serialize($this->httpRequest->post('perms')),
            'price' => $this->httpRequest->post('price'),
            'expirationDays' => $this->httpRequest->post('expiration_days'),
            'enable' => $this->httpRequest->post('enable')
        ];
        (new PaymentModel)->addMembership($aData);

        /* Clean UserCoreModel Cache */
        (new Cache)->start(UserCoreModel::CACHE_GROUP, null, null)->clear();

        HeaderUrl::redirect(Uri::get('payment','admin','membershiplist'), t('The Membership has been added!'));
    }

}
