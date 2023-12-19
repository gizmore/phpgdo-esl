<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO_ArgError;
use GDO\Core\GDO_DBException;
use GDO\Core\GDT;
use GDO\Core\GDT_Object;
use GDO\Date\Time;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Language\GDO_Language;
use GDO\Language\Trans;
use GDO\Mail\Mail;
use GDO\UI\GDT_Divider;
use GDO\UI\GDT_EditButton;
use GDO\User\GDO_User;

final class RuleEdit extends MethodForm
{

    public function gdoParameters(): array
    {
        return [
            GDT_Object::make('id')->table(ESL_Rule::table())->notNull(),
        ];
    }

    /**
     * @throws GDO_ArgError
     */
    protected function getRule(): ESL_Rule
    {
        return $this->gdoParameterValue('id');
    }

    /**
     * @throws GDO_ArgError
     */
    public function hasPermission(GDO_User $user, string &$error, array &$args): bool
    {
        if ($user->isStaff())
        {
            return true;
        }
        elseif ($user === $this->getRule()->getCreator())
        {
            return true;
        }
        $error = 'err_esl_rule_edit_perm';
        return false;
    }

    /**
     * @throws GDO_ArgError
     */
    protected function createForm(GDT_Form $form): void
    {
        $rule = $this->getRule();
        $editable = !$rule->inDiscussion();
        $form->text('esl_info_rule_edit');
        $form->addFields(
            $rule->gdoColumn('rule_country')->writeable($editable),
            $rule->gdoColumn('rule_title')->writeable($editable),
            $rule->gdoColumn('rule_description')->writeable($editable),
            GDT_Divider::make('esl_div_now'),
            $rule->gdoColumn('rule_current')->writeable($editable),
            $rule->gdoColumn('rule_problem')->writeable($editable),
            GDT_Divider::make('esl_div_gov'),
            $rule->gdoColumn('rule_government')->writeable($editable),
            $rule->gdoColumn('rule_mistake')->writeable($editable),
            GDT_Divider::make('esl_div_we'),
            $rule->gdoColumn('rule_suggestion')->writeable($editable),
            $rule->gdoColumn('rule_goal')->writeable($editable),

        );
        $form->actions()->addFields(
            GDT_EditButton::make('edit')->writeable($editable)->onclick([$this, 'onEdit']),
            GDT_EditButton::make('esl_edit_done')->label('btn_start_discussion')->writeable($editable)->onclick([$this, 'onStartDiscussion']),
            GDT_EditButton::make('esl_start_voting')->label('btn_start_voting')->writeable($rule->canBePutInVotings())->onclick([$this, 'onStartVoting']),

        );
    }

    /**
     * @throws GDO_ArgError
     * @throws GDO_DBException
     */
    public function onEdit(): GDT
    {
        $rule = $this->getRule();
        $form = $this->getForm();
        $rule->saveVars($form->getFormVars());
        return $this->message('msg_esl_rule_edited')->addField($this->renderPage());
    }

    ##################
    ### Discussion ###
    ##################

    /**
     * @throws GDO_DBException
     * @throws GDO_ArgError
     */
    public function onStartDiscussion(): GDT
    {
        $rule = $this->getRule();
        if (!($rule->canStartDiscussion()))
        {
            return $this->error('err_esl_cannot_start_discussion')->addField($this->renderPage());
        }

        $rule->saveVar('rule_discuss_state', '1');

        $count = $this->sendDiscussionMails($rule);

        return $this->message('msg_esl_discussion_started', [$count])->addField($this->renderPage());

    }

    /**
     * @throws GDO_DBException
     */
    private function sendDiscussionMails(ESL_Rule $rule): int
    {
        $count = 0;
        $users = GDO_User::table()->select()->where("user_type='member'")->exec();
        while ($user = $users->fetchObject())
        {
            $this->sendDiscussionMail($user, $rule);
            $count++;
        }
        return $count;
    }

    private function sendDiscussionMail(GDO_User $user, ESL_Rule $rule): void
    {
        $mail = Mail::botMail();
        $mail->setSubject(tusr($user, 'mails_esl_disc_started', [$rule->getName(), sitename()]));
        $args = [
            $user->renderUserName(),
            sitename(),
            $rule->getCountry()->displayNameForUser($user),
            $rule->renderTitle(),
            $rule->getCreator()->renderUserName(),
            $rule->renderCard(),
            $rule->linkComment()->render(),
        ];
        $mail->setBody(tusr($user, 'mailb_esl_disc_started', $args));
        $mail->sendToUser($user);
    }

    ####################
    ### Start Voting ###
    ####################

    /**
     * @throws GDO_DBException
     * @throws GDO_ArgError
     */
    public function onStartVoting(): GDT
    {
        $rule = $this->getRule();
        if ($rule->hasVotingsStarted() || $rule->hasVotingsEnded())
        {
            return $this->error('err_esl_voting_start');
        }

        $this->sendVotingMails($rule);

        $rule->saveVar('rule_vote_started', Time::getDate());

        return $this->message('msg_esl_voting_started')->addField($this->renderPage());
    }

    private function sendVotingMails(ESL_Rule $rule): void
    {
        $old = Trans::$ISO;
        $users = GDO_User::table()->select()->where("user_type='member'")->exec();
        while ($user = $users->fetchObject())
        {
            Trans::setISO($user->getLangISO());
            $this->sendVotingMail($user, $rule);
        }
        Trans::setISO($old);
    }

    private function sendVotingMail(GDO_User $user, ESL_Rule $rule): void
    {
        $mail = Mail::botMail();
        $mail->setSubject(t('mails_esl_voting_started', $rule->getName(), sitename()));
        $args = [
            $user->renderUserName(),
            sitename(),
            $rule->renderMail(),
            $rule->linkVoteUp(),
            $rule->linkVoteDown(),
        ];
        $mail->setBody(t('mailb_esl_voting_started', $args));
        $mail->sendToUser($user);
    }

}
