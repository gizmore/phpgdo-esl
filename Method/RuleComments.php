<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Comments\Comments_List;
use GDO\EdwardSnowdenLand\ESL_RuleComments;

final class RuleComments extends Comments_List
{

    public function gdoCommentsTable()
    {
        return ESL_RuleComments::table();
    }

    public function hrefAdd()
    {
        return href('EdwardSnowdenLand', 'RuleAdd');
    }
}

