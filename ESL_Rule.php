<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\UI\GDT_Message;
use GDO\UI\GDT_Title;
use GDO\Votes\WithLikes;

/**
 *
 */
class ESL_Rule extends GDO
{

	use WithLikes;

	public function gdoColumns(): array
	{
		return [
			GDT_AutoInc::make('rule_id'),
			GDT_Title::make('rule_title'),
			GDT_Message::make('rule_description'),
			GDT_CreatedAt::make('rule_created'),
			GDT_CreatedBy::make('rule_creator'),
		];
	}

}
