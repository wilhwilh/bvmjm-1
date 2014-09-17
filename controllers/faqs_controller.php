<?php
class FaqsController extends AppController {

	var $name = 'Faqs';

	function beforeFilter(){
		parent::beforeFilter();
	
		if ($this->Session->read('Auth.User.group_id') != '1'){
			$this->Session->setFlash(__('Acceso Restringido.', true));
			$this->redirect(array('controller' => 'pages', 'action' => 'home'));
		}
	}
	
	function index() {
		$this->Faq->recursive = 0;
		$this->set('faqs', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid faq', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('faq', $this->Faq->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Faq->create();
			if ($this->Faq->save($this->data)) {
				$this->Session->setFlash(__('The faq has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The faq could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid faq', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Faq->save($this->data)) {
				$this->Session->setFlash(__('The faq has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The faq could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Faq->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for faq', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Faq->delete($id)) {
			$this->Session->setFlash(__('Faq deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Faq was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
