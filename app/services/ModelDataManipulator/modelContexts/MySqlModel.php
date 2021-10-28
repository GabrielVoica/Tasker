<?php

namespace App\Models\ModelContexts;

use App\Models\ModelStrategy;
use Exception;
use mysqli;

include __DIR__ . "/../ModelStrategy.php";

class MySqlModel implements ModelStrategy
{
    private $connection;
    private $tableName;
    private $query;
    private $where;
    private $limit;
    private $orderBy;

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    function query($builder)
    {
        echo $builder->query . '<br>';
        return mysqli_query($this->connection, $builder->query);
    }
}

class Builder
{
    public $query;
    private $tableName;
    private $where;
    private $limit;
    private $orderBy;
    private $mainOperation;
    private $values;

    public function select($tableName)
    {
        $this->mainOperation = "SELECT * FROM " . $tableName;
        $this->tableName = $tableName;
    }

    public function insert($tableName)
    {
        $this->mainOperation = "INSERT INTO " . $tableName;
        $this->query = $this->query . $this->mainOperation;
    }

    public function values($values)
    {
        if (strpos($this->mainOperation, 'INSERT')) {
            throw new Exception('The main operation needs to be an insert if you add values');
        }
        preg_match_all('/\{[a-zA-Z0-9=\(\)\?\@\.]+\}/', $values, $matches, PREG_SET_ORDER);
        $brackets = array('{', '}');
        $values = [];

        foreach ($matches as $matches_index) {
            $result = str_replace($brackets, ' ', $matches_index[0]);
            $leftValue = null;
            preg_match_all('/[a-zA-Z0-9\']+\=/', $result, $leftValue);
            $leftValue = str_replace('=', '', $leftValue[0][0]);
            $rightValue = null;
            preg_match_all('/=[a-zA-Z0-9\'\?\@\.]+/', $result, $rightValue);
            $rightValue = str_replace('=', '', $rightValue[0][0]);
            $values[] = [$leftValue => $rightValue];
        }

        $leftQueryString = "(";
        $rightQueryString = " VALUES (";
        $count = count($values);
        $i = 0;

        foreach ($values as $value) {
            $keys = array_keys($value);
            if ($i < $count - 1) {
                $leftQueryString = $leftQueryString . $keys[0] . ',';
                $rightQueryString = $rightQueryString . $this->checkIfNumberOrString($value[$keys[0]]) . ',';
                $i++;
            } else {
                $leftQueryString = $leftQueryString . $keys[0] . ')';
                $rightQueryString = $rightQueryString . $this->checkIfNumberOrString($value[$keys[0]]) . ')';
            }
        }

        $this->values =  $leftQueryString . $rightQueryString;
    }

    public function where($conditions)
    {
        $matches = null;
        preg_match_all('/\{[a-zA-Z0-9=\?\@\.]+\}/', $conditions, $matches, PREG_SET_ORDER);
        $brackets = array('{', '}');
        $values = [];
        $queryString = "";

        foreach ($matches as $matches_index) {
            $result = str_replace($brackets, ' ', $matches_index[0]);

            $leftValue = null;
            preg_match_all('/[a-zA-Z0-9\']+\=/', $result, $leftValue);
            $leftValue = str_replace('=', '', $leftValue[0][0]);

            $rightValue = null;
            preg_match_all('/=[a-zA-Z0-9\'\?\@\.]+/', $result, $rightValue);
            $rightValue = str_replace('=', '', $rightValue[0][0]);

            $values[] = [$leftValue => $rightValue];

            $queryString = " WHERE ";

            $count = count($values);
            $i = 0;

            foreach ($values as $value) {
                $keys = array_keys($value);

                if ($i < $count - 1) {
                    $queryString = $queryString .  $keys[0]  . '=' . $this->checkIfNumberOrString($value[$keys[0]]) . ' AND ';
                    $i++;
                } else {
                    $queryString = $queryString .  $keys[0]  . '=' . $this->checkIfNumberOrString($value[$keys[0]]);
                }
            }
        }
        $this->where = $queryString;
    }


    function build()
    {
        $this->query =  $this->mainOperation . ($this->where ? $this->where : '') . ($this->values ? $this->values : '');
    }



    function checkIfNumberOrString($data)
    {
        if (preg_match('/[a-zA-Z]+/', $data)) {

            if (preg_match('/\?string$/', $data) && preg_match('/[0-9]+/', $data) && !preg_match('/[abcdefhjklmopquvwxyz]+/', $data)) {
                $data = str_replace('?string', '', $data);
                return "'" . $data . "'";
            } else {
                return "'" . $data . "'";
            }
        } else {
            return $data;
        }
    }
}
