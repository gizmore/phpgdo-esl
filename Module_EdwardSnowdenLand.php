<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDO_DBException;
use GDO\Core\GDO_Exception;
use GDO\Core\GDO_Module;
use GDO\Core\GDT_Method;
use GDO\Core\Method;
use GDO\Country\GDT_Country;
use GDO\Date\GDT_Duration;
use GDO\EdwardSnowdenLand\Method\Home;
use GDO\EdwardSnowdenLand\Method\SetCountry;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Page;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * A Demo for free votes!
 * In real this is merely a hacking challenge on wechall.net
 */
class Module_EdwardSnowdenLand extends GDO_Module
{

    public int $priority = 150;

    public function isSiteModule(): bool
    {
        return true;
    }

    public function getTheme(): ?string
    {
        return 'esl';
    }

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
            'AboutMe',
            'Account',
            'ActivationAlert',
            'Admin',
            'Avatar',
            'Bootstrap5Theme',
            'Captcha',
            'Contact',
            'Cronjob',
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
            ESL_RuleLikes::class,
        ];
    }


    public function onInitSidebar(): void
    {
        $bar = GDT_Page::instance()->topBar();
        $bar->addField(GDT_Link::make('module_esl')->href(href('EdwardSnowdenLand', 'Home')));

        $bar = GDT_Page::instance()->leftBar();
        $canAddRule = GDO_User::current()->isMember();
        $bar->addField(GDT_Method::make()->method(SetCountry::make()));
        $bar->addField(GDT_Link::make('list_edwardsnowdenland_rules')->href(href('EdwardSnowdenLand', 'Rules')));
        $bar->addField(GDT_Link::make('mt_edwardsnowdenland_ruleadd')->enabled($canAddRule)->href(href('EdwardSnowdenLand', 'RuleAdd')));
        $bar->addField(GDT_Link::make('mt_edwardsnowdenland_music')->href(href('EdwardSnowdenLand', 'Music')));


//        $bar->addField(GDT_Link::make('current_president')->href($this->href('CurrentPresident')));
//        $bar->addField(GDT_Link::make('current_votings')->href($this->href('CurrentVotings')));
//        $bar->addField(GDT_Link::make('mt_esl_suggestpresident')->href($this->href('SuggestPresident')));
    }

    public function getConfig(): array
    {
        return [
            GDT_User::make('president')->notNull()->initial('1'),
            GDT_Duration::make('min_discussion_time')->notNull()->initial('7d'),
        ];
    }

    public function cfgMinAgeForVoteDuration(): int { return $this->getConfigValue('min_discussion_time'); }


    public function getUserSettings(): array
    {
        return [
            GDT_Country::make('esl_country'),
        ];
    }


}
