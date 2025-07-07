<?php

namespace Petros\QuickSettings;

class ValueParser
{
    /**
     * Parse value to string
     *
     * @param mixed $value
     * 
     * @return string|null
     */
    public function parse($value): string|null
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        } else if (is_bool($value) || is_numeric($value)) {
            $value = (string) $value;
        }

        return $value;
    }

    /**
     * Parse key to string
     *
     * @param string|integer|float $key
     * 
     * @return string
     */
    public function parseKey(string|int|float $key): string
    {
        return (string) $key;
    }
}
