<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Avatar\GDO_Avatar;
use GDO\Avatar\GDO_UserAvatar;
use GDO\Avatar\Module_Avatar;
use GDO\Core\GDO_DBException;
use GDO\Core\GDO_Exception;
use GDO\Country\GDO_Country;
use GDO\Crypto\BCrypt;
use GDO\File\GDO_File;
use GDO\Language\Module_Language;
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
        self::installPresidents();
        self::installMinisters();
        self::installRules();
	}

    private static function installConfig(): void
    {
        Module_Language::instance()->saveConfigVar('languages', '["en","de"]');
        Module_Avatar::instance()->saveConfigVar('hook_sidebar', '0');
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

        GDO_UserPermission::grant($user, GDO_Permission::ADMIN);
        GDO_UserPermission::grant($user, GDO_Permission::STAFF);

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
    private static function installPresidents()
    {
    }

    private static function installMinisters()
    {
    }

    private static function installRules()
    {
        $data = [
            '1' => [
                'country' => 'DE',
                'topic'
            ],
        ];
    }


}
