<?php

namespace Veebiteenus;

use FluentPDO;

class Controller
{

    public $db;

    function __construct()
    {
        $pdo = new \PDO('mysql:host=' . DATABASE_HOSTNAME . ';dbname=' . DATABASE_DATABASE, DATABASE_USERNAME,
            DATABASE_PASSWORD);
        $this->db = new FluentPDO($pdo);
        $this->pdo = $pdo;
    }

    function get_column_names()
    {
        $q = $this->pdo->prepare("DESCRIBE tablename");
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        var_dump($table_fields);
    }

    function output(Array $data, $code = 200)
    {
        header("HTTP/1.1 $code OK");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}