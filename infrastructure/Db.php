<?php
namespace infrastructure;
class Db implements \interfaces\DbInterface{
	public $connection;

	private $config = [
			'driver' => 'mysql',
			'host' => 'localhost',
			'login' => 'mysql',
			'password' => 'mysql',
			'database' => 'proofix_db',
			'charset' => 'utf8'
	];
	use \traits\Tsingletone;
	
	private function getConnection() {
        if (is_null($this->connection)) {
            $this->connection = new \PDO($this->prepareDsnString(),
                $this->config['login'],
                $this->config['password']
                );
        }
        $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $this->connection;
    }

    private function prepareDsnString() {

        return sprintf("%s:host=%s;dbname=%s;charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset']
        );
    }
// "SELECT * FROM products WHERE id = :id", ["id", 1]
    public function query($sql, $params) {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function queryObject($sql, $params, $class) {
        $stmt = $this->query($sql, $params);
        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
        return $stmt->fetch();
    }

    public function execute($sql, $params) {
        $this->query($sql, $params);
        return true;
    }


    public function queryOne($sql, $params) {
			// var_dump($sql);
			// var_dump($params);
        return $this->queryAll($sql, $params)[0];
    }

    public function queryAll($sql, $params = []) {

        return $this->query($sql, $params)->fetchAll();
    }
    public function __toString()
    {
        return "Db";
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}