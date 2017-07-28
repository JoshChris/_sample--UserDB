<?php
namespace TonicApp;

class _DB 
{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "test-app";

    private $result_id;

    function runQuery($query) 
    {
        //connect to the database
        $connection = mysqli_connect($this->server, $this->username, $this->password, $this->database);

        try {
            //run the query
            $result = $connection->query($query);

            //get the inserted id
            if (preg_match("/^INSERT /i", $query)) {
                $this->result_id = $connection->insert_id;

                if (!empty($connection->error)) {
                    throw new \Exception("Error: ".$connection->error, 1);
                }
            }

            if (empty($connection->error)) {
                return array("status" => 200);
            } else {
                return array("status" => 500, "message" => $connection->error);
            }

            /* close connection */
            $connection->close();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    function returnQuery($query) 
    {
        //connect to the database
        $connection = mysqli_connect($this->server, $this->username, $this->password, $this->database);

        try {
            //run the query
            $result = $connection->query($query);

            //get the inserted id
            if (preg_match("/^INSERT /i", $query)) {
                $this->result_id = $connection->insert_id;

                if (!empty($connection->error)) {
                    throw new \Exception("Error: ".$connection->error, 1);
                }
            }

            //populate return array
            $rows = array();
            while( $row = $result->fetch_array(MYSQLI_ASSOC)){
                $rows[] = $row;
            }
            return $rows;

            /* close connection */
            $connection->close();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}