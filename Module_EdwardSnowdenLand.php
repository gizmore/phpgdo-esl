<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDO_DBException;
use GDO\Core\GDO_Exception;
use GDO\Core\GDO_Module;
use GDO\Core\GDT_Method;
use GDO\Core\Method;
use GDO\Country\GDT_Country;
use GDO\EdwardSnowdenLand\Method\Home;
use GDO\EdwardSnowdenLand\Method\SetCountry;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Page;
use GDO\User\GDT_User;

/**
 * A Demo for free votes!
 * In real this is merely a hacking challenge on wechall.net
 */
class Module_EdwardSnowdenLand extends GDO_Module
{

    public function defaultMethod(): Method
    {
        return Home::make();
    }

    public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/esl');
	}

    /**
     * @throws GDO_DBException
     * @throws GDO_Exception
     */
    public function onInstall(): void
    {
        Install::install();
    }

    public function getDependencies(): array
	{
		return [
            'Account',
            'Avatar',
            'Bootstrap5Theme',
            'IP2Country',
            'Favicon',
            'File',
            'FontAwesome',
            'Invite',
            'JQueryAutocomplete',
            'Mail',
            'News',
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
            ESL_RuleVotes::class,
        ];
    }


    public function onInitSidebar(): void
    {
        $bar = GDT_Page::instance()->topBar();
        $bar->addField(GDT_Link::make('module_esl')->href(href('EdwardSnowdenLand', 'Home')));

        $bar = GDT_Page::instance()->leftBar();
        $bar->addField(GDT_Method::make()->method(SetCountry::make()));
        $bar->addField(GDT_Link::make('list_edwardsnowdenland_rules')->href(href('EdwardSnowdenLand', 'Rules')));
        $bar->addField(GDT_Link::make('mt_edwardsnowdenland_music')->href(href('EdwardSnowdenLand', 'Music')));


//        $bar->addField(GDT_Link::make('current_president')->href($this->href('CurrentPresident')));
//        $bar->addField(GDT_Link::make('current_votings')->href($this->href('CurrentVotings')));
//        $bar->addField(GDT_Link::make('mt_esl_suggestpresident')->href($this->href('SuggestPresident')));
    }

    public function getConfig(): array
    {
        return [
            GDT_User::make('president')->notNull()->initial('1'),
        ];
    }

    public function getUserSettings(): array
    {
        return [
            GDT_Country::make('esl_country'),
        ];
    }

}
