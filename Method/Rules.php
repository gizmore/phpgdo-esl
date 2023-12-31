<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Table\GDT_Table;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;
use GDO\UI\GDT_EditButton;
use GDO\UI\GDT_ShowButton;

class Rules extends MethodQueryTable
{

    public function gdoTable(): GDO
    {
        return ESL_Rule::table();
    }

    public function gdoHeaders(): array
    {
        $headers = [
            GDT_ShowButton::make('view'),
            GDT_EditButton::make('edit'),
        ];
        $table = $this->gdoTable();
        $fromgdo = $table->gdoColumnsOnly('rule_id', 'rule_country', 'rule_title', 'rule_description', 'rule_created');
        return array_merge($headers, $fromgdo);
    }

    public function onCreateTable(GDT_Table $table): void
    {
        $table->text('info_esl_rules');
    }

}