<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO;
use GDO\Core\GDT;
use GDO\Core\GDT_Response;
use GDO\Core\Method;
use GDO\Cronjob\MethodCronjob;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Container;
use GDO\UI\MethodCard;
use GDO\User\GDT_User;

class CurrentPresident extends Method
{

    public function execute(): GDT
    {
        $card = GDT_Card::make();
        $card->title('t_current_president');
        $box = GDT_Container::make()->vertical();

        $card->content($box);
        return $card;
    }
}