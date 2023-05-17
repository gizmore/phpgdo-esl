<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDO_Module;

/**
 * A Demo for free votes!
 * In real this is merely a hacking challenge on wechall.net
 */
class Module_EdwardSnowdenLand extends GDO_Module
{

	public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/esl');
	}

	public function getDependencies(): array
	{
		return [
			'Bootstrap5Theme',
			'Votes',
		];
	}

}
