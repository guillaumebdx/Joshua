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
        $this->verifyOtherName($data['name'], 'name', 'name')
             ->verifyDescription($data['description'], 'description')
             ->verifyUrl($data['image']);
        $this->verifyInteger($data['duration'], 'duration')
             ->verifyIfCampusIs0($data['campus']);
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

    public function verifyIfCampusIs0(int $value, string $propertyName = 'campus')
    {
        if ($value === 0) {
            $this->$propertyName = 0;
        } else {
            $this->verifySelected($value, $propertyName);
        }
    }
}
