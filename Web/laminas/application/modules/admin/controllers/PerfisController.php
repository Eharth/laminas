<?php

class Admin_PerfisController extends Zend_Controller_Action {

    private $ModelUsuarios;
    private $ModelPerfis;

    // Inicializa os menus de navegação.
    public function init() {

        $this->view->headTitle('Perfis');
        $this->view->menu_active = $this->_getParam("controller");

        $this->ModelUsuarios = new Application_Model_DbTable_Admin_Usuarios();
        $this->ModelPerfis = new Application_Model_DbTable_Admin_Perfis();
        //$this->ModelEstados = new Application_Model_DbTable_Admin_Estados();

        parent::init();
    }

    //REDIRECIONA PARA PAGINA DOS CAMPOS-----------------------------------------
    public function indexAction() {


        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        $this->view->title = "Perfis";



        $filters = array(
            "t" => $this->_getParam("t", 20),
            "p" => $this->_getParam("p", 1)
        );
        $this->view->filters = $filters;
        $this->view->perfis = $this->ModelPerfis->listar($filters);
    }

    public function inserirAction() {
        
        $this->view->title = "Inserir novo Perfil";

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        // Criação do Objeto Formulário
        $form = new Application_Form_Admin_Perfis();

        // Envio para a Camada de Visualização
        $this->view->form = $form;

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            
            $data = $form->getValues();
            
            if($this->ModelPerfis->insert($data)){
                App_Plugins_ZenMessage::createMessage("admin", "success", "Perfil cadastrado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'perfis', 'action' => 'index'));
            }
        }
    }

    public function editarAction() {


        // Criação do Objeto Formulário
        $this->view->title = "Editar novo Perfil";
        
        
        $form = new Application_Form_Admin_Perfis();
        
        $usuarios = $this->ModelPerfis->find($this->_getParam('nid'))->current();

        $form->populate($this->ModelPerfis->find($this->_getParam('nid'))->current()->toArray());

        // Envio para a Camada de Visualização
        $this->view->form = $form;
        
        $this->render('inserir');
        
   
        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            
            $data = $form->getValues();            
            if($this->ModelPerfis->update($data, "id_perfil = " . $this->_getParam('nid'))){
                App_Plugins_ZenMessage::createMessage("admin", "success", "Perfil editado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'perfis', 'action' => 'index'));
            }
        }

        

  
    }

    public function deleteAction() {
        
        $auth = Zend_Auth::getInstance()->getIdentity();
        
        $perfil = $this->ModelPerfis->find($this->_getParam('nid'))->current();
        
     
        
        if($perfil->sigla_perfil == 'adm' || $perfil->sigla_perfil == $auth->getRoleId()){
            App_Plugins_ZenMessage::createMessage("admin", "danger", "Este Perfil não pode ser excluido!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'perfis', 'action' => 'index'), null, true);
        }

        if ($this->ModelPerfis->delete('id_perfil = '. $this->_getParam('nid'))) {
            App_Plugins_ZenMessage::createMessage("admin", "success", "Perfil excluido com sucesso!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'perfis', 'action' => 'index'), null, true);
        }
    }

}
