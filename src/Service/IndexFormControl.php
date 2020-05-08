<?php


namespace App\Service;

class IndexFormControl extends AbstractFormControl
{
    public function __construct(array $data)
    {
        $this->verifyPseudo($data['pseudo'], 'pseudo', 'pseudo')
            ->verifyPassword($data['password']);
    }
}
