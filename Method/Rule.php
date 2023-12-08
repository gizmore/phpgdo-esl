<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Table\MethodQueryCard;

final class Rule extends MethodQueryCard
{

    public function gdoTable()
    {
        return ESL_Rule::table();
    }
}
