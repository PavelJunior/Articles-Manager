<?php

namespace models;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelValidationException;

abstract class BaseModel
{
	protected $db;
	protected $table;
	protected $validator;

	public function __construct(DBDriver $db, Validator $validator,  $table)
	{
		$this->db = $db;
		$this->table = $table;
		$this->validator = $validator;
	}

	public function getAll()
	{
		$sql = sprintf('SELECT * FROM %s', $this->table);
		return $this->db->select($sql);
	}

	public function getById($id)
	{
		$sql = sprintf('SELECT * FROM %s WHERE id = :id', $this->table);
		return $this->db->select($sql, ['id' => $id], DBDriver::FETCH_ONE);
	}

	public function add(array $params)
	{
		$params = $this->validator->execute($params);

		if (!$this->validator->success) {
			throw new ModelValidationException($this->validator->errors);
		}

		return $this->db->insert($this->table, $params);
	}

	public function update(array $params, $where)
	{
		$params = $this->validator->execute($params);

		if (!$this->validator->success) {
			throw new ModelValidationException($this->validator->errors);
		}
		
		return $this->db->update($this->table, $params, $where);
	}
}