<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController
{

    public function login()
    {
        $user = new User('gabrielgrzegorzak@gmail.com', '1234', 'Gabriel', 'Grzegorzak');

//        if ($this->isPost()) {
//            return $this->login('login');    //wypierdala błąd - prawdopodobnie infinite loop
//        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user -> getEmail() !== $email) {
            return $this -> render('login', ['messages' => ['Invalid email']]);
        }

        if ($user -> getPassword() !== $password) {
            return $this -> render('login', ['messages' => ['Invalid password']]);
        }


        return $this -> render('main');
    }

}