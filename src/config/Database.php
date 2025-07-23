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
     *
     * @throws Exception
     */
    public function getConnection(): ?PDO {
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
        } catch (PDOException $exception) {
            throw new Exception($exception->getMessage());
        }

        return $this->conn;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function table(string $table): self {
        $this->table = $table;
        $this->resetQuery();
        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     *
     * @return $this
     */
    public function where(string $column, string $operator, $value): self {
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
     * @return bool
     * @throws Exception
     */
    public function insert(array $data): bool {
        try {
            $this->getConnection();

            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $stmt = $this->conn->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return int
     *
     * @throws Exception
     */
    public function update(array $data): int {
        try {
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
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function get(): array {
        try {
            $this->getConnection();

            $sql = "SELECT * FROM {$this->table}";
            $sql .= $this->buildWhereClause();

            $stmt = $this->conn->prepare($sql);
            $this->bindWhereValues($stmt);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    private function buildWhereClause(): string {
        if (empty($this->whereConditions)) return '';

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
    private function bindWhereValues(PDOStatement $stmt) {
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