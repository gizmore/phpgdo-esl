<?php
namespace GDO\EdwardSnowdenLand;

use GDO\Comments\GDO_CommentTable;
use GDO\Core\GDO;

final class ESL_RuleComments extends GDO_CommentTable
{
    public function gdoCommentedObjectTable(): GDO { return ESL_Rule::table(); }

    public function gdoAllowFiles(): bool { return true; }

    public function gdoEnabled(): bool { return $this->getRule()->inDiscussion(); }

    public function getRule(): ESL_Rule { return $this->getCommentedObject(); }

}
