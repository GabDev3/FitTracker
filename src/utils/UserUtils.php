<?php

namespace utils;


use models\User;
use repository\UserRepository;

require_once 'src/models/User.php';
require_once 'src/repository/UserRepository.php';


class UserUtils
{
    public static function getCurrentUser() : User
    {
        if (!isset($_SESSION["user_id"]))
        {
         die("User is not logged in!");
        }

        return (new UserRepository())->getUserById($_SESSION["user_id"]);
    }

}