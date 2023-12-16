<?php

namespace GDO\EdwardSnowdenLand;

use GDO\Comments\CommentedObject;
use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\Country\GDO_Country;
use GDO\Country\GDT_Country;
use GDO\Date\GDT_Timestamp;
use GDO\Date\Time;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Divider;
use GDO\UI\GDT_Link;
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
        if ($this->getCreator() === $user)
        {
            return false;
        }
        return $user->isMember();
    }

    public function getName(): ?string
    {
        return sprintf('%s#%s', $this->getID(), $this->getTitle());
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

            GDT_Timestamp::make('rule_disc_started')->label('esl_rule_discuss_state'),
            GDT_Timestamp::make('rule_disc_ended')->label('esl_rule_discuss_ended'),

            GDT_Timestamp::make('rule_vote_started')->label('esl_rule_vote_state'),
            GDT_Timestamp::make('rule_vote_ended')->label('esl_rule_vote_ended'),

            GDT_Timestamp::make('rule_closed')->label('esl_rule_closed'),

            GDT_Timestamp::make('rule_petition_created')->label('esl_rule_petition_created'),

            GDT_CreatedAt::make('rule_created'),
			GDT_CreatedBy::make('rule_creator'),
		];
	}

    public function inDiscussion(): bool
    {
        return ($this->gdoVar('rule_discuss_started') !== null) &&
            ($this->gdoVar('rule_discuss_ended') === null);
    }

    public function isDiscussionEnded(): bool
    {
        return $this->gdoVar('rule_discuss_ended') !== null;
    }


    public function inVotings(): bool
    {
        return ($this->gdoVar('rule_vote_started') !== null) &&
            ($this->gdoVar('rule_vote_ended') === null);
    }

    public function canBePutInVotings(): bool
    {
        if ($this->inVotings())
        {
            return false;
        }
        $duration = Module_EdwardSnowdenLand::instance()->cfgMinAgeForVoteDuration();
        return $this->getAge() >= $duration;
    }

    public function canStartDiscussion(): bool
    {
        if ($this->isDiscussionEnded())
        {
            return false;
        }
        if ($this->inDiscussion() || $this->inVotings())
        {
            return false;
        }
        return true;
    }

    public function getTitle(): string { return $this->gdoVar('rule_title'); }

    public function getCreated(): string { return $this->gdoVar('rule_created'); }

    public function getCreator(): GDO_User { return $this->gdoValue('rule_creator'); }

    public function getCountry(): GDO_Country { return $this->gdoValue('rule_country'); }

    public function getMetaDescr(): string { return $this->gdoVar('rule_description_text'); }

    public function getAge(): int { return Time::getAge($this->getCreated()); }


    public function href_view(): string { return href('EdwardSnowdenLand', 'Rule', "&id={$this->getID()}"); }

    public function href_edit(): string { return href('EdwardSnowdenLand', 'RuleEdit', "&id={$this->getID()}"); }

    ##############
    ### Render ###
    ##############
    public function renderTitle(): string { return $this->gdoVar('rule_title'); }

    public function renderCard(): string
    {
        $card = GDT_Card::make()->gdo($this);
        $card->creatorHeader();
        $card->addFields(
            $this->gdoColumn('rule_country'),
            $this->gdoColumn('rule_title'),
            $this->gdoColumn('rule_description'),
            GDT_Divider::make('esl_div_now'),
            $this->gdoColumn('rule_current'),
            $this->gdoColumn('rule_problem'),
            GDT_Divider::make('esl_div_gov'),
            $this->gdoColumn('rule_government'),
            $this->gdoColumn('rule_mistake'),
            GDT_Divider::make('esl_div_we'),
            $this->gdoColumn('rule_suggestion'),
            $this->gdoColumn('rule_goal'),
        );
        return $card->renderCard();
    }

    public function linkComment(): GDT_Link
    {
        return GDT_Link::make()->label('esl_mlink_comment')->href(url('EdwardSnowdenLand', 'RuleAddComment', "&id={$this->getID()}"));
    }


}
