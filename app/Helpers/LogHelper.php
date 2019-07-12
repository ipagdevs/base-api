<?php

namespace App\Helpers;

class LogHelper
{
    public function parse($data)
    {
        $json = json_decode($this->json_encode_private($data), true);

        $array = $this->array_filter_recursive($json);

        if (is_array($array)) {
            return $array;
        }

        return var_export($array, true);
    }

    private function extract_props($object)
    {
        $public = [];
        $reflection = new \ReflectionClass(get_class($object));
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            $name = $property->getName();
            if (is_array($value)) {
                $public[$name] = [];
                foreach ($value as $item) {
                    if (is_object($item)) {
                        $itemArray = $this->extract_props($item);
                        $public[$name][] = $itemArray;
                    } else {
                        $public[$name][] = $item;
                    }
                }
            } else if (is_object($value)) {
                $public[$name] = $this->extract_props($value);
            } else {
                $public[$name] = $value;
            }
        }
        return $public;
    }

    protected function json_encode_private($object)
    {
        if (is_object($object)) {
            return json_encode($this->extract_props($object));
        }

        return json_encode($object);
    }

    protected function array_filter_recursive($input)
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
