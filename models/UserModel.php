<?php

namespace models;

class UserModel extends BaseModel
{
	public function __construct(\PDO $db)
	{
		parent::__construct($db, 'users');
	}
}