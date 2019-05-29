<?php

if (!function_exists('array_filter_recursive')) {
    function array_filter_recursive($input)
    {
        if (!is_array($input)) {
            return;
        }

        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = array_filter_recursive($value);
            }
        }

        return array_filter($input, function ($value) {
            return (!empty($value));
        });
    }
}
