<?php


namespace FormControl;

class ChallengeFormControl extends AbstractFormControl
{
    /**
     * ChallengeFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyDescription($data['description'], 'description')
            ->verifySelected($data['difficulty'], 'difficulty')
            ->verifySelected($data['type'], 'type')
            ->verifyUrl($data['url'], 'url')
            ->verifyFlag($data['flag'], 'flag')
            ->verifyOtherName($data['name'], 'name', 'name');
    }
}
