<?php


namespace FormControl;

class IndexFormControl extends AbstractFormControl
{
    public function __construct(array $data)
    {
        $this->verifyPseudo($data['pseudo'], 'pseudo', 'pseudo')
            ->verifyPassword($data['password']);
    }
}
