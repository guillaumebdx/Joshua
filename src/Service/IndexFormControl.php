<?php


namespace App\Service;

class IndexFormControl extends AbstractFormControl
{
    public function __construct(array $data)
    {
        $this->verifyEmail($data['email'])
            ->verifyPassword($data['password']);
    }
}
