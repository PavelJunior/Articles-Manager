<?php

use core\DBConnector;
use models\UserModel;
use models\PostModel;

function __autoload($classname) {
	include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

session_start();

define('ROOT', '//localhost/php2/8les/2/');

$uri = $_GET['php2chpu'];

$uriParts = explode('/', $uri);

$uriPartsLast = count($uriParts) - 1;
if($uriPartsLast === '')
{
	unset($uriParts[$uriPartsLast]);
	$uriPartsLast--;
}

$controller = isset($uriParts[0]) && $uriParts[0] !== '' ? $uriParts[0] : 'post';

try {
	switch ($controller) {
		case 'post':
			$controller = 'Post';
			break;
		case 'user':
			$controller = 'User';
			break;
		default:
			throw new core\Exception\ErrorNotFoundException();
			break;
	}

	$id = false;
	if(isset($uriParts[1]) && is_numeric($uriParts[1]))
	{
		$id = $uriParts[1];
		$uriParts[1] = 'one';
	}

	$action = isset($uriParts[1]) && $uriParts[1] !== '' && is_string($uriParts[1]) ? $uriParts[1] : 'index';
	$action = sprintf('%sAction', $action);

	if(!$id)
	{
		$id = isset($uriParts[2]) && is_numeric($uriParts[2]) ? $uriParts[2] : false;
	}

	if($id)
	{
		$_GET['id'] = $id;
	}

	$request = new core\Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);

	$controller = sprintf('controllers\%sController', $controller);
	$controller = new $controller($request);
	$controller->$action($id);
} catch (\Exception $e) {
	if ($e->getCode() == 404) {
		header('HTTP/1.1 404 Not Found', true, 404);
	}
	$controller = sprintf('controllers\%sController', 'Base');
	$controller = new $controller();
	$controller->errorHandler($e->getMessage(), $e->getTraceAsString());
}
$controller->render();