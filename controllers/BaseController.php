<?php

namespace controllers;

use core\Request;
use core\Exception\ErrorNotFoundException;

class BaseController
{
	protected $title;
	protected $content;
	protected $request;

	public function __construct(Request $request = null)
	{
		$this->title = 'PHP2';
		$this->content = '';
		$this->request = $request;

	}

	public function __call($name, $params)
	{
		throw new ErrorNotFoundException();
	}

	public function render()
	{
		echo $this->build(
			__DIR__ . '/../views/main.html.php', [
				'title'=>$this->title,
				'content'=>$this->content,
			]
		);
	}

	public function errorHandler($message, $trace)
	{
		$this->content = $message;
	}

	public function redirect($uri)
	{
		header("HTTP/1.1 410 Gone");
		header(sprintf('Location: %s', $uri));
		die();
	}

	public function build ($template, array $params = [])
	{
		ob_start();
		extract($params);
		include_once $template;

		return ob_get_clean();
	}
}