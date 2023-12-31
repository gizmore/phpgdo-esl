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
use GDO\Language\Module_Language;
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

}
