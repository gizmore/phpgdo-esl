<?php
namespace GDO\EdwardSnowdenLand;

use GDO\Country\GDT_Country;

/**
 * Like a country select, but adds special country "all".
 */
final class GDT_CountryExt extends GDT_Country
{

    protected function __construct()
    {
        parent::__construct();
    }

    protected function getChoices(): array
    {
        $choices = ['all' => t('all')];
        return array_merge($choices, parent::getChoices());
    }

}
