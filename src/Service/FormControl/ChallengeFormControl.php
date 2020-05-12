<?php


namespace FormControl;

class ChallengeFormControl extends AbstractFormControl
{
    const MAX_CHARACTERS_TITLE = 30;
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
            ->verifyFlag($data['flag'], 'flag');
        $this->verifyChallengeName($data['name'], 'name', 'name');
    }

    public function verifyChallengeName(string $value, string $propertyName, string $key)
    {
        $this->$propertyName = $value;
        $word = str_replace('_', ' ', $key);
        if (empty($value)) {
            $this->errors['error_' . $key] = 'Please enter a ' . $word . ', thank you.';
        } elseif (strlen($value) > self::MAX_CHARACTERS_TITLE) {
            $this->errors['error_' . $key] = 'Must be a maximum of 35 characters. Current : ' . strlen($value);
        } elseif (preg_match('/[^-_A-Za-z0-9àâïçéèêôÀÂÏÇÉÈÔ!\s]/', $value)) {
            $this->errors['error_' . $key] = 'Some characters are prohibited.';
        }

        return $this;
    }
}
