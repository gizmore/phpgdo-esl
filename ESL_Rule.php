<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Comments\CommentedObject;
use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\Country\GDT_Country;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Divider;
use GDO\UI\GDT_Message;
use GDO\UI\GDT_Title;
use GDO\User\GDO_User;
use GDO\Votes\WithVotes;

class ESL_Rule extends GDO
{

	use WithVotes;
    use CommentedObject;

    public function gdoVoteTable() { return ESL_RuleVotes::table(); }

    public function gdoVoteAllowed(GDO_User $user)
    {

    }


	public function gdoColumns(): array
	{
		return [
			GDT_AutoInc::make('rule_id'),
            GDT_Country::make('rule_country')->notNull(),
			GDT_Title::make('rule_title')->notNull()->label('topic'),
			GDT_Message::make('rule_description')->label('description'),

            GDT_Message::make('rule_current')->label('esl_rule_current'),
            GDT_Message::make('rule_problem')->label('esl_rule_problem'),

            GDT_Message::make('rule_government')->label('esl_rule_government'),
            GDT_Message::make('rule_mistake')->label('esl_rule_mistake'),

            GDT_Message::make('rule_suggestion')->label('esl_rule_suggestion'),
            GDT_Message::make('rule_goal')->notNull()->label('esl_rule_goal'),

            GDT_Checkbox::make('rule_edited_state')->label('esl_rule_edited_state'),
            GDT_Checkbox::make('rule_discuss_state')->label('esl_rule_discuss_state'),
            GDT_Checkbox::make('rule_vote_state')->label('esl_rule_vote_state'),
            GDT_Checkbox::make('rule_petition_state')->label('esl_rule_petition_state'),

            GDT_CreatedAt::make('rule_created'),
			GDT_CreatedBy::make('rule_creator'),
		];
	}

    public function inDiscussion(): bool { return $this->gdoValue('rule_discuss_state'); }

    public function getTitle(): string { return $this->gdoVar('rule_title'); }

    public function getMetaDescr(): string { return $this->gdoVar('rule_description_text'); }

    public function href_view(): string { return href('EdwardSnowdenLand', 'Rule', "&id={$this->getID()}"); }

    public function href_edit(): string { return href('EdwardSnowdenLand', 'RuleEdit', "&id={$this->getID()}"); }

    ##############
    ### Render ###
    ##############
    public function renderCard(): string
    {
        $card = GDT_Card::make()->gdo($this);
        $card->creatorHeader();
        $card->addFields(
            $this->gdoColumn('rule_country'),
            $this->gdoColumn('rule_title'),
            $this->gdoColumn('rule_description'),
            GDT_Divider::make(),
            $this->gdoColumn('rule_current'),
            $this->gdoColumn('rule_problem'),
            GDT_Divider::make(),
            $this->gdoColumn('rule_government'),
            $this->gdoColumn('rule_mistake'),
            GDT_Divider::make(),
            $this->gdoColumn('rule_suggestion'),
            $this->gdoColumn('rule_goal'),
        );
        return $card->renderCard();
    }


}
