<?php

class Admin_TemplatesController extends Zend_Controller_Action {

    private $ModelTemplates;

    // Inicializa os menus de navegação.
    public function init() {

        $this->view->headTitle('Templates');
        $this->view->menu_active = $this->_getParam("controller");

        $this->ModelTemplates = new Application_Model_DbTable_Admin_Templates();
        $this->ModelCategorias = new Application_Model_DbTable_Admin_Categorias();

        parent::init();
    }

    public function indexAction() {


        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;
        

        $this->view->title = "Templates";
        $auth = Zend_Auth::getInstance()->getIdentity();
        $this->view->idUsuario = $auth->getUserID();
        $this->view->perfil = $auth->getRoleId();

        $filters = array(
            "t" => $this->_getParam("t", 30),
            "p" => $this->_getParam("p", 1),
            "cat" => $this->_getParam("cat", false),
            "buscar" => $this->_getParam("buscar", false)
            
        );
        if ($auth->getRoleId() != "adm") {
            $filters["date"] = date('Y-m-d');
            $filters["status"] = "1";
        }
        $this->view->filters = $filters;
        $this->view->templates = $this->ModelTemplates->listar($filters);
        $this->view->categorias = $this->ModelCategorias->fetchAll();
    }

    public function inserirAction() {
        
        $this->view->title = "Inserir novo Template";

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        // Criação do Objeto Formulário
        $form = new Application_Form_Admin_Templates();

        // Envio para a Camada de Visualização
        $this->view->form = $form;

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {            
            $data = $form->getValues();            
            $data['datavalid_template'] = App_Plugins_Data::datadb($data['datavalid_template']);
            $file = App_Plugins_FileUpload::upload(APPLICATION_PATH . "/../public/uploads/");
            
            if($file){
                $data['imagem_template'] = $file;
            }
            
            if($this->ModelTemplates->insert($data)){
                App_Plugins_ZenMessage::createMessage("admin", "success", "Template cadastrado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'templates', 'action' => 'index'));
            }
        }
    }

    public function editarAction() {


        // Criação do Objeto Formulário
        $form = new Application_Form_Admin_Templates();        
        $templates = $this->ModelTemplates->find($this->_getParam('nid'))->current();
        $form->populate($this->ModelTemplates->find($this->_getParam('nid'))->current()->toArray());
        
        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;
        $this->view->title = "Editar Template";

        // Envio para a Camada de Visualização
        $this->view->form = $form;
        
        $this->render('inserir');
        
        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
               
            $data = $form->getValues();  
            $data['datavalid_template'] = App_Plugins_Data::datadb($data['datavalid_template']);
            
            $file = App_Plugins_FileUpload::upload(APPLICATION_PATH . "/../public/uploads/");
            
            if($file){
                $data['imagem_template'] = $file;
            } else {
                $data['imagem_template'] = $templates->imagem_template;
            }
			
            $data['dataedit_template'] = date("y-m-d");
			
            if($this->ModelTemplates->update($data, "id_template = " . $this->_getParam('nid'))){
                App_Plugins_ZenMessage::createMessage("admin", "success", "Template editado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'templates', 'action' => 'index'));
            }
        } else {
            $message = "Foi encontrado os seguintes erros: <br />";
            foreach($form->getMessages() as $input){
                foreach($input as $callback){
                    $message .= $callback;
                }
            }
            if(!empty($callback)){
                App_Plugins_ZenMessage::createMessage("admin", "danger", $message);
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'templates', 'action' => 'editar', 'nid' => $this->_getParam('nid')));
            }
            
           
        }

    }

    public function deleteAction() {
        
        $auth = Zend_Auth::getInstance()->getIdentity();
        $this->view->idUsuario = $auth->getUserID();
    

        if ($this->ModelTemplates->update(array('excluido' => 1),'id_template = '. $this->_getParam('nid'))) {
            App_Plugins_ZenMessage::createMessage("admin", "success", "Template excluido com sucesso!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'templates', 'action' => 'index'), null, true);
        }
    }

}
