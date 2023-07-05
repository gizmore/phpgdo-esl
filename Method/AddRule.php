<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;

class AddRule extends \GDO\Form\MethodForm
{

    protected function createForm(GDT_Form $form): void
    {
        $table = ESL_Rule::table();
        $form->text('info_esl_create_rule');
        $form->addFields(
            $table->gdoColumn('rule_title'),
            $table->gdoColumn('rule_description'),
        );
        $form->actions()->addField(GDT_Submit::make());
    }

}