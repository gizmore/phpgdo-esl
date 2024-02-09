<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Avatar\GDO_Avatar;
use GDO\Avatar\GDO_UserAvatar;
use GDO\Avatar\Module_Avatar;
use GDO\Comments\GDO_Comment;
use GDO\Comments\Module_Comments;
use GDO\Core\GDO;
use GDO\Core\GDO_DBException;
use GDO\Core\GDO_Exception;
use GDO\Country\GDO_Country;
use GDO\Crypto\BCrypt;
use GDO\Date\Time;
use GDO\Favicon\Module_Favicon;
use GDO\File\GDO_File;
use GDO\Forum\GDO_ForumBoard;
use GDO\Forum\Module_Forum;
use GDO\Language\Module_Language;
use GDO\News\GDO_News;
use GDO\News\GDO_NewsText;
use GDO\Register\Module_Register;
use GDO\User\GDO_Permission;
use GDO\User\GDO_User;
use GDO\User\GDO_UserPermission;
use GDO\User\GDT_UserType;

/**
 * Install basic Government
 *
 * @author gizmore@wechall.net
 */
class Install
{
    /**
     * @throws GDO_DBException
     */
    private static function installCountry(): void
    {
        $pop = GDO_User::table()->countWhere();
        GDO_Country::blank([
            'c_iso' => 'XX',
            'c_iso3' => 'XXX',
            'c_phonecode' => null,
            'c_population' => $pop,
        ])->softReplace();
    }

    /**
     * @throws GDO_DBException|GDO_Exception
     */
    public static function install(): void
	{
        self::installConfig();
        self::installCountry();
		self::installPermissions();
        self::installGizmore();
        self::installTestUsers();
        self::installPresidents();
        self::installMinisters();
        self::installRules();
        self::installForum();
        self::installNews();
	}

    /**
     * @throws GDO_Exception
     */
    private static function installConfig(): void
    {
        Module_Language::instance()->saveConfigVar('languages', '["en","de"]');
        Module_Avatar::instance()->saveConfigVar('hook_sidebar', '0');
        Module_Register::instance()->saveVar('module_enabled', '1');
        Module_Comments::instance()->saveConfigVar('comment_approval_guest', '0');

        # Favicon
        $m = Module_Favicon::instance();
        if (!$m->getConfigVar('favicon'))
        {
            $path = Module_EdwardSnowdenLand::instance()->filePath('img/logo1.jpg');
            $file = GDO_File::fromPath('favicon.ico', $path)->insert();
            $m->saveConfigVar('favicon', $file->getID());
            $m->updateFavicon();
        }

    }

	private static function installPermissions(): void
	{
		$permissions = [
			'president',
			'expresident',
			'minister',
		];
		foreach ($permissions as $permission)
		{
			GDO_Permission::create($permission);
		}
	}

    /**
     * @throws GDO_Exception
     */
    private static function installGizmore(): void
    {
        if (!$user = GDO_User::getByName('gizmore'))
        {
            $user = GDO_User::blank([
                'user_name' => 'gizmore',
                'user_type' => GDT_UserType::MEMBER,
            ])->insert();

        }
        $secrets = require Module_EdwardSnowdenLand::instance()->filePath('secret.php');
        $password = $secrets['gizmore_pass'];
        $user->saveSettingVar('Login', 'password', BCrypt::create($password)->__toString());
        $user->saveSettingVar('Mail', 'email', 'gizmore@es-land.net');
        $user->saveSettingVar('Mail', 'email_confirmed', Time::getDate());

        GDO_UserPermission::grant($user, GDO_Permission::ADMIN);
        GDO_UserPermission::grant($user, GDO_Permission::STAFF);
        GDO_UserPermission::grant($user, GDO_Permission::CRONJOB);

        if (!GDO_Avatar::forUser($user)->isPersisted())
        {
            $module = Module_EdwardSnowdenLand::instance();
            $path = $module->filePath("img/gizmore.png");
            $file = GDO_File::fromPath('gizmore.png', $path)->insert();
            $avatar = GDO_Avatar::blank([
                'avatar_file_id' => $file->getID(),
                'avatar_created_by' => $user->getID(),
            ])->insert();
            GDO_UserAvatar::updateAvatar($user, $avatar->getID());
        }
    }
    private static function installTestUsers()
    {
        $users = [
            'DejaVu',
        ];
        foreach ($users as $username)
        {
            self::installTestUser($username);
        }
    }

    private static function installTestUser($username)
    {
        if (!$user = GDO_User::getByName($username))
        {
            $user = GDO_User::blank([
                'user_name' => $username,
                'user_type' => GDT_UserType::MEMBER,
            ])->insert();

        }
        $secrets = require Module_EdwardSnowdenLand::instance()->filePath('secret.php');
        $password = $secrets["{$username}_pass"];
        $user->saveSettingVar('Login', 'password', BCrypt::create($password)->__toString());
        $user->saveSettingVar('Mail', 'email', $secrets["{$username}_mail"]);
        $user->saveSettingVar('Mail', 'email_confirmed', Time::getDate());
    }

    private static function installPresidents()
    {
    }

    private static function installMinisters()
    {
    }

    /**
     * @throws GDO_DBException|GDO_Exception
     */
    private static function installRules(): void
    {
        $data = require Module_EdwardSnowdenLand::instance()->filePath('seed.php');

        foreach ($data as $id => $d)
        {
            if (!$d['goal'])
            {
                echo "Skipping one suggestion...";
            }
            else
            {
                self::installRule($id, $d);
            }
        }
    }

    /**
     * @throws GDO_DBException
     * @throws GDO_Exception
     */
    private static function installRule(string $id, array $data): void
    {
        $country = GDO_Country::getById($data['country']);
        $rule = ESL_Rule::blank([
            'rule_id' => $id,
            'rule_country' => $country->getID(),
            'rule_title' => $data['topic'],
            'rule_description' => $data['descr'],

            'rule_current' => $data['is'],
            'rule_problem' => $data['problem'],

            'rule_government' => $data['gov'],
            'rule_mistake' => $data['error'],

            'rule_suggestion' => $data['we'],
            'rule_goal' => $data['goal'],

            'rule_discuss_started' => Time::getDate(1702204452),

            'rule_created' => Time::getDate(1702204452),
            'rule_creator' => self::gizmore()->getID(),

        ]);

        if ($data['voting'])
        {
            $rule->setVar('rule_vote_started', Time::getDate(1702204452));
        }

        if ($data['petition'])
        {
            $rule->setVar('rule_petition_created', $data['petition']);
        }

        if (isset($data['pet_url']))
        {
            $rule->setVar('rule_petition_url', $data['pet_url']);
            $rule->setVar('rule_petition_state', GDT_ESLPetitionState::PUBLISHED);
        }

        $rule->softReplace();

        foreach ($data['disc'] as $username => $msg)
        {
            self::comment($rule, $username, $msg);
        }
    }

    /**
     * @throws GDO_DBException|GDO_Exception
     */
    private static function comment(ESL_Rule $rule, string $username, string $msg): void
    {
        $t = ESL_RuleComments::table();
        $emsg = GDO::escapeS($msg);
        if (!$t->select()->joinObject('comment_id')->where("comment_id_t.comment_message_input = '$emsg'")->first()->exec()->fetchObject())
        {
            $user = self::getCommentUser($username);
            $comment = GDO_Comment::blank([
                'comment_message' => $msg,
                'comment_creator' => $user->getID(),
                'comment_approved' => Time::getDate(),
                'comment_approvor' => GDO_User::system()->getID(),
            ])->insert();
            ESL_RuleComments::blank([
                'comment_id' => $comment->getID(),
                'comment_object' => $rule->getID(),
            ])->insert();
        }
    }

    /**
     * @throws GDO_DBException
     */
    private static function getCommentUser(string $username): GDO_User
    {
        if (!($user = GDO_User::getByName($username)))
        {
            if (!($user = GDO_User::getByGuestName($username)))
            {
                $user = GDO_User::blank([
                    'user_type' => GDT_UserType::GUEST,
                    'user_guest_name' => $username,
                ])->insert();
            }
        }
        return $user;
    }

    /**
     * @throws GDO_DBException
     */
    private static function gizmore(): GDO_User
    {
        return GDO_User::getByName('gizmore');
    }

