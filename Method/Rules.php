<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\EdwardSnowdenLand\GDT_ESLPetitionState;
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
        $fromgdo = $table->gdoColumnsOnly('rule_id', 'rule_petition_state', 'rule_country', 'rule_title', 'rule_description', 'rule_created');
        return array_merge($headers, $fromgdo);
    }

    public function onCreateTable(GDT_Table $table): void
    {
        $gdo = ESL_Rule::blank();
        $state = GDT_ESLPetitionState::make('rule_petition_state')->gdo($gdo);
        $table->text('info_esl_rules', [
            $this->renderLegendItem($gdo, $state, GDT_ESLPetitionState::NOT_YET),
            $this->renderLegendItem($gdo, $state, GDT_ESLPetitionState::CREATED),
            $this->renderLegendItem($gdo, $state, GDT_ESLPetitionState::VOTED),
            $this->renderLegendItem($gdo, $state, GDT_ESLPetitionState::PUBLISHED),
            $this->renderLegendItem($gdo, $state, GDT_ESLPetitionState::SUCCEEDED),
            $this->renderLegendItem($gdo, $state, GDT_ESLPetitionState::FAILED),
            $state->renderHTML(),
        ]);
    }

    private function renderLegendItem(ESL_Rule $gdo, GDT_ESLPetitionState $gdt, string $state): string
    {
        $gdo->setVar('rule_petition_state', $state);
        return $gdt->gdo($gdo)->renderHTML();
    }

}
