<?php


namespace FormControl;

class CampusFormControl extends AbstractFormControl
{
    /**
     * CampusFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyName($data['country'], 'country', 'country')
            ->verifyName($data['city'], 'city', 'city');
    }
}
