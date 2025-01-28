<?php

namespace repository;
use Database;

require_once __DIR__.'/../../Database.php';

class Repository
{
    protected $database;


    //TODO zaimplementować singleton
    public function __construct()
    {
        $this->database = new Database();

    }

}