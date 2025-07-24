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

    /**
     * @var Database|null
     */
    private static $instance = null;

    /**
     * @var bool
     */
    private $inTransaction = false;


    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_DATABASE'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    /**
     * @return Database
     */
    public static function getInstance(): Database {
        if (self::$instance === null) self::$instance = new self();

        return self::$instance;
    }

    /**
     * @return PDO|null
     * @throws Exception
     */
    public function getConnection(): PDO {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username,
                    $this->password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                    ]
                );
            } catch (PDOException $exception) {
                throw new Exception("Connection failed: " . $exception->getMessage());
            }
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

            $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
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
     *
     * @return bool
     * @throws Exception
     */
    public function update(array $data): bool {
        try {
            $this->getConnection();

            $setParts = [];
            foreach ($data as $key => $value) {
                $setParts[] = "$key = :$key";
            }

            $setClause = implode(', ', $setParts);
            $sql = "UPDATE $this->table SET $setClause";
            $sql .= $this->buildWhereClause();
            $stmt = $this->conn->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $this->bindWhereValues($stmt);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get(): array {
        try {
            $this->getConnection();

            $sql = "SELECT * FROM $this->table";
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
     * @return bool
     * @throws Exception
     */
    public function beginTransaction(): bool {
        try {
            $this->inTransaction = $this->conn->beginTransaction();

            return $this->inTransaction;
        } catch (Exception $e) {
            throw new Exception("Falha ao iniciar transação: " . $e->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function commit(): bool {
        try {
            if ($this->inTransaction) {
                $result = $this->conn->commit();
                $this->inTransaction = false;

                return $result;
            }
            throw new Exception("Nenhuma transação ativa para commit");
        } catch (Exception $e) {
            $this->inTransaction = false;
            throw new Exception("Falha ao confirmar transação: " . $e->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function rollBack(): bool {
        try {
            if ($this->inTransaction) {
                $result = $this->conn->rollBack();
                $this->inTransaction = false;

                return $result;
            }
            throw new Exception("Nenhuma transação ativa para rollback");
        } catch (Exception $e) {
            $this->inTransaction = false;
            throw new Exception("Falha ao reverter transação: " . $e->getMessage());
        }
    }

    /**
     * @param callable $callback
     *
     * @return mixed
     * @throws Exception
     */
    public function transaction(callable $callback) {
        $this->beginTransaction();

        try {
            $result = $callback();
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
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