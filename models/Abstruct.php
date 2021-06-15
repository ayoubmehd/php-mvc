<?php
class Abstruct
{

    protected $cnx;
    protected $result;
    protected $errors;
    protected $isError;

    // private static $table = "user";

    function __construct()
    {
        $this->isError = false;
        try {
            $this->cnx = new PDO($this->dsn(DB_NAME, DB_HOST), DB_USER, DB_PASS);
        } catch (PDOException $exception) {
            var_dump($exception);

            $this->isError = true;
        }
    }

    function __get($var)
    {
        return $this->$var;
    }

    private function dsn($dbname, $dbhost)
    {
        return "mysql: host=$dbhost;dbname=$dbname";
    }

    protected function buildQuery($data)
    {
        $returns = [
            "cols" => [],
            "rows" => [],
            "vals" => []
        ];

        if (is_array($data))
            foreach ($data as $key => $value) {
                $returns["cols"][] = $key;
                $returns["rows"][] = "?";
                $returns["vals"][] = $value;
            }

        return $returns;
    }

    protected function inserQuery($data)
    {

        $buildQuery = $this->buildQuery($data);

        $qruey = "INSERT INTO " . static::$table . "("
            . implode(",", $buildQuery["cols"])
            . ") VALUES("
            . implode(",", $buildQuery["rows"])
            . ")";

        return [
            "qruey" => $qruey,
            "values" => $buildQuery["vals"]
        ];
    }

    protected function updateQuery($data, $where)
    {

        $buildQuery = $this->buildQuery($data);

        $qruey = "UPDATE " . static::$table . " SET "
            . implode("=?, ", $buildQuery["cols"])
            . "=? WHERE $where";

        return [
            "qruey" => $qruey,
            "values" => $buildQuery["vals"]
        ];
    }

    protected function deleteQuery($where)
    {
        return "DELETE FROM " . static::$table . " WHERE $where";
    }

    protected function selectQuery(array $select, string $where)
    {
        $qruey = "SELECT " . implode(", ", $select) . " FROM " . static::$table . " WHERE $where";

        return $qruey;
    }

    function whereQuery($where)
    {
        extract($this->buildQuery($where));
        $qruey_string = "";
        foreach ($cols as $key => $value) {
            $qruey_string .= $value . "=?,";
        }

        $qruey_string = rtrim($qruey_string, ",");
        return [
            "qruey_string" => $qruey_string,
            "values" => $vals
        ];
    }

    public function insert($data)
    {
        $insert = $this->inserQuery($data);
        extract($insert);

        $result =  $this->cnx->prepare($qruey);
        $result->execute($values);

        $this->errors =  $result->errorInfo();
    }

    public function update($data, $where)
    {
        $where = $this->whereQuery($where);
        $update = $this->updateQuery($data, $where["qruey_string"]);
        extract($update);

        $result =  $this->cnx->prepare($qruey);
        $result->execute(array_merge($values, $where["values"]));

        $this->errors =  $result->errorInfo();
    }

    public function delete($where)
    {
        $result =  $this->cnx->prepare($this->deleteQuery($where));
        $result->execute();

        $this->errors =  $result->errorInfo();
    }

    public function select(array $select, $where, $fetchConstant = PDO::FETCH_ASSOC)
    {
        if (gettype($where) === "string") {
            $result =  $this->cnx->prepare($this->selectQuery($select, $where));
            $result->execute();
        } else if (gettype($where) === "array") {
            extract($this->whereQuery($where));
            $result =  $this->cnx->prepare($this->selectQuery($select, $qruey_string));
            $result->execute($values);
        }


        $this->result = $result->fetchAll($fetchConstant);

        $this->errors =  $result->errorInfo();
    }

    public function query($qruey, $params = [], $fetchConstant = PDO::FETCH_ASSOC)
    {
        $result = $this->cnx->prepare($qruey);
        $result->execute($params);
        $this->result = $result->fetchAll($fetchConstant);
        $this->errors =  $result->errorInfo();
    }

    public function getLastInsertId()
    {
        return $this->cnx->lastInsertId();
    }
}
