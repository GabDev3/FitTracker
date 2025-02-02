<?php

namespace repository;

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/User.php';
require_once './src/utils/UserUtils.php';


use Exception;
use models\User;
use PDO;

// <-- Import the User class properly

class UserRepository extends Repository
{
    public function getUserByEmail($email): ?User
    {
        $statement = $this->database->connect()->prepare(
            "SELECT * FROM public.users us
         LEFT JOIN user_details ud ON us.id_user_details = ud.id
         WHERE email = :email"
        );
        $statement->bindParam(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['email'], $user['password'], $user['name'], $user['surname']);
    }

    public function addUser(User $user)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO user_details (name, surname, phone_number, user_role_id)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getPhone(),
            1 //1 - user role id
        ]);

        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, id_user_details)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $this->getUserDetailsId($user)
        ]);
    }

    public function getUserDetailsId(User $user): int
    {
        // Assign values to variables first
        $name = $user->getName();
        $surname = $user->getSurname();
        $phone = $user->getPhone(); // This will be used to bind to the 'phone_number' column

        // Update the query to use 'phone_number' instead of 'phone'
        $stmt = $this->database->connect()->prepare('
        SELECT * FROM public.user_details WHERE name = :name AND surname = :surname AND phone_number = :phone
    ');

        // Bind the variables to the prepared statement
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR); // Use $phone for 'phone_number'

        // Execute the query
        $stmt->execute();

        // Fetch the result and return the 'id'
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['id'];

    }

    public function getUserId(string $email): int
    {
        // Prepare the query with a parameter for the email
        $stmt = $this->database->connect()->prepare('
        SELECT * FROM public.users
        WHERE users.email = :email
    ');

        // Bind the email parameter to the prepared statement
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch the result and return the user ID
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ensure that the data exists before trying to return the id
        if ($data) {
            return $data['id'];
        } else {
            // Handle the case where no user is found (optional, could throw an exception or return a default value)
            throw new Exception("User not found for email: " . $email);
        }
    }

    public function getUserById(int $id): User
    {
        $stmt = $this->database->connect()->prepare('Select * from users where id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->database->connect()->prepare('Select * from user_details where id = :id');
        $stmt->bindParam(':id', $user["id_user_details"], PDO::PARAM_INT);
        $stmt->execute();
        $user_details = $stmt->fetch(PDO::FETCH_ASSOC);

        $userObj = new User($user['email'], $user['password'], $user_details['name'],  $user_details['surname']);
        $userObj->setPhone($user_details['phone_number']);
        return $userObj;
    }

    public function getUserRoleById(int $id): int
    {
        $stmt = $this->database->connect()->prepare('Select * FROM public.users u
JOIN public.user_details ud ON u.id_user_details = ud.id
WHERE u.id = :id');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['user_role_id'];
    }


    public function getAllUsers(): array
    {
        $stmt = $this->database->connect()->prepare('Select u.id as user_id, u.email, ud.name, ud.surname, ud.phone_number, ur.role from public.users u
    JOIN public.user_details ud on ud.id = u.id_user_details
    JOIN public.user_roles ur ON ur.id = ud.user_role_id
    WHERE u.id != :curr_user_id');

        $curr_user_id = $_SESSION["user_id"];
        $stmt->bindParam(':curr_user_id', $curr_user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUserById(int $id)
    {

        $stmt = $this->database->connect()->prepare('
        DELETE FROM public.users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

    }

}
