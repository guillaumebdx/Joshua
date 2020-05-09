<?php


namespace FormControl;

class SpecialtyFormControl extends AbstractFormControl
{
    /**
     * SpecialtyFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyName($data['specialty'], 'title', 'title')
            ->verifyName($data['specialty'], 'identifier', 'identifier');
    }
}
