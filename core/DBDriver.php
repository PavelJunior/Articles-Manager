<?php

namespace core;

class DBDriver
{
	const FETCH_ALL = 'all';
	const FETCH_ONE = 'one';

	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function select($sql, array $params = [], $fetch = self::FETCH_ALL)
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		if ($fetch === self::FETCH_ALL){
			return $stmt->fetchALL();
		}
		else if ($fetch === self::FETCH_ONE){
			return $stmt->fetch();
		}
		else{
			return none;
		}
	}

	public function insert($table, array $params)
	{
		$columns = sprintf('(%s)', implode(', ', array_keys($params)));
		$masks = sprintf('(:%s)', implode(', :', array_keys($params)));

		$sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $columns, $masks);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		return $this->pdo->LastInsertId();
	}

	public function update($table, array $params, $where = 'id = 24')
	{
		foreach($params as $k => $v) {
			$masks[$k] = "$k = :$k";
		}

		$masks = implode(', ', $masks);

		foreach($params as $k => $v){
			$params[$k] = "$v";
		}

		$sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $masks, $where);

		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($params);
	}

	public function delete($table, array $params, $where)
	{
		$sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		
	}
}