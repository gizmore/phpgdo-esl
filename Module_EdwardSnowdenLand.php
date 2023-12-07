<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Page;
use GDO\User\GDT_User;

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
            'Account',
            'Bootstrap5Theme',
            'FontAwesome',
            'Invite',
            'JQueryAutocomplete',
            'Mail',
            'Register',
            'Votes',
		];
	}

    public function getClasses(): array
    {
        return [
            ESL_Aspirings::class,
            ESL_AspirantLikes::class,
            ESL_Rule::class,
            ESL_RuleComments::class,
            ESL_RuleLikes::class,
        ];
    }


    public function onInitSidebar(): void
    {
        $bar = GDT_Page::instance()->leftBar();
        $bar->addField(GDT_Link::make('current_president')->href($this->href('CurrentPresident')));
        $bar->addField(GDT_Link::make('current_votings')->href($this->href('CurrentVotings')));
        $bar->addField(GDT_Link::make('mt_esl_suggestpresident')->href($this->href('SuggestPresident')));
    }

    public function getConfig(): array
    {
        return [
            GDT_User::make('president')->notNull()->initial('1'),
        ];
    }

}
