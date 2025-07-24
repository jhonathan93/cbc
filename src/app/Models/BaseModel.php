<?php

namespace app\Models;

use Database;
use Exception;

class BaseModel {

    /**
     * @var Database
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $columns = [];

    /***
     * @var array
     */
    protected $data = [];

    public function __construct() {
        /**
         * No primeiro momento so obterei uma instância da classe do banco, a conexão não será aberta neste momento,
         * a conexão com o banco de dados será estabelecida quando for executado um método de ação ao banco,
         * get(), insert(), update(), delete()
         */
        $this->db = Database::getInstance();
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     * @throws Exception
     * o método magico __get {https://www.php.net/manual/pt_BR/language.oop5.magic.php}
     * é acionado quando tentamos acessar uma propriedade que não existe ou inacessível,
     * logo podemos acessar a informação pela propriedade dinâmica exemplo: $club->saldo
     * de forma "magica" criar e carrega as propriedades
     */
    public function __get(string $name) {
        if (in_array($name, $this->columns)) return $this->data[$name] ?? null;

        throw new Exception("A propriedade '{$name}' não existe no modelo.");
    }

    /**
     * @param $id
     *
     * @return bool
     * @throws Exception
     */
    public function load($id): bool {
        $result = $this->db->table($this->table)->where('id', '=', $id)->get();

        if (!empty($result)) {
            $this->data = (array) $result[0];

            return true;
        }

        return false;
    }
}