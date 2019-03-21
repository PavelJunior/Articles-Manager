<?php

namespace core\Exception;

class ErrorNotFoundException extends \Exception
{
	public function __construct($message = 'Page Not Found', $code = 404)
	{
		parent::__construct($message);
	}
} 
