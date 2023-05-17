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
	}

	public static function installPermissions(): void
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

}
