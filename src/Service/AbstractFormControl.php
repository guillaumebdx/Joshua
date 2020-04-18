<?php

namespace App\Service;

abstract class AbstractFormControl
{
    /**
     * Store errors.
     * @var array
     */
    protected $errors = [];

    /**
     * AbstractFormControl constructor.
     *
     * $data generally correspond to $_POST.
     * @param array $data
     */
    abstract public function __construct(array $data);

    /**
     * Return all errors in the property $errors of the actual class.
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return the requested property
     * @param string $propertyName
     * @return mixed
     */
    public function getProperty(string $propertyName)
    {
        return $this->$propertyName;
    }

    /**
     * Verify last name, first name, city, country.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'name' created in the child class.
     * @param string $propertyName
     * $key is the complement of the error key if it is multiple words used a _ . example : city, contest_name.
     * Also used to complete the error message for empty values, _ replaced with whitespace.
     * @param string $key
     *
     * @return AbstractFormControl
     */
    public function verifyName(string $value, string $propertyName, string $key): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        // Replace underscore by whitespace.
        $word = str_replace('_', ' ', $key);
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is greater than 45.
         * Check if the input is composed only of letter, number, common accent and whitespace.
         */
        if (empty($value)) {
            $this->errors['error_' . $key] = 'Please enter a ' . $word . ', thank you.';
        } elseif (strlen($value) > 45) {
            $this->errors['error_' . $key] = 'Must be a maximum of 45 characters. Current : ' . strlen($value);
        } elseif (preg_match('/[^A-Za-zàâïçéèêôÀÂÏÇÉÈÔ\s]/', $value)) {
            $this->errors['error_' . $key] = 'Special characters are prohibited.';
        }

        return $this;
    }

    /**
     * Verify pseudo, pseudo github.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'pseudo' created in the child class.
     * @param string $propertyName
     * $key is the complement of the error key if it is multiple words used a _ . example : city, contest_name.
     * Also used to complete the error message for empty values, _ replaced with whitespace.
     * @param string $key
     *
     * @return AbstractFormControl
     */
    public function verifyPseudo(string $value, string $propertyName, string $key): AbstractFormControl
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
        } elseif (strlen($value) > 26) {
            $this->errors['error_' . $key] = 'Must be a maximum of 26 characters. Current : ' . strlen($value);
        } elseif (preg_match('/[^-_A-Za-z0-9àâïçéèêôÀÂÏÇÉÈÔ]/', $value)) {
            $this->errors['error_' . $key] = 'Special characters are prohibited.';
        }

        return $this;
    }

    /**
     * Verify type, contest name, challenge name.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'pseudo' created in the child class.
     * @param string $propertyName
     * $key is the complement of the error key if it is multiple words used a _ . example : city, contest_name.
     * Also used to complete the error message for empty values, _ replaced with whitespace.
     * @param string $key
     *
     * @return AbstractFormControl
     */
    public function verifyOtherName(string $value, string $propertyName, string $key): AbstractFormControl
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
        } elseif (strlen($value) > 26) {
            $this->errors['error_' . $key] = 'Must be a maximum of 26 characters. Current : ' . strlen($value);
        } elseif (preg_match('/[^-_A-Za-z0-9àâïçéèêôÀÂÏÇÉÈÔ\s]/', $value)) {
            $this->errors['error_' . $key] = 'Special characters are prohibited.';
        }

        return $this;
    }

    /**
     * Verify email.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'email' created in the child class. Default : email.
     * @param string $propertyName
     *
     * @return AbstractFormControl
     */
    public function verifyEmail(string $value, string $propertyName = 'email'): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is greater than 80.
         * Check if the email is in a format valid.
         */
        if (empty($value)) {
            $this->errors['error_email'] = 'Please enter a valid email, thank you.';
        } elseif (strlen($value) > 80) {
            $this->errors['error_email'] = 'Must be a maximum of 80 characters. Current : ' . strlen($value);
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors['error_email'] = 'Please enter your email in a valid format. Example: john.doe@exemple.fr';
        }

        return $this;
    }

    /**
     * Verify password.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'password' created in the child class. Default : password.
     * @param string $propertyName
     *
     * @return AbstractFormControl
     */
    public function verifyPassword(string $value, string $propertyName = 'password'): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is greater than 45.
         * Check if the input is composed only of letter, number, common accent and whitespace.
         */
        $regex = '/^(?=.[0-9])(?=.[!@#$%^&{}])(?=.[a-z])(?=.[A-Z])[a-zA-Z0-9!@#$%^&*{}]{8,45}$/';
        if (empty($value)) {
            $this->errors['error_password'] = 'Please enter a  password, thank you.';
        } elseif (strlen($value) < 8) {
            $this->errors['error_password'] = 'Must be a minimum of 8 characters. Current : ' . strlen($value);
        } elseif (!preg_match($regex, $value)) {
            $errorMessage = 'Your password must match with at least 1 uppercase, 1 number and 1 special character';
            $this->errors['error_password'] = $errorMessage;
        }

        return $this;
    }

    /**
     * Verify Description.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'text' created in the child class. Default : description.
     * @param string $propertyName
     *
     * @return AbstractFormControl
     */
    public function verifyDescription(string $value, string $propertyName = 'description'): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is between 30 and 500 characters.
         * Check if the email is in a valid format.
         */
        if (empty($value)) {
            $this->errors['error_' . $propertyName] = 'Please enter a description. (Max: 500 characters)';
        } elseif (strlen($value) < 30 || strlen($value) > 500) {
            $errorMessage = 'Your description must be between 30 characters and 500 characters. Current: ';
            $this->errors['error_' . $propertyName] = $errorMessage . strlen($value);
        }

        return $this;
    }

    /**
     * Verify select or input type radio.
     *
     * $value is a string retrieved from $data['key']
     * @param integer $value
     * $propertyName is the name of the property of type 'integer' created in the child class.
     * @param string $propertyName
     *
     * @return AbstractFormControl
     */
    public function verifySelected(int $value, string $propertyName): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        // Check if the input value is empty and if the input is not a integer
        if (empty($value) || !is_int($value)) {
            $this->errors['error_' . $propertyName] = 'Please select an answer.';
        }

        return $this;
    }

    /**
     * Verify url.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'url' created in the child class. Default : url.
     * @param string $propertyName
     *
     * @return AbstractFormControl
     */
    public function verifyUrl(string $value, string $propertyName = 'url'): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is greater than 2083 characters
         * and check if the url is in a valid format.
         */
        if (empty($value)) {
            $this->errors['error_' . $propertyName] = 'Please enter a link.';
        } elseif (strlen($value) > 2083 || filter_var($value, FILTER_VALIDATE_URL) === false) {
            $this->errors['error_' . $propertyName] = 'Please enter a valid url';
        }

        return $this;
    }

    /**
     * Verify flag.
     *
     * $value is a string retrieved from $data['key']
     * @param string $value
     * $propertyName is the name of the property of type 'string' or 'url' created in the child class. Default : flag.
     * @param string $propertyName
     *
     * @return AbstractFormControl
     */
    public function verifyFlag(string $value, string $propertyName = 'flag'): AbstractFormControl
    {
        // Set the property in object.
        $this->$propertyName = $value;
        /**
         * Check if the input value is empty.
         * Check if the number of characters in the input is greater than 2083 characters
         * and check if the url is in a valid format.
         */
        if (empty($value)) {
            $this->errors['error_' . $propertyName] = 'Please enter a flag.';
        } elseif (strlen($value) > 2083) {
            $this->errors['error_' . $propertyName] = 'Please enter a valid flag';
        }

        return $this;
    }
}
