<?php


namespace App\Service;

class UserEditFormControl extends AbstractFormControl
{
    /**
     * UserFormControl data from $_POST.
     * @param array $data
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $github = ($data['github']!= '') ? $data['github'] : 'github';
        $this->verifyName($data['lastname'], 'lastname', 'lastname')
            ->verifyName($data['firstname'], 'firstname', 'firstname')
            ->verifyEmail($data['email'], 'email')
            ->verifyPseudo($data['joshuapseudo'], 'joshuapseudo', 'joshuapseudo')
            ->verifyPseudo($github, 'github', 'github')
            ->verifySelected($data['campus'], 'campus')
            ->verifySelected($data['specialty'], 'specialty');
        if ($data['password'] != '') {
            $this->verifyPassword($data['password'], 'password');
        }
    }

    public function getData() : array
    {
        $allData = $this->data;

        $data = [];
        $data['errors'] = $this->getErrors();
        $data['user']= [
            'firstname' => $this->getProperty('firstname'),
            'lastname' => $this->getProperty('lastname'),
            'email' => $this->getProperty('email'),
            'joshuapseudo' => $this->getProperty('joshuapseudo'),
            'github' => $this->getProperty('github'),
            'campus_id' => $this->getProperty('campus'),
            'specialty_id' => $this->getProperty('specialty')
            ];

        if ($allData['password'] != '') {
            $data['user']['password'] = $this->getProperty('password');
        }

        return $data;
    }
}
