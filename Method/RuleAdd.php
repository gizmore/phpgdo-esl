<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDT;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\UI\GDT_AddButton;
use GDO\UI\GDT_Divider;

final class RuleAdd extends MethodForm
{

    public function isUserRequired(): bool { return true; }

    public function isGuestAllowed(): bool { return false; }

    protected function createForm(GDT_Form $form): void
    {
        $t = ESL_Rule::table();
        $form->text('info_esl_create_rule');
        $form->addFields(
            $t->gdoColumn('rule_country'),
            $t->gdoColumn('rule_title'),
            $t->gdoColumn('rule_description'),
            GDT_Divider::make('esl_div_now'),
            $t->gdoColumn('rule_current'),
            $t->gdoColumn('rule_problem'),
            GDT_Divider::make('esl_div_gov'),
            $t->gdoColumn('rule_government'),
            $t->gdoColumn('rule_mistake'),
            GDT_Divider::make('esl_div_we'),
            $t->gdoColumn('rule_suggestion'),
            $t->gdoColumn('rule_goal'),
        );
        $form->actions()->addField(GDT_AddButton::make('submit'));
    }

    public function formValidated(GDT_Form $form): GDT
    {
        $rule = ESL_Rule::blank($form->getFormVars())->insert();
        $href = href('EdwardSnowdenLand', 'RuleEdit', "&id={$rule->getID()}");
        return $this->redirectMessage('msg_esl_rule_added', null, $href);
    }

}
