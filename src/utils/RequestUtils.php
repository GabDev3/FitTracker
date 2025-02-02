<?php

namespace utils;

class RequestUtils
{
    public static function getPostData(): array
    {
        if (count($_POST) !== 0) {
            return $_POST;
        }
        $input = file_get_contents('php://input');
        if ($input) {
            return json_decode($input, true);
        }
        return [];
    }

    public static function getGetData(): array
    {
        return $_GET;
    }

    public static function validateInput(array $requiredFields, array $passedData): void
    {
        $isValid = true;
        foreach ($requiredFields as $field) {
            $isValid = $isValid && isset($passedData[$field]);
        }

        if (!$isValid) {
            die(
                "not valid input"
            );
        }
    }


}