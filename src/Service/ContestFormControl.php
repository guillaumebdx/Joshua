<?php


namespace App\Service;

class ContestFormControl extends AbstractFormControl
{
    /**
     * ContestFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyName($data['name'], 'name', 'name')
             ->verifySelected($data['campus'], 'campus')
             ->verifyDescription($data['description'], 'description')
             ->verifyUrl($data['image']);
        $this->verifyInteger($data['duration'], 'duration');
    }

    /**
     * @param int $value
     * @param string $propertyName
     * @return $this
     */
    public function verifyInteger(int $value, string $propertyName)
    {
        // Set the property in object.
        $this->$propertyName = $value;
        // Check if the input value is empty and if the input is not a integer
        if (empty($value) || !is_int($value)) {
            $this->errors['error_' . $propertyName] = 'Please indicate a valid duration.';
        }
        return $this;
    }
}
