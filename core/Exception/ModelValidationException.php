<?php

namespace core\Exception;

class ModelValidationException extends \Exception
{
	public function __construct($errors)
	{
		parent::__construct('');
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}
} 