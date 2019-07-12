<?php

namespace App\Helpers;

class ArrayHelper
{
    public function array_filter_recursive($input)
    {
        if (!is_array($input)) {
            return;
        }

        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->array_filter_recursive($value);
            }
        }

        if (is_string($input)) {
            return array_filter($input, 'strlen');
        }

        return array_filter($input);
    }
}
