<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO;
use GDO\EdwardSnowdenLand\ESL_Rule;

class Rules extends \GDO\Table\MethodQueryTable
{

    public function gdoTable(): GDO
    {
        return ESL_Rule::table();
    }

    public function gdoHeaders(): array
    {
        $table = $this->gdoTable();
        return $table->gdoColumnsOnly('rule_id', 'rule_title', 'rule_creator', 'rule_created');
    }

}