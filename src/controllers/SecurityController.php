<?php

use models\User;
use repository\UserRepository;
use utils\RequestUtils;

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController
{

    private $userRepository;


    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }


    public function deleteUser()
    {
        header('Content-Type: application/json'); // Ensure JSON response

        if (!$this->isPost()) {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
            exit;
        }

        $data = RequestUtils::getPostData();

        // Validate input
        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            echo json_encode(["success" => false, "message" => "Invalid user ID."]);
            exit;
        }

        $userId = (int)$data['user_id'];

        try {
            // Call the repository method to delete the user
            $this->userRepository->deleteUserById($userId);

            // Respond with success message
            echo json_encode(["success" => true, "message" => "User deleted successfully."]);
        } catch (Exception $e) {
            // Handle errors
            echo json_encode(["success" => false, "message" => "Error deleting user: " . $e->getMessage()]);
        }
        exit;
    }


    public function login()
    {
        if (!$this->isPost()) {
            return $this->render("login");
        }

        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            return $this->render("login", ['messages' => ['Please provide email and password.']]);
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render("login", ['messages' => ['User does not exist.']]);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['Invalid email']]);
        }

        // Verify password with stored hash
        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Invalid password']]);
        }

        $userId = $this->userRepository->getUserId($email);
        $userRole = $this->userRepository->getUserRoleById($userId); // Assuming this function exists


        // Set session user ID to track the logged-in user
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $userRole; // Save role in session


        return $this->render('main');
    }


    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $phone = $_POST['phone'];

        if ($password !== $confirmedPassword) {
            return $this->render('register', ['messages' => ['Please provide proper password']]);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('register', ['messages' => ['Invalid email format']]);
        }


        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User($email, $passwordHash, $name, $surname);
        $user->setPhone($phone);

        $this->userRepository->addUser($user);

        return $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);
    }

    public function logout(): void
    {

        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        // Redirect the user to the login page
        header("Location: /login");
        exit;
    }

    public function manageUsers()
    {
        $users = $this->userRepository->getAllUsers();
        $this->render('manageUsers', ['users' => $users]);
    }


}