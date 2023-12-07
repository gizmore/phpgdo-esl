<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Comments\CommentedObject;
use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\Country\GDT_Country;
use GDO\UI\GDT_Message;
use GDO\UI\GDT_Title;
use GDO\Votes\WithLikes;

/**
 *
 * @see WithLikes
 */
class ESL_Rule extends GDO
{

	use WithLikes;
    use CommentedObject;


	public function gdoColumns(): array
	{
		return [
			GDT_AutoInc::make('rule_id'),
            GDT_Country::make('rule_country')->notNull(),
			GDT_Title::make('rule_title')->notNull(),
			GDT_Message::make('rule_description')->notNull(),

            GDT_Message::make('rule_current'), # IST
            GDT_Message::make('rule_problem'), # PROBLEM

            GDT_Message::make('rule_government'), # THEY
            GDT_Message::make('rule_mistake'), # FEHLER

            GDT_Message::make('rule_suggestion')->notNull(), # WE
            GDT_Message::make('rule_goal')->notNull(), # GOAL

            GDT_Checkbox::make('rule_discuss_state')->initial('1'), # Voting time?
            GDT_Checkbox::make('rule_vote_state'), # Voting time?

            GDT_CreatedAt::make('rule_created'),
			GDT_CreatedBy::make('rule_creator'),
		];
	}

    public function inDiscussion(): bool { return $this->gdoValue('rule_discuss_state'); }

}
