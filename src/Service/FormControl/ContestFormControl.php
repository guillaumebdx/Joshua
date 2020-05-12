<?php


namespace FormControl;

class ContestFormControl extends AbstractFormControl
{
    /**
     * ContestFormControl constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->verifyDescription($data['description'], 'description')
            ->verifyInteger($data['duration'], 'duration')
            ->verifyOtherName($data['name'], 'name', 'name');
        $this->verifyIfCampusIs0($data['campus'])
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
}
