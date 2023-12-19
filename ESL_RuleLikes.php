<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Votes\GDO_LikeTable;
use GDO\Votes\GDO_VoteTable;

class ESL_RuleLikes extends GDO_LikeTable
{

    public function gdoLikeForGuests() { return false; }

    public function gdoLikeObjectTable() { return ESL_Rule::table(); }

}