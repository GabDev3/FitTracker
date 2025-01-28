<?php

use repository\UserRepository;

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController
{

    public function login()
    {
        $userRepository = new UserRepository();
//        if ($this->isPost()) {
//            return $this->login('login');    //wypierdala błąd - prawdopodobnie infinite loop
//        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $userRepository->getUserByEmail($email);

        if(!$user) {
            return $this->render("login", ['messages' => ['User does not exist.']]);
        }

        if ($user -> getEmail() !== $email) {
            return $this -> render('login', ['messages' => ['Invalid email']]);
        }

        if ($user -> getPassword() !== $password) {
            return $this -> render('login', ['messages' => ['Invalid password']]);
        }


        return $this -> render('main');
    }

}