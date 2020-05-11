<?php


namespace FormControl;

class ContestFormControl extends AbstractFormControl
{
    const MAX_CHARACTERS_TITLE = 30;
    /**
     * ContestFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyDescription($data['description'], 'description')
            ->verifyInteger($data['duration'], 'duration');
        $this->verifyContestName($data['name'], 'name', 'name')
            ->verifyIfCampusIs0($data['campus'])
            ->verifyIfEmptyUrl($data['image']);
    }

    public function verifyIfCampusIs0(int $value, string $propertyName = 'campus'): ContestFormControl
    {
        if ($value === 0) {
            $this->$propertyName = 0;
        } else {
            $this->verifySelected($value, $propertyName);
        }
        return $this;
    }

    public function verifyIfEmptyUrl(string $value, string $propertyName = 'image'): ContestFormControl
    {
        if (empty($value)) {
            $this->$propertyName = null;
        } else {
            $this->verifyUrl($value, $propertyName);
        }
        return $this;
    }

    // TODO VERIFY IF ABSTRACT CAN DO IT WITH MINOR CHANGE
    public function verifyContestName(string $value, string $propertyName, string $key): ContestFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        // Replace underscore by whitespace.
        $word = str_replace('_', ' ', $key);
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is greater than 26.
         * Check if the input is composed only of letter, number, common accent and whitespace.
         */
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
