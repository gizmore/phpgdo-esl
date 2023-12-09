<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDT;
use GDO\Country\GDT_Country;
use GDO\EdwardSnowdenLand\GDT_CountryExt;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;

final class SetCountry extends MethodForm
{

    public function isUserRequired(): bool { return false; }

    public function isGuestAllowed(): bool { return true; }

    protected function createForm(GDT_Form $form): void
    {
        $form->slim();
        $form->titleNone();
        $form->addFields(
            GDT_Country::make('esl_country'),
        );
        $form->actions()->addField(GDT_Submit::make());
    }

    public function formValidated(GDT_Form $form): GDT
    {
    }

}
