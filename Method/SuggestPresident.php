<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO_DBException;
use GDO\Core\GDT;
use GDO\Core\GDT_Token;
use GDO\EdwardSnowdenLand\ESL_Aspirings;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_Validator;
use GDO\Mail\Mail;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Message;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

class SuggestPresident extends \GDO\Form\MethodForm
{

    public function getMethodTitle(): string
    {
        return t('mt_esl_suggestpresident');
    }

    public function getMethodDescription(): string
    {
        return t('md_esl_suggestpresident');
    }

    protected function createForm(GDT_Form $form): void
    {
        $form->text('info_esl_add_president', [
            GDT_Link::make('link_invite')->href(href('Invite', 'Form'))->render(),
        ]);
        $user = GDT_User::make('user')->notNull()->withCompletion();
        $form->addFields(
            $user,
            GDT_Message::make('why')->notNull(),
            GDT_Validator::make()->validator($form, $user, [$this, 'validateExisting']),
            GDT_AntiCSRF::make(),
        );
        $form->actions()->addField(GDT_Submit::make());
    }

    /**
     * @throws GDO_DBException
     */
    public function validateExisting(GDT_Form $form, GDT $field, ?GDO_User $user): bool
    {
        if (!$user)
        {
            return $field->error('err_user');
        }
        if (ESL_Aspirings::table()->getWhere("esla_uid={$user->getID()} AND esla_active"))
        {
            return $field->error('err_already_aspiring');
        }
        return true;
    }

    /**
     * @throws GDO_DBException
     */
    public function formValidated(GDT_Form $form): GDT
    {
        $aspiring = ESL_Aspirings::blank([
            'esla_uid' => $form->getFormVar('user'),
            'esla_active' => '1',
        ])->softReplace();
        $aspirant = $form->getFormValue('user');
        $this->sendMails($aspirant, $form->getFormVar('why'));
        return $this->message('msg_president_suggested', [$aspirant->getName()]);
    }

    /**
     * @throws GDO_DBException
     */
    private function sendMails(GDO_User $aspirant, string $message): void
    {
        $result = GDO_User::table()->select()->where('user_deleted IS NULL')->exec();
        /**
         * @var $user GDO_User
         */
        while ($user = $result->fetchObject())
        {
            $this->sendMail($aspirant, $user, $message);
        }
    }

    private function sendMail(GDO_User $aspirant, GDO_User $user, string $message): void
    {
        $mail = new Mail();
        $mail->setSubject(tusr($user, 'mailt_new_aspirtant', [$aspirant->getName()]));
        $token = GDT_Token::generateToken("{$user->getID()}:{$aspirant->getID()}");
        $link = GDT_Link::make()->href(href('EdwardSnowdenLand', 'LikePresident', "user={$user->getID()}&aspirant={$aspirant->getID()}&token={$token}"));
        $mail->setBody(tusr($user, 'mailb_new_aspirtant', [
            $user->getName(),
            GDO_User::current()->getName(),
            $aspirant->getName(),
            $message,
            $link->renderHTML(),
            sitename(),
        ]));
        $mail->sendToUser($user);
    }


}