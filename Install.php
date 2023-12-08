<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Avatar\GDO_Avatar;
use GDO\Avatar\GDO_UserAvatar;
use GDO\Core\GDO_DBException;
use GDO\Core\GDO_Exception;
use GDO\Country\GDO_Country;
use GDO\Crypto\BCrypt;
use GDO\File\GDO_File;
use GDO\User\GDO_Permission;
use GDO\User\GDO_User;
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
        self::installCountry();
		self::installPermissions();
        self::installGizmore();
        self::installPresidents();
        self::installMinisters();
        self::installRules();
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
        $password = require Module_EdwardSnowdenLand::instance()->filePath('gizmore.php');
        $user->saveSettingVar('Login', 'password', BCrypt::create($password)->__toString());

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
    }


}
