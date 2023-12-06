<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDT_EnumNoI18n;

class GDT_ESLSection extends GDT_EnumNoI18n
{

    protected function __construct()
    {
        parent::__construct();
        $this->enumValues('combat_zone', 'real_world');
    }
}
