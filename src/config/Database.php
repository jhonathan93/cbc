<?php
class Database {

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $db_name;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var PDO
     */
    public $conn;

    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $whereConditions = [];

    /**
     * @var array
     */
    private $data = [];

    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_DATABASE'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    /**
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );

            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    /**
     * @param $table
     *
     * @return $this
     */
    public function table($table) {
        $this->table = $table;
        $this->resetQuery();
        return $this;
    }

    /**
     * @param $column
     * @param $operator
     * @param $value
     *
     * @return $this
     */
    public function where($column, $operator, $value) {
        $this->whereConditions[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }

    /**
     * @param array $data
     *
     * @return false|string
     */
    public function insert(array $data) {
        $this->getConnection();

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function update(array $data) {
        $this->getConnection();

        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$this->table} SET $setClause";
        $sql .= $this->buildWhereClause();

        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $this->bindWhereValues($stmt);

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @return array
     */
    public function get() {
        $this->getConnection();

        $sql = "SELECT * FROM {$this->table}";
        $sql .= $this->buildWhereClause();

        $stmt = $this->conn->prepare($sql);
        $this->bindWhereValues($stmt);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @return mixed|null
     */
    public function first() {
        $this->getConnection();

        $sql = "SELECT * FROM {$this->table}";
        $sql .= $this->buildWhereClause();
        $sql .= " LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $this->bindWhereValues($stmt);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    /**
     * @return int
     */
    public function delete() {
        $this->getConnection();

        $sql = "DELETE FROM {$this->table}";
        $sql .= $this->buildWhereClause();

        $stmt = $this->conn->prepare($sql);
        $this->bindWhereValues($stmt);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * @return string
     */
    private function buildWhereClause() {
        if (empty($this->whereConditions)) {
            return '';
        }

        $whereParts = [];
        foreach ($this->whereConditions as $index => $condition) {
            $whereParts[] = "{$condition['column']} {$condition['operator']} :where_$index";
        }

        return ' WHERE ' . implode(' AND ', $whereParts);
    }

    /**
     * @param PDOStatement $stmt
     *
     * @return void
     */
    private function bindWhereValues($stmt) {
        foreach ($this->whereConditions as $index => $condition) {
            $stmt->bindValue(":where_$index", $condition['value']);
        }
    }

    /**
     * @return void
     */
    private function resetQuery() {
        $this->whereConditions = [];
        $this->data = [];
    }
}