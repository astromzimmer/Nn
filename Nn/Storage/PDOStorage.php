<?php

namespace Nn\Storage;
use \Nn;
use \Utils;
use PDO;

class PDOStorage implements StorageInterface {

	private $backup_path;
	private $dbc;
	private $errors = [];

	public function errors($err=null) {
		if(isset($err)) $this->errors[] = $err;
		return $this->errors;
	}

	private function schematics() {
		$dbtype = strtolower(Nn::settings('DB_TYPE'));
		switch ($dbtype) {
			case 'pgsql':
				return array(
					'short_text' => 'VARCHAR(128)',
					'text' => 'TEXT',
					'long_text' => 'TEXT',
					'float' => 'FLOAT',
					'integer' => 'INTEGER',
					'date' => 'DATE',
					'datetime' => 'DATETIME',
					'timestamp' => 'TIMESTAMP',
					'bool' => 'BOOLEAN'
				);
				break;
			
			default:
				return array(
					'short_text' => 'VARCHAR(128)',
					'text' => 'TEXT',
					'long_text' => 'LONGTEXT',
					'float' => 'FLOAT',
					'integer' => 'INTEGER',
					'date' => 'DATE',
					'datetime' => 'DATETIME',
					'timestamp' => 'TIMESTAMP',
					'bool' => 'BOOLEAN'
				);
				break;
		}
	}

	public function __construct($type='sqlite',$host='127.0.0.1',$port='3306',$name=null,$user=null,$password=null) {
		$this->backup_path = ROOT.DS.'db';
		if(!is_dir($this->backup_path)) {
			mkdir($this->backup_path);
		}
		switch(strtolower($type)) {
			case "sqlite" :
				try {
					$this->dbc = new PDO('sqlite:'.$this->backup_path.DS.'database.sqlite3');
				} catch(\PDOException $e) {
					return trigger_error($e);
				}
			break;
			case "mysql" :
				if(!isset($host) || !isset($port) || !isset($name) || !isset($user) || !isset($password)) {
					if(count($this->errors > 0)) return trigger_error('DB config error: '.implode(',', $this->errors));
				}
				try {
					$this->dbc = new PDO("mysql:host=$host;port=$port;dbname=$name",
						$user,
						$password,
						array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
				} catch(\PDOException $e) {
					return trigger_error($e);
				}
			break;
			case "pgsql" :
				if(!isset($host) || !isset($port) || !isset($name) || !isset($user) || !isset($password)) {
					if(Nn::settings('DB_HOST') && Nn::settings('DB_PORT') && Nn::settings('DB_NAME') && Nn::settings('DB_USER') && Nn::settings('DB_PASSWORD')) {
						$host = Nn::settings('DB_HOST');
						$port = Nn::settings('DB_PORT');
						$name = Nn::settings('DB_NAME');
						$user = Nn::settings('DB_USER');
						$password = Nn::settings('DB_PASSWORD');
					} else {
						return trigger_error('DB config error');
					}
				}
				try {
					$this->dbc = new PDO("pgsql:host=$host;port=$port;dbname=$name",
						$user,
						$password);
				} catch(\PDOException $e) {
					// die($e->getMessage());
					// Utils::throwError();
					return trigger_error($e);
				}
			break;
			default:
				return trigger_error("Please set up your database correctly.");
			break;
		}
		$this->dbc->setAttribute(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
			);
		$this->dbc->setAttribute(
				PDO::ATTR_ORACLE_NULLS,
				PDO::NULL_NATURAL
			);
	}

	public function dbc() {
		return $this->dbc;
	}

	private function tableCheck($table_name,$model) {
		return gettype($this->dbc->exec('SELECT 1 from '.$table_name)) == 'integer';
	}

	private function subset($result,$subset=null) {
		if($result != false && isset($subset)) {
			if(is_array($subset)) {
				$start = $subset[0];
				$length = $subset[1];
			} else {
				$start = 0;
				$length = $subset;
			}
			$result = array_slice($result,$start,$length);
		}
		return $result;
	}
	
	public function count($table_name,$model,$group=null,$query=null) {
		$sql = 'SELECT COUNT(*)';
		if(isset($group)) $sql .= ', '.$group;
		$sql .= ' FROM ' . $table_name;
		if(isset($query)) {
			$query = is_array($query) ? $query : array($query);
			$first = true;
			foreach($query as $key => $val) {
				# Check if first filter
				$sql .= ($first) ? ' WHERE ' : ' AND ';
				# Check if LIKE
				if(substr($key,0,1) == '*') {
					$first_val = true;
					$value_array = is_array($val) ? $val : explode(' ', $val);
					$clean_key = substr($key,1);
					$sql .= $clean_key;
					foreach($value_array as $i => $value) {
						$sql .= ($first_val) ? ' LIKE ?' : ' AND '.$clean_key.' LIKE ?';
						$value_array[$i] = '%'.$value.'%';
						$first_val = false;
					}
					$query[$key] = $value_array;
				} else {
					# Check if NOT
					if(substr($key,0,1) == '-') {
						$sql .= substr($key,1).' NOT';
					} else {
						$sql .= $key;
					}
					$sql .= (is_array($val)) ? ' IN ('.str_repeat('?,',count($val)-1).'?) ' : ' IN (?)';
				}
				$first = false;
			}
		}
		if(isset($group)) $sql .= ' GROUP BY '.$group;
		$values = Utils::flatten(array_values($query));
		$result = $this->find_by_sql($sql,array(),$table_name,$model);
		return $result;
	}

	public function find($table_name,$model,$subset=null,$order_by=null) {
		if(is_array($subset)) {
			$limit = isset($subset[0]) ? $subset[0] : false;
			$offset = isset($subset[1]) ? $subset[1] : false;
		} else {
			$limit = $subset;
		}
		$sql = "SELECT * FROM " . $table_name;
		if(isset($order_by)) {
			$sql .= ' ORDER BY '.$order_by;
		}
		if(isset($limit)) $sql .= " LIMIT ".$limit;
		if(isset($offset)) $sql .= " OFFSET ".$offset;
		$result = $this->find_by_sql($sql,array(),$table_name,$model);
		return $result;
	}

	public function find_by_attribute($table_name,$model,$query,$subset=null,$order_by=null) {
		if(is_array($subset)) {
			$limit = isset($subset[0]) ? $subset[0] : false;
			$offset = isset($subset[1]) ? $subset[1] : false;
		} else {
			$limit = $subset;
		}
		$query = is_array($query) ? $query : array($query);
		$sql = "SELECT * FROM " . $table_name;
		$first = true;
		foreach($query as $key => $val) {
			# Check if first filter
			$sql .= ($first) ? ' WHERE ' : ' AND ';
			# Check if LIKE
			if(substr($key,0,1) == '&' || substr($key,0,1) == '*') {
				$operator = (substr($key,0,1) == '&') ? 'AND' : 'OR';
				$first_val = true;
				$value_array = is_array($val) ? $val : explode(' ', $val);
				$clean_key = substr($key,1);
				$sql .= $clean_key;
				foreach($value_array as $i => $value) {
					$sql .= ($first_val) ? ' LIKE ?' : ' '.$operator.' '.$clean_key.' LIKE ?';
					$value_array[$i] = '%'.$value.'%';
					$first_val = false;
				}
				$query[$key] = $value_array;
			} else {
				# Check if NOT
				if(substr($key,0,1) == '-') {
					$sql .= substr($key,1).' NOT';
				} else {
					$sql .= $key;
				}
				$sql .= (is_array($val)) ? ' IN ('.str_repeat('?,',count($val)-1).'?) ' : ' IN (?)';
			}
			$first = false;
		}
		if(isset($order_by)) {
			$sql .= ' ORDER BY '.$order_by;
		}
		# TODO
		if(isset($limit)) $sql .= ' LIMIT '.$limit;
		if(isset($offset)) $sql .= ' OFFSET '.$offset;
		$values = Utils::flatten(array_values($query));
		$result_array = $this->find_by_sql($sql,$values,$table_name,$model);
		$result = !empty($result_array) ? $result_array : false;
		return $result;
	}
	
	public function find_by_sql($sql="",$vals=[],$table_name=null,$model=null) {
		$id = $table_name.'_'.md5($sql.implode("-",$vals));
		$cache = Nn::cache();
		if($cache->valid($id)) {
			return $cache->get($id);
		} else {
			try {
				$statement = $this->dbc->prepare($sql);
				if(!$statement) {
					trigger_error($statement->errorInfo());
				}
				$vals = (count($vals) > 0) ? $vals : null;
				$exec = $statement->execute($vals);
				$rows = (isset($model)) ? $statement->fetchALL(PDO::FETCH_CLASS,$model) : $statement->fetchALL(PDO::FETCH_ASSOC);
				$result = (count($rows > 0)) ? $rows : false;
				$cache->set($id,$result);
				return $result;
			} catch(\PDOException $e) {
				if((isset($table_name)) && ($e->getCode() == '42S02' || $e->getCode() == '42P01' || $e->getMessage() == 'SQLSTATE[HY000]: General error: 1 no such table: '.$table_name)) {
					if($this->createTable($table_name,($model::$SCHEMA))) {
						return $this->find_by_sql($sql,$vals,$table_name,$model);
					}
				}
				trigger_error($e);
			}
		}
	}

	private function properties(){
		$properties = array();
		foreach($this->db_fields as $field){
			if(property_exists($this, $field)){
				if(!isset($properties[$field])) $properties[$field] = $this->$field;
			}
		}
		return $properties;
	}
	
	private function serialised_properties(){
		$clean_properties = array();
		foreach($this->properties() as $key => $value){
			isset($this->$key) ? $clean_properties[$key] = $this->db->quote($value) : false;
		}
		return $clean_properties;
	}
	
	public function save($table_name,$obj,$stamp=true){
		Nn::cache()->flush($table_name);
		$id = $obj->attr('id');
		return ($id) ? $this->update($table_name,$obj,$stamp) : $this->create($table_name,$obj,$stamp);
	}
	
	private function create($table_name,$obj,$stamp){
		if($stamp) {
			$now = gettimeofday(true);
			$created_at = $obj->attr('created_at');
			$updated_at = $obj->attr('updated_at');
			if(!$created_at) $obj->attr('created_at',$now);
			if(!$updated_at) $obj->attr('updated_at',$obj->attr('created_at'));
		}
		$attributes = $obj->getAttributes();
		unset($attributes['id']);
		$keys = array_keys($attributes);
		$vals = array_values($attributes);
		$sql = "INSERT INTO " . $table_name . " (";
		if(Nn::settings('DB_TYPE') == 'pgsql') $sql .= "id, ";
		$sql .= join(", ", $keys);
		$sql .= ") VALUES (";
		if(Nn::settings('DB_TYPE') == 'pgsql') $sql .= "DEFAULT,";
		$i = count($attributes);
		for(;$i--;) {
		$sql .= '?';
			if($i > 0) $sql .= ',';
		}
		$sql .= ")";
		try {
			$statement = $this->dbc->prepare($sql);
			$statement->execute($vals);
			$obj->attr('id',$this->dbc->lastInsertId($table_name.'_id_seq'));
			Nn::cache()->flush($table_name);
			return true;
		} catch(\PDOException $e) {
			if($e->getCode() == '42S02' || $e->getCode() == '42P01' || $e->getMessage() == 'SQLSTATE[HY000]: General error: 1 no such table: '.$table_name) {
				if($this->createTable($table_name,($obj::$SCHEMA))) {
					return $this->create($table_name,$obj,true);
				}
			}
			print_r($keys);
			echo('<br>');
			print_r($vals);
			echo('<br>');
			print_r($sql);
			echo('<br>');
			print_r($obj);
			trigger_error("<p>Can't create object in table '".$table_name."':<p>".$e->getMessage());
		}
	}
	
	private function update($table_name,$obj,$stamp){
		if($stamp) {
			$obj->attr('updated_at',gettimeofday(true));
		}
		$attributes = $obj->getAttributes();
		$keys = array();
		$vals = array();
		foreach($attributes as $key => $val){
			# Hacky solution to SQL violation
			if($key != 'id') {
				$keys[] = "{$key}=?";
				$vals[] = $val;
			}
		}
		$sql = "UPDATE " . $table_name . " SET ";
		$sql .= join(", ", $keys);
		$sql .= " WHERE id = " . $obj->attr('id');
		try {
			$statement = $this->dbc->prepare($sql);
			$statement->execute($vals);
			Nn::cache()->flush($table_name);
			return true;
		} catch(\PDOException $e) {
			$message = $e->getMessage();
			if($e->getCode() == '42S22' || $e->getCode() == '42703') {
				// TODO: FIX! SUPER HEIKEL!
				// We find the column name at char 56 of the MySQL error message
				$column_name = substr($message,56);
				if($this->addColumn($table_name,($obj::$SCHEMA),$column_name)) {
					return $this->update($table_name,$obj);
				}
			}
			trigger_error($e);
		}
	}
	
	public function delete($table_name,$obj){
		return $this->dbc_delete($table_name,$obj);
	}
	
	private function dbc_delete($table_name,$obj){
		$vals = array($obj->attr('id'));
		$sql = "DELETE FROM " . $table_name;
		$sql .= " WHERE id = ?";
		$statement = $this->dbc->prepare($sql);
		try {
			$statement->execute($vals);
			Nn::cache()->flush($table_name);
			return true;
		} catch(\PDOException $e) {
			$error = $e->getMessage();
			return false;
		}
	}

	private function createTable($table_name,$schema) {
		$mapped_schema = array();
		$schematics = $this->schematics();
		foreach($schema as $attribute => $mapping) {
			$mapped_schema[] = $attribute.' '.$schematics[$mapping];
		}
		$dbtype = strtolower(Nn::settings('DB_TYPE'));
		$sql = "CREATE TABLE ".$table_name;
		$sql .= " (id";
		$sql .= ($dbtype == 'mysql' || $dbtype == 'sqlite') ? ' INTEGER' : '';
		$sql .= ($dbtype == 'pgsql') ? ' SERIAL' : '';
		$sql .= ' PRIMARY KEY';
		$sql .= ($dbtype == 'mysql') ? ' AUTO_INCREMENT' : '';
		$sql .= ", ";
		$sql .= join(",", $mapped_schema).")";
		try {
			$this->dbc->exec($sql);
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}

	private function addColumn($table_name,$schema,$column_name) {
		$previous_column_name = null;
		$column_mapping = null;
		$schematics = $this->schematics();
		foreach($schema as $attribute => $mapping) {
			if($column_name == $attribute) {
				$column_mapping = $schematics[$mapping];
				break;
			}
			$previous_column_name = $attribute;
		}
		$sql = "ALTER TABLE ".$table_name;
		$sql .= " ADD ".$column_name." ".$column_mapping;
		if($previous_column_name) {
			// $sql .= " AFTER ".$previous_column_name;
		}
		// die($sql);
		try {
			$this->dbc->exec($sql);
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}

	public function backup($filename=null) {
		$filename = (isset($filename)) ? $filename : time();
		switch (strtolower(DB_TYPE)) {
			case 'mysql':
				return $this->backup_mysql($filename);
				break;

			case 'sqlite':
				$file_path = $this->backup_path.DS.$filename.'.sqlite3';
				if(copy($this->backup_path.DS.'database.sqlite3',$file_path)) {
					return $file_path;
				} else {
					$this->errors('Unable to copy SQLite file');
					return false;
				}
				break;
			
			default:
				return 'No backup function implemented for your database type. Please ensure your database is backed up systematically.';
				break;
		}
	}

	private function backup_mysql($filename=null) {
		$tables = array();
		$compression = false;
		$file_path = false;
		$numtypes = array('tinyint','smallint','mediumint','int','bigint','float','double','decimal','real');

		if($compression) {
			$file_path = $this->backup_path.DS.$filename.'.sql.gz';
			if(file_exists($file_path)) unlink($file_path);
			$zp = gzopen($file_path,'a9');
		} else {
			$file_path = $this->backup_path.DS.$filename.'.sql';
			if(file_exists($file_path)) unlink($file_path);
			$handle = fopen($file_path,'a+');
		}

		if(empty($location)) {
			$get_tables = $this->dbc->query('SHOW TABLES');
			while($row = $get_tables->fetch(PDO::FETCH_NUM)) {
				$tables[] = $row[0];
			}
		} else {
			$tables = is_array($location) ? $location : explode(',', $location);
		}

		foreach($tables as $table) {
			$result = $this->dbc->query('SELECT * FROM '.$table);
			$field_count = $result->columnCount();
			$row_count = $result->rowCount();

			# Get tables
			$return = '';
			$return .= 'DROP TABLE IF EXISTS `'.$table.'`;';
			$create_table = $this->dbc->query('SHOW CREATE TABLE '.$table);
			$first_row = $create_table->fetch(PDO::FETCH_NUM);
			$if_not_exists = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $first_row[1]);
			$return .= "\n\n".$if_not_exists.";\n\n";

			if($compression) {
				gzwrite($zp, $return);
			} else {
				fwrite($handle, $return);
			}

			# Get values
			$return = '';
			if($row_count) {
				$return = 'INSERT INTO `'.$table.'` (';
				$get_columns = $this->dbc->query('SHOW COLUMNS FROM '.$table);
				$count = 0;
				$type = array();
				while ($rows = $get_columns->fetch(PDO::FETCH_NUM)) {
					if(strpos($rows[1], '(')) {
						$type[$table][] = stristr($rows[1], '(', true);
					} else {
						$type[$table][] = $rows[1];
					}
					$return .= '`'.$rows[0].'`';
					$count++;
					if($count < ($get_columns->rowCount())) {
						$return .= ', ';
					}
				}
				$return .= ')'.' VALUES';

				if($compression) {
					gzwrite($zp, $return);
				} else {
					fwrite($handle, $return);
				}
				$return = '';
			}
			$count = 0;
			while ($row = $result->fetch(PDO::FETCH_NUM)) {
				$return = "\n\t(";
				for($j=0;$j<$field_count;$j++) {
					if(isset($row[$j])) {
						# Only quote string values
						if((in_array($type[$table][$j], $numtypes)) && (!empty($row[$j]))) {
							$return .= $row[$j];
						} else {
							$return .= $this->dbc->quote($row[$j]);
						}
					} else {
						$return .= 'NULL';
					}
					if($j<($field_count-1)) {
						$return .= ',';
					}
				}
				$count++;
				if($count<($result->rowCount())) {
					$return .= '),';
				} else  {
					$return .= ');';
				}
				if($compression) {
					gzwrite($zp, $return);
				} else {
					fwrite($handle, $return);
				}
				$return = '';
			}
			$return = "\n\n-- ------------------------------------------------ \n\n";
			if($compression) {
				gzwrite($zp, $return);
			} else {
				fwrite($handle, $return);
			}
			$return = '';
		}

		$get_tables_error = $get_tables->errorInfo();
		$get_columns_error = $get_columns->errorInfo();
		$result_error = $result->errorInfo();
		echo $get_tables_error[2];
		echo $get_columns_error[2];
		echo $result_error[2];

		if($compression) {
			gzclose($zp);
		} else {
			fclose($handle);
		}
		return $file_path;
	}

	public function __sleep() {
		//
	}

	public function __wakeup() {
		$this->connect();
	}

	public function __destruct() {
		unset($this->dbc);
	}
}

?>