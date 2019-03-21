<?php

namespace core;

use core\Exception\ValidatorException;

class Validator
{
	const TYPE_INTEGER = 'integer';

	public $clean = [];
	public $errors = [];
	public $success = false;
	public $rules;

	public function execute(array $fields)
	{
		if (!$this->rules){
			throw new ValidatorException('Rules for validation not found');
		}

		foreach ($this->rules as $name => $rules) {
			if(!isset($fields[$name]) && isset($rules['require']) && $rules['require']) {
				$this->errors[$name][] = sprintf('Field %s is require!', $name);
			}

			if(!isset($fields[$name]) && (!isset($rules['require']) || !$rules['require'])){
				continue;
			}

			if(isset($rules['notBlank']) && $rules['notBlank'] && $this->isBlank($fields[$name])) {
					$this->errors[$name][] = sprintf('Field %s can not be blank!', $name);
			}

			if(isset($rules['type']) && !$this->isCorrectType($fields[$name], $rules['type'])) {
				$this->errors[$name][] = sprintf('Field %s must be %s type!', $name, $rules['type']);
			}

			if(isset($rules['minLength']) || isset($rules['maxLength'])) {
				if(!$this->isCorrectLength($fields[$name], $rules['minLength'] ?? null, $rules['maxLength'] ?? null)) {
					$this->errors[$name][] = sprintf(
						'Field %s must be longer than %s and shorter than %s!', 
						$name, 
						$rules['minLength'] ?? 0, 
						$rules['maxLength'] ?? 10000
					);
				}
			}

			if (empty($this->errors[$name])) {
				if (isset($rules['type']) && $rules['type'] === 'string') {
					$this->clean[$name] = htmlspecialchars($fields[$name]);
				} elseif (isset($rules['type']) && $rules['type'] === 'integer') {
					$this->clean[$name] = (int)$fields[$name];
				} else {
					$this->clean[$name] = $fields[$name];
				}
			}
		}

		if (empty($this->errors)) {
			$this->success = true;
		}

		return $this->clean;
	}

	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}

	public function isBlank($field)
	{
		$field = trim($field);

		return $field == '' || $field = null;
	}

	public function isCorrectType($field, $type)
	{
		switch ($type){
			case 'int':
			case 'integer':
				return getttype($field) == 'integer' || ctype_digit($field);
				break;
			case 'string':
				return is_string($field);
				break;
			default:
				throw new ValidatorException('incorrect type of variable in scheme');
				break;
		}
	}

	public function isCorrectLength($field, $minLength = null, $maxLength = null)
	{
		if ($maxLength && $minLength) {
			return $this->isCorrectMaxLength($field, $maxLength) && $this->isCorrectMinLength($field, $minLength);
		}
		elseif($maxLength) {
			return $this->isCorrectMaxLength($field, $maxLength);
		}
		elseif($minLength) {
			return $this->isCorrectMinLength($field, $minLength);
		}
		else {
			throw new ValidatorException('inncorect length assigned in scheme');
		}
	}

	public function isCorrectMaxLength($field, $maxLength)
	{
		return strlen($field) < $maxLength ? true : false; 
	}

	public function isCorrectMinLength($field, $minLength)
	{
		return strlen($field) > $minLength ? true : false; 
	}
}