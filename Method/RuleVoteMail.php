<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDT;
use GDO\Core\GDT_Object;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;

/**
 *
 */
class RuleVoteMail extends MethodForm
{
    public function gdoParameters(): array
    {
        return [
            GDT_Object::make('id')->table(ESL_Rule::table())->notNull(),
        ];
    }

    public function execute(): GDT
    {
        $form = parent::execute();
        $rule = Rule::make()->executeWithInputs($this->getInputs());
        return $rule->addField($form);
    }

    protected function createForm(GDT_Form $form): void
    {

    }

}