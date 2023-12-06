<?php

namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDO_ArgError;
use GDO\Core\GDT;
use GDO\Core\GDT_Token;
use GDO\Core\Method;
use GDO\EdwardSnowdenLand\ESL_AspirantLikes;
use GDO\EdwardSnowdenLand\ESL_Aspirings;
use GDO\Form\GDT_Validator;
use GDO\Form\MethodForm;
use GDO\Hash\GDT_Hash;
use GDO\User\GDT_User;
use GDO\Votes\Method\Like;

/**
 * Like a president via email.
 */
class LikePresident extends Method
{

    public function gdoParameters(): array
    {
        return [
            GDT_User::make('user')->notNull(),
            GDT_User::make('aspirant')->notNull(),
            GDT_Token::make('token')->notNull(),
        ];
    }

    /**
     * @throws GDO_ArgError
     */
    public function execute(): GDT
    {
        $user = $this->gdoParameterValue('user');
        $aspirant = $this->gdoParameterValue('aspirant');
        $token = GDT_Token::generateToken("{$user->getID()}:{$aspirant->getID()}");
        if ($token !== $this->gdoParameterVar('token'))
        {
            return $this->error('err_token');
        }
        return Like::make()->executeWithInputs([
            'gdo' => ESL_AspirantLikes::class,
            'id' => $aspirant->getID(),
        ]);
    }

}