<?php

/**
 * Mysql database interaction class
 * 
 * @package   UseSQL
 * @author    Keevitaja
 * @version     Version 1.3
 * @link      http://usesql.keevitaja.com
 *
 * @param $_link               Mysql link identifier or TRUE on success, FALSE on failure
 * @param str $query          Mysql query string
 * @param $result             Mysql resource or bool
 * @param bool $errors        Disables errors if set false
 * @param bool $exceptions		Disables exceptions if set false
 * 
 */
class UseSQL {

	protected $_link;
	public $query;
	public $stm;
	public $result;
	public $errors = true;
	public $exceptions = true;

	/**
	 * @desc Connects to the mysql host
	 *
	 * @param array $data           Hostname, user, password
	 * @returns object $this
	 *
	 */
	public function __construct() {
		global $link;
		$this->_link = $link;

		if (!$this->_link) {
			$this->_error("Não existe link associado");
		}
		return $this;
	}


	/**
	 * @desc Sends unique mysql query
	 *
	 * @param str $query
	 * @returns object $this
	 *
	 */
	public function query($query = false, $arguments = array()) {
		if ($query) {
			$this->query = $query;
		}
		$this->_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->_link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		try{
			$this->stm = @$this->_link->prepare($this->query);
			$this->result = @$this->stm->execute($arguments);
			if(!$this->result){
				throw new Exception('Não foi possível executar a query ' . $this->query);
			}
		}catch(Exception $e){
			throw new Exception($this->query, 0, new Exception($e->getMessage()));        	
		}

		return $this->result;
	}

	/**
	 * @desc Error managment - decides if errors and exceptions will be used
	 *
	 * @param str $message          Message thrown with exception
	 *
	 */
	protected function _error($message = '') {
		if ($this->errors === true) {
			if ($this->exceptions === true) {
				throw new Exception($message);
			} else {
				echo mysql_error();
			}
		}
	}

	/**
	 * @desc Creates new table
	 *
	 * @param str $table              Name of the new table
	 * @param array $data             Columns of the new table
	 * @return bool true OR false
	 *
	 */
	public function create_table($table, $data) {
		$query = "CREATE TABLE " . $table . " (" . join(', ', $data) . ")";

		return $this->query($query)->result;
	}

	/**
	 * @desc Clears the cache $query and $result
	 *
	 * @return object $this
	 *
	 */
	public function clear() {
		unset($this->query);
		unset($this->result);

		return $this;
	}

	/**
	 * @desc Inserts on row into the table
	 *
	 * @param str $table                Name of the table
	 * @param array $data               Array key is the name of the column
	 * @return method $this->query()
	 *
	 */
	public function insert_row($table, $data) {
		return $this->insert_rows($table, array($data));
	}

	/**
	 * @desc Updates a row in table
	 *
	 * @param str $table
	 * @param array $where
	 * @param array data
	 * @return method query()
	 *
	 */
	public function update_row($table, $data, $where = array()) {

		if(count($data) == 0)
			return; 
		
		$sql = $this->get_update_row_query($table, $data, $where);
		return $this->query($sql['sql'], $sql['params']);
	}
	
	public function get_update_row_query($table, $data, $where = array()){
		$data = (array) $data;
		if(count($data) == 0)
			return;
		 
		$query = "UPDATE " . $table . "\n SET ";
		$params = array();
		foreach ($data as $name=>$value){
			$key = ':' . $name;
			$params[$key] = $value;
			$query .= "\n\t".$name . "=" . $key . ", ";
		}
		$query = substr($query, 0, strlen($query) - 2);
		$query .= " ";
		 
		if(count($where) > 0){
			$key_where = array_keys((array)$where);
			$key_where = array_shift($key_where);
			$params[":" . $key_where] = array_shift($where);
		
			$query .=  " WHERE " . $key_where . "=:" . $key_where;
		}
		return array('sql' => $query, 'params' => $params);
	}
	
	public function delete_row($table, $where) {
		
		$key = array_keys((array)$where);
		$key = array_shift($key);
		
		$query = "DELETE FROM " . $table . " \nWHERE " .
		$key . "=:" .$key;
	
		$params = array(':'.$key => array_shift($where));
		
		return $this->query($query, $params);
	}


	/**
	 * @desc Gets rows from table by mysql query string
	 *
	 * @param str $query
	 * @return array $rows
	 *
	 */
	public function get_rows($query = false, $params = array(), $fetchType = PDO::FETCH_ASSOC ) {
		
		if($query){
			$this->query = $query;
		}
		$this->query($this->query, $params);
		return $this->stm->fetchAll($fetchType);
	}

	/**
	 * @desc Gets 1 row from table by mysql query string
	 *
	 * @param str $query
	 * @return method fetch_assoc()
	 *
	 */
	public function get_row($query = false, $params = array(), $fetchType = PDO::FETCH_ASSOC) {
		$this->query($query, $params);
		$line = $this->stm->fetch($fetchType);
		$this->stm->closeCursor();
		return $line;
	}

	/**
	 * @desc Gets first variable from row by mysql query string
	 *
	 * @param str $query
	 * @return varible
	 *
	 */
	public function get_var($query = false) {
		return array_shift($this->get_row($query));
	}

	/**
	 * @desc Gets the last mysql inserted id
	 *
	 * @return function mysql_insert_id()
	 *
	 */
	public function getLastInsertedId($column = 'id') {
		return $this->_link->lastInsertId($column);
	}


	/**
	 * @desc Close mysql conntection
	 *
	 */
	public function close() {
		@$this->_link->close();
	}
	
	/**
	 * 
	 * @param string $table
	 * @param array[][Object] $data
	 */
	public function insert_rows($table, $data){
		
		if(count($data) == 0)
			return ;
		
		$sql = $this->get_insert_row_query($table, $data);
		return $this->query($sql['sql'], $sql['params']);
	}
	
	public function get_insert_row_query($table, $data){
		
		if(count($data) == 0)
			return ;

		$i = 0;
		$params = array();
		$sql = "INSERT INTO " . $table . " (" . join(', ', array_keys((array)$data[0])) . ") VALUES \n";
		foreach ($data as $row){
			
			$array = (array) $row;
			$sql .= "(";
			foreach ($array as $column => $value){
				$key = ":" . $column . "_" . ($i);
				$params[$key] = $value;
				$sql .= $key . ", ";
			}
			$sql = substr($sql, 0, strlen($sql)-2);
			$sql .= "), ";
			$i++;
		}
		return array('sql'=>substr($sql, 0, strlen($sql)-2), 'params'=>$params);
	}

}
