<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Comments\Comments_Write;
use GDO\Comments\GDO_CommentTable;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\EdwardSnowdenLand\ESL_RuleComments;

final class RuleAddComment extends Comments_Write
{

    public function gdoCommentsTable(): GDO_CommentTable
    {
        return ESL_RuleComments::table();
    }

    public function getRule(): ESL_Rule
    {
        return $this->getObject();
    }

    public function hrefList(): string
    {
        $rule = $this->getRule();
        return href('EdwardSnowdenLand', 'Rule', "&id={$rule->getID()}");
    }
}
