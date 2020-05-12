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
        $this->verifyOtherName($data['specialty'], 'title', 'title')
            ->verifyOtherName($data['specialty'], 'identifier', 'identifier');
    }
}
