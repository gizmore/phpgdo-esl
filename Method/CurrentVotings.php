<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDT;
use GDO\Core\Method;
use GDO\EdwardSnowdenLand\ESL_AspirantLikes;
use GDO\EdwardSnowdenLand\ESL_Aspirings;
use GDO\Table\GDT_List;
use GDO\UI\GDT_Container;
use GDO\UI\GDT_Paragraph;
use GDO\User\GDO_User;

class CurrentVotings extends Method
{

    public function execute(): GDT
    {
        $para = GDT_Paragraph::make()->text('info_current_votings');
        $list = GDT_List::make();
        $cont = GDT_Container::make()->vertical();
        $query = ESL_Aspirings::table()->select()
            ->order('esla_likes DESC')
            ->joinObject('esla_uid');
        $cont->addFields($para, $list);
        $list->query($query);
        $list->fetchAs(ESL_Aspirings::table());
        return $cont;
    }
}