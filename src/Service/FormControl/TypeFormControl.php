<?php


namespace FormControl;

class TypeFormControl extends AbstractFormControl
{
    /**
     * TypeFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyOtherName($data['type'], 'title', 'title')
            ->verifyOtherName($data['type'], 'identifier', 'identifier');
    }
}
