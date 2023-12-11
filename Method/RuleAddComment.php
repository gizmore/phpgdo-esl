<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Comments\Comments_Write;
use GDO\Comments\GDO_CommentTable;
use GDO\EdwardSnowdenLand\ESL_RuleComments;

final class RuleAddComment extends Comments_Write
{

    public function gdoCommentsTable(): GDO_CommentTable
    {
        return ESL_RuleComments::table();
    }

    public function hrefList(): string
    {
        return href('EdwardSnowdenLand', 'RuleComments');
    }
}
