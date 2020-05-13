<?php

namespace App\Service;

class TextProcessing
{
    /**
     * @param array $array
     * @param bool|null $multidimensional
     * @return array
     */
    public static function decodeSpecialCharsInArray(array $array, bool $multidimensional = null): array
    {
        if ($multidimensional) {
            foreach ($array as $keyContest => $contest) {
                foreach ($contest as $key => $value) {
                    if (is_string($array[$keyContest][$key])) {
                        $array[$keyContest][$key] = htmlspecialchars_decode($value, ENT_QUOTES);
                        $array[$keyContest][$key] = html_entity_decode($value);
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                if (is_string($array[$key])) {
                    $array[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
                    $array[$key] = html_entity_decode($value);
                }
            }
        }

        return $array;
    }
}
