<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDO_DBException;
use GDO\Country\GDO_Country;
use GDO\User\GDO_Permission;
use GDO\User\GDO_User;

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

    private static function installGizmore(): void
    {
        
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
