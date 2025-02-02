<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    public function __construct() {
        parent::__construct(); // Ensure the parent constructor is callable
    }

//    public function newAction() {
//        // Logic for the new action
//        $data = ['message' => 'This is the new action'];
//        $this->render('newView', $data);
//    }

    public function index() {
        $this->render('login');
    }

    public function main()
    {
        if (!isset($_SESSION['user_id'])) {
            // If user is not logged in, redirect to login page
            header("Location: /login");
            exit();
        }

        // Render the main view if the user is logged in
        $this->render('main');
    }


    public function register() {
        $this->render('register');
    }
}
?>
