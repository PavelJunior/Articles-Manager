<?php

namespace controllers;

use models\PostModel;
use core\DBConnector;
use core\DBDriver;
use core\Validator;
use core\Exception\ModelValidationException;

class PostController extends BaseController
{
	public function indexAction()
	{
		$this->title = 'Список Постов';

		$mPost = new PostModel(
			new DBDriver(DBConnector::getConnect()), 
			new Validator()
		);
		$posts = $mPost->getAll();

		$this->content = $this->build(__DIR__ . '/../views/posts.html.php', ['posts' => $posts]);
	}

	public function oneAction()
	{
		$id = $this->request->get('id');

		$mPost = new PostModel(
			new DBDriver(DBConnector::getConnect()), 
			new Validator()
		);

		$post = $mPost->getById($id);

		$this->title = $post['title'];

		$this->content = $this->build(__DIR__ . '/../views/post.html.php', 
			[
				'created_at' => $post['created_at'],
				'text' => $post['text']
			]);
	}
	public function addAction()
	{
		$this->title ='Добавить статью';

		if($this->request->isPost()){
			$name = $this->request->post('name');
			$preview = $this->request->post('preview');
			$text = $this->request->post('text');

			$mPost = new PostModel(
				new DBDriver(DBConnector::getConnect()), 
				new Validator()
			);

			try {
				$id = $mPost->add([
					'title' => $this->request->post('name'),
					'preview' => $this->request->post('preview'),
					'text' => $this->request->post('text')
				]);

				$this->redirect(sprintf(ROOT . 'post/%s', $id));
			} catch (ModelValidationException $e) {
				$errors = $e->getErrors();
			}
		}

		$this->content = $this->build(__DIR__ . '/../views/add.html.php', [
			'name' => $name ?? null,
			'preview' => $preview ?? null,
			'text' => $text ?? null,
			'nameError'=> $errors['title'][0] ?? null,
			'previewError' => $errors['preview'][0] ?? null,
			'textError' => $errors['text'][0] ?? null
		]);
	}

	public function editAction($id)
	{
		$this->title = 'Изменить статью';

		$mPost = new PostModel(
			new DBDriver(DBConnector::getConnect()), 
			new Validator()
		);

		$post = $mPost->getById($id);

		$name = trim($post['title']);
		$preview = trim($post['preview']);
		$text = trim($post['text']);

		if ($this->request->isPost())
		{
			$name = $this->request->post('name');
			$preview = $this->request->post('preview');
			$text = $this->request->post('text');
			
			try {
				$mPost->update([
					'title' => $this->request->post('name'),
					'preview' => $this->request->post('preview'),
					'text' => $this->request->post('text')
				], 
				sprintf('id = %s', $id));

				$this->redirect(sprintf(ROOT . 'post/%s', $id));
			} catch (ModelValidationException $e) {
				$errors = $e->getErrors();
			}
		}

		$this->content = $this->build(__DIR__ . '/../views/edit.html.php', [
			'name' => $name ?? null,
			'preview' => $preview ?? null,
			'text' => $text ?? null,
			'nameError'=> $errors['title'][0] ?? null,
			'previewError' => $errors['preview'][0] ?? null,
			'textError' => $errors['text'][0] ?? null
		]);
	}

	public function deleteAction($id){

        $mPost = new PostModel(
            new DBDriver(DBConnector::getConnect()),
            new Validator()
        );

        $mPost->delete($id);
        $this->redirect(ROOT);
    }
}