<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2014, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / HotOrNot / Model
 */
namespace PH7;
use PH7\Framework\Mvc\Model\Engine\Db;

class HotOrNotModel extends Framework\Mvc\Model\Engine\Model
{

    /**
     * Get random picture.
     *
     * @param integer $iProfileId Default NULL
     * If the user is connected, you need the ID of the user in this parameter to not display the avatar of the user since the user can not vote for himself.
     *
     * @param integer $iApproved Default 1
     * @param integer $iOffset Default 0
     * @param integer $iLimit Default 1
     * @return object DATA ot the user (profileId, username, firstName, sex, avatar).
     */
    public function getPicture($iProfileId = null, $iApproved = 1, $iOffset = 0, $iLimit = 1)
    {
        $sSql = (!empty($iProfileId)) ? ' AND (profileId <> :profileId) ' : ' ';
        $rStmt = Db::getInstance()->prepare('SELECT profileId, username, firstName, sex, avatar FROM'.Db::prefix('Members') . 'WHERE (username <> \'' . PH7_GHOST_USERNAME . '\')' . $sSql . 'AND (avatar IS NOT NULL) AND (approvedAvatar = :approved) ORDER BY RAND() LIMIT :offset, :limit');

        if (!empty($iProfileId)) $rStmt->bindValue(':profileId', $iProfileId, \PDO::PARAM_INT);
        $rStmt->bindValue(':approved', $iApproved, \PDO::PARAM_INT);
        $rStmt->bindParam(':offset', $iOffset, \PDO::PARAM_INT);
        $rStmt->bindParam(':limit', $iLimit, \PDO::PARAM_INT);
        $rStmt->execute();

        $oRow = $rStmt->fetch(\PDO::FETCH_OBJ);
        Db::free($rStmt);
        return $oRow;
    }

}
