<?php

namespace repository;

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/User.php';

use models\User; // <-- Import the User class properly

class UserRepository extends Repository
{
    public function getUserByEmail($email): ?User
    {
        $statement = $this->database->connect()->prepare(
            "SELECT * FROM public.users WHERE email = :email"
        );
        $statement->bindParam(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['email'], $user['password'], $user['name'], $user['surname']);
    }
}
