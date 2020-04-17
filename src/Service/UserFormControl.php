<?php


namespace App\Service;

class UserFormControl extends AbstractFormControl
{

    public function __construct(array $data)
    {
        $this->verifyName($data['lastname'], 'lastname', 'lastname')
            ->verifyName($data['firstname'], 'firstname', 'firstname')
            ->verifyEmail($data['email'], 'email')
            ->verifyPseudo($data['joshuapseudo'], 'joshuapseudo', 'joshuapseudo')
            ->verifyPseudo($data['github'], 'github', 'github')
            ->verifyPassword($data['password'], 'password');
    }

    public function getDatas() : array
    {
        $datas = [];
        $datas['errors'] = $this->getErrors();
        $datas['user']= [
            'firstname' => $this->getProperty('firstname'),
            'lastname' => $this->getProperty('lastname'),
            'email' => $this->getProperty('email'),
            'joshuapseudo' => $this->getProperty('joshuapseudo'),
            'password' => $this->getProperty('password'),
            'github' => $this->getProperty('github'),
        ];
        return $datas;
    }
}
