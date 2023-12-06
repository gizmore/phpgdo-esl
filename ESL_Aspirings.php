<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\Core\GDT_Template;
use GDO\Date\GDT_Month;
use GDO\User\GDT_User;
use GDO\Votes\GDT_LikeCount;
use GDO\Votes\WithLikes;

class ESL_Aspirings extends \GDO\Core\GDO
{

    use WithLikes;

    public function gdoLikeTable()
    {
        return ESL_AspirantLikes::table();
    }

    public function gdoColumns(): array
    {
        return [
//            GDT_AutoInc::make('esla_id'),
            GDT_User::make('esla_uid')->notNull()->primary(),
            GDT_LikeCount::make('esla_likes'),
//            GDT_Month::make('esla_month')->notNull(),
            GDT_Checkbox::make('esla_active')->notNull()->initial('1'),
            GDT_CreatedAt::make('esla_created'),
            GDT_CreatedBy::make('esla_creator'),
        ];
    }

    public function renderCard(): string
    {
        return GDT_Template::make()->template('EdwardSnowdenLand', 'li_president.php', ['gdt' => $this]);
    }

}