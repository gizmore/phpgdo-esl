<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Votes\GDO_LikeTable;

class ESL_AspirantLikes extends GDO_LikeTable
{
    public function gdoLikeObjectTable() { return ESL_Aspirings::table(); }

}