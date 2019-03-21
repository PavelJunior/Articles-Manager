<?php

namespace models;

use core\DBDriver;
use core\Validator;

class PostModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id' => [
			'type' => 'integer',
			'primary' => true
		],

		'title' => [
			'type' => 'string',
			'minLength' => 5, // 50
			'maxLength' => 150,
			'notBlank' => true,
			'require' => true 
		],

		'preview' => [
			'type' => 'string',
			'maxLength' => 250
		],

		'text' => [
			'type' => 'string',
			'minLength' => 10, //400
			'require' => true,
			'notBlank' => true
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'articles');
		$this->validator->setRules($this->schema);
	}
}