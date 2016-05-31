<?php


namespace App\DBManagerBundle\Connection;

/**
 * Class Connection
 * Implements the singleton pattern to avoid a lot of instances.
 * @package App\DBManagerBundle\Connection
 */
class Connection {


    /**
     * Database name
     */
    const DATABASE_NAME = "database.sqlite";

    const DATABASE_NAME_TEST = "databasetest.sqlite";

    /**
     * Contains the database connection
     * @var \SQLite3
     */
    private $dbhandle;
    /**
     * Contains the instance of the singleton
     * @var
     */
    private static $instance;

    /**
     * Contructor of the class
     */
    private function __construct($test=false) {
        if($test){
            $this->dbhandle = new \SQLite3(self::DATABASE_NAME_TEST);
        }else{
            $this->dbhandle = new \SQLite3(self::DATABASE_NAME);
        }
       if ( $this->dbhandle == false)
        {
            die ('Unable to open database');
        }
    }

    /**
     * Returns the instance
     * @return Connection
     */
    public static function getInstance($test=false) {
        if(!self::$instance) {
            if($test){
                self::$instance = new self($test);
            }else{
                self::$instance = new self();
            }
        }
        return self::$instance;
    }

    /**
     * Returns the connection
     * @return \SQLite3
     */
    public function getConnection() {
        return $this->dbhandle;
    }

    /**
     *
     */
    public function __destruct() {
        $this->dbhandle->close();
    }
} 