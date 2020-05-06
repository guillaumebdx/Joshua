<?php


namespace App\Service;

class TypeFormControl extends AbstractFormControl
{
    /**
     * TypeFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyName($data['type'], 'title', 'title')
            ->verifyName($data['type'], 'identifier', 'identifier');
    }
}
