<?php


namespace FormControl;

class UserFormControl extends AbstractFormControl
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
            ->verifyPseudo($data['pseudo'], 'pseudo', 'pseudo')
            ->verifyPseudo($github, 'pseudoGithub', 'pseudo_github')
            ->verifyPassword($data['password'], 'password')
            ->verifySelected($data['campus'], 'campus')
            ->verifySelected($data['specialty'], 'specialty');
    }
}
