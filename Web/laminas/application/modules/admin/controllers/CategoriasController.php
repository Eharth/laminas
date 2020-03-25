<?php

class Admin_CategoriasController extends Zend_Controller_Action {

    private $ModelUsuarios;
    private $ModelCategorias;

    // Inicializa os menus de navegação.
    public function init() {

        $this->view->headTitle('Categorias');
        $this->view->menu_active = $this->_getParam("controller");

        $this->ModelUsuarios = new Application_Model_DbTable_Admin_Usuarios();
        $this->ModelCategorias = new Application_Model_DbTable_Admin_Categorias();
        //$this->ModelEstados = new Application_Model_DbTable_Admin_Estados();

        parent::init();
    }

    //REDIRECIONA PARA PAGINA DOS CAMPOS-----------------------------------------
    public function indexAction() {


        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        $this->view->title = "Categorias";



        $filters = array(
            "t" => $this->_getParam("t", 20),
            "p" => $this->_getParam("p", 1)
        );
        $this->view->filters = $filters;
        $this->view->categorias = $this->ModelCategorias->listar($filters);
    }

    public function inserirAction() {
        
        $this->view->title = "Inserir novo Categoria";

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        // Criação do Objeto Formulário
        $form = new Application_Form_Admin_Categorias();

        // Envio para a Camada de Visualização
        $this->view->form = $form;

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            
            $data = $form->getValues();
            
            if($this->ModelCategorias->insert($data)){
                App_Plugins_ZenMessage::createMessage("admin", "success", "Categoria cadastrado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'categorias', 'action' => 'index'));
            }
        }
    }

    public function editarAction() {


        // Criação do Objeto Formulário
        $this->view->title = "Editar novo Categoria";
        
        
        $form = new Application_Form_Admin_Categorias();
        
        $usuarios = $this->ModelCategorias->find($this->_getParam('nid'))->current();

        $form->populate($this->ModelCategorias->find($this->_getParam('nid'))->current()->toArray());

        // Envio para a Camada de Visualização
        $this->view->form = $form;
        
        $this->render('inserir');
        
   
        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            
            $data = $form->getValues();            
            if($this->ModelCategorias->update($data, "id_categoria = " . $this->_getParam('nid'))){
                App_Plugins_ZenMessage::createMessage("admin", "success", "Categoria editado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'categorias', 'action' => 'index'));
            }
        }

        

  
    }

    public function deleteAction() {
        
        $auth = Zend_Auth::getInstance()->getIdentity();
        
        $categoria = $this->ModelCategorias->find($this->_getParam('nid'))->current();
        
     
        /*
        if($categoria->sigla_categoria == 'adm' || $categoria->sigla_categoria == $auth->getRoleId()){
            App_Plugins_ZenMessage::createMessage("admin", "danger", "Este Categoria não pode ser excluido!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'categorias', 'action' => 'index'), null, true);
        }

        if ($this->ModelCategorias->delete('id_categoria = '. $this->_getParam('nid'))) {
            App_Plugins_ZenMessage::createMessage("admin", "success", "Categoria excluido com sucesso!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'categorias', 'action' => 'index'), null, true);
        }*/
		/*
			*modificado por Robson
			*10/09/15
			*tabela Templates possui foreign key -> id_categoria com NO ACTION
			*não deleta, altera campo excluido -> 1
		
		*/
		$data = array(
			'excluido' => '1'			
		);
		if($this->ModelCategorias->update($data, "id_categoria = " . $this->_getParam('nid'))){
			App_Plugins_ZenMessage::createMessage("admin", "success", "Categoria removida com sucesso");
			return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'categorias', 'action' => 'index'));
		}
    }

}
