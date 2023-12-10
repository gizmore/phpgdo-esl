<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO_DBException;
use GDO\EdwardSnowdenLand\ESL_AspirantLikes;
use GDO\EdwardSnowdenLand\ESL_Aspirings;
use GDO\EdwardSnowdenLand\Module_EdwardSnowdenLand;
use GDO\User\GDO_User;

/**
 * Every sunday at 18:00 we elect the weeks president.
 */
class PresidentCronjob extends \GDO\Cronjob\MethodCronjob
{

    public function runAt(): string
    {
        return "0 18 * * SUN";
    }

    /**
     * @throws GDO_DBException
     */
    public function run(): void
    {
        $users = ESL_AspirantLikes::table()->select('SUM(like_votes) votes, like_object_t.*')
            ->group('like_object')->joinObject('like_object')
            ->fetchTable(GDO_User::table())
            ->exec();

        $aspirants = [];

        while ($user = $users->fetchObject())
        {
            $aspirants[$user->getID()] = $user->gdoVar('votes');
        }

        asort($aspirants);
        array_reverse($aspirants);

        $max = 0;
        $maxuid = 0;

        foreach ($aspirants as $uid => $votes)
        {
            if ($max === 0)
            {
                $max = $votes;
                $maxuid = $uid;
            }
            elseif ($max === $votes)
            {
                # deuce
            }
        }

        $oldPresidentId = Module_EdwardSnowdenLand::instance()->getConfigVar('president');

        if ($oldPresidentId == $maxuid)
        {
            $this->logNotice('No new president (keep)');
            return;
        }

        # New president
        $this->sendMails($maxuid);

        ESL_AspirantLikes::table()->truncate();
        ESL_Aspirings::table()->updateQuery()->set('esla_likes=0')->exec();
    }

    private function sendMails(): void
    {
    }



}