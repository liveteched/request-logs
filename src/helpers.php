<?php

if (! function_exists('isJson')) {
    function is_json(string $string): bool
    {
        json_decode($string);
        
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
