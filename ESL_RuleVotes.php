<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Votes\GDO_VoteTable;

class ESL_RuleVotes extends GDO_VoteTable
{
    public function gdoVoteObjectTable() { return ESL_Rule::table(); }

}