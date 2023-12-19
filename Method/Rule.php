<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDT;
use GDO\Core\GDT_Response;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Table\MethodQueryCard;
use GDO\UI\GDT_AddButton;
use GDO\UI\GDT_Box;
use GDO\Votes\GDT_DislikeButton;
use GDO\Votes\GDT_LikeButton;
use GDO\Votes\GDT_VoteSelection;

final class Rule extends MethodQueryCard
{

    public function gdoTable()
    {
        return ESL_Rule::table();
    }

    public function getRule(): ESL_Rule
    {
        return $this->getQueryCard();
    }

    public function getMethodTitle(): string
    {
        return $this->getRule()->getTitle();
    }

    public function getMethodDescription(): string
    {
        return sprintf('%s - %s - %s - %s',
            t('module_esl'),
            t('md_esl_rule'),
            $this->getMethodTitle(),
            $this->getRule()->getMetaDescr(),
        );
    }

    public function execute(): GDT
    {
        $rule = $this->getRule();
        $response = GDT_Response::make();
//        $rulehtml = parent::execute();
        $comments = RuleComments::make()->executeWithInputs($this->getInputs());
        $actions = GDT_Box::make();
        $actions->addField(GDT_AddButton::make()->href(href('EdwardSnowdenLand', 'RuleAddComment', "&id={$rule->getID()}"))->label('add_comment'));
        $actions->addField(GDT_LikeButton::make()->gdo($rule));
        $actions->addField(GDT_DislikeButton::make()->gdo($rule));
        return $response->addFields($comments, $actions);
    }


}