    /**
     * @throws GDO_DBException
     */
    private static function installForum(): void
    {
        GDO_ForumBoard::blank([
            'board_id' => '2',
            'board_title' => 'Politics',
            'board_description' => 'Political topics are discussed here.',
            'board_permission' => null,
            'board_allow_threads' => '0',
            'board_allow_guests' => '1',
            'board_sort' => '2',
            'board_parent' => '1',
        ])->softReplace();
        GDO_ForumBoard::blank([
            'board_id' => '1000',
            'board_title' => 'Politics in a new Country',
            'board_description' => 'Political topics for any country that have no forum yet.',
            'board_permission' => null,
            'board_allow_threads' => '1',
            'board_allow_guests' => '1',
            'board_sort' => '3',
            'board_parent' => '2',
        ])->softReplace();
        GDO_ForumBoard::blank([
            'board_id' => '3',
            'board_title' => 'Find & Search',
            'board_description' => 'Offer or ask for goods and services.',
            'board_permission' => null,
            'board_allow_threads' => '1',
            'board_allow_guests' => '1',
            'board_sort' => '4',
            'board_parent' => '1',
        ])->softReplace();
        GDO_ForumBoard::blank([
            'board_id' => '4',
            'board_title' => 'Open Chat',
            'board_description' => 'You can discuss everything else here.',
            'board_permission' => null,
            'board_allow_threads' => '1',
            'board_allow_guests' => '1',
            'board_sort' => '5',
            'board_parent' => '1',
        ])->softReplace();
        self::installForumCountryBoards();
        GDO_ForumBoard::table()->rebuildFullTree();
    }

    /**
     * @throws GDO_DBException
     */
    private static function installForumCountryBoards(): void
    {
        $countries = ESL_Rule::table()->select('DISTINCT rule_country_t.*')->joinObject('rule_country')->fetchTable(GDO_Country::table())->exec();
        while ($country = $countries->fetchObject())
        {
            /** @var GDO_Country $country */
            self::installForumCountryBoard($country);
        }
    }

    /**
     * @throws GDO_DBException
     */
    private static function installForumCountryBoard(GDO_Country $country): void
    {
        $iso = $country->getID();
        $id = (string)(1000 + ord($iso[0]) * 256 + ord($iso[1]));
        $name = $country->displayEnglishName();
        GDO_ForumBoard::blank([
            'board_id' => $id,
            'board_title' => "Politics in {$name}",
            'board_description' => "Political topics in {$name}.",
            'board_permission' => null,
            'board_allow_threads' => '1',
            'board_allow_guests' => '1',
            'board_sort' => $id,
            'board_parent' => '2',
        ])->softReplace();
    }

    /**
     * @throws GDO_DBException
     */
    private static function installNews(): void
    {
        $date = '2024-02-07 09:36:00.000';
        $titles = [
            'en' => 'First News Entry',
            'de' => 'Erster News Eintrag',
        ];
        $texts = [
            'en' => "Hello and Welcome to Edward Snowden Land.\n\nI am still working on some modules and general design.\nUntil we have our own petitions, please support the direct democracy petition in the first page of this website.",
            'de' => "Hallo und Willkommen auf Edward Snowden Land.\n\nIch arbeite immer noch an einigen Modulen und dem generellen Design.\nBis wir eigene Petitionen haben, unterstütze bitte die Petition für direkte Demokratie, die auf der Startseite hier verlinkt ist."
        ];
        self::installNewsItem($titles, $texts, $date);
    }

    /**
     * @throws GDO_DBException
     */
    private static function installNewsItem(array $titles, array $texts, string $date): void
    {
        if (!GDO_News::getBy('news_created', $date))
        {
            $gizmore = self::gizmore();
            $entry = GDO_News::blank([
                'news_visible' => '1',
                'news_send' => $date,
                'news_sent' => $date,
                'news_created' => $date,
                'news_creator' => $gizmore->getID(),
            ])->insert();
            foreach ($titles as $iso => $title)
            {
                GDO_NewsText::blank([
                    'newstext_news' => $entry->getID(),
                    'newstext_lang' => $iso,
                    'newstext_title' => $title,
                    'newstext_message' => nl2br($texts[$iso]),
                    'newstext_created' => $date,
                    'newstext_creator' => $gizmore->getID(),
                ])->insert();
            }
        }
    }

}
