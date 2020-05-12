<?php


namespace FormControl;

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
            ->verifyPseudo($data['pseudo'], 'pseudo', 'pseudo')
            ->verifyPseudo($github, 'pseudoGithub', 'pseudo_github')
            ->verifySelected($data['campus'], 'campus')
            ->verifySelected($data['specialty'], 'specialty');
        if ($data['password'] != null) {
            $this->verifyPassword($data['password'], 'password');
        }
    }
}
