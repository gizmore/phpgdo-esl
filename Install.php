<?php

namespace GDO\EdwardSnowdenLand;

use GDO\User\GDO_Permission;

/**
 * Install basic Government
 *
 * @author gizmore@wechall.net
 */
class Install
{
	public static function install(): void
	{
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
