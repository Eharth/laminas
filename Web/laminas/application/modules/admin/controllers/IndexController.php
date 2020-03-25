<?php

class Admin_IndexController extends Zend_Controller_Action {

    private $ModelUsuarios;
    private $ModelTemplates;
    private $ModelPerfis;
    private $ModelLogs;

    public function init() {

        $this->view->headTitle('admin');

        $this->ModelUsuarios = new Application_Model_DbTable_Admin_Usuarios();
        $this->ModelTemplates = new Application_Model_DbTable_Admin_Templates();
        $this->ModelLogs = new Application_Model_DbTable_Admin_Log();

        parent::init();
    }

    //REDIRECIONA PARA PAGINA DOS CAMPOS-----------------------------------------
    public function indexAction() {
        //----------------- MENSAGENS

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        $this->view->menu_active = $this->_getParam("controller");
        
        $auth = Zend_Auth::getInstance()->getIdentity();

        if (!$auth->getUserID()) {
            $this->_helper->FlashMessenger->setNamespace('login')->addMessage(array('warning' => 'Você foi deslogado!'));
            return $this->_helper->redirector('index');
        }

        
        $this->view->idUsuario = $auth->getUserID();
        $this->view->perfil = $auth->getRoleId();

        $this->view->usuarios = $this->ModelUsuarios->listar(array("limit" => 5));
        if ($auth->getRoleId() != "adm") {
            $tFilters = array(
                "limit" => 5,
                "status" => "1",
                "date" => date('Y-m-d')
            );
        } else {
            $tFilters = array(
                "limit" => 5
            );
        }
        $this->view->templates = $this->ModelTemplates->listar($tFilters);
        $this->view->logs = $this->ModelLogs->listar(array("limit" => 5));
        $this->view->novos_usuarios = $this->ModelUsuarios->usuarios_novos();

        if ($auth->getRoleId() != "adm") {
            $this->view->title = "Editar meu perfil";
            $form = new Application_Form_Admin_Usuarios();
            $form->populate($this->ModelUsuarios->find($auth->getUserID())->current()->toArray());
            $form->removeElement("id_perfil");
            $form->removeElement("status_usuario");
            $this->view->form = $form;
            $usuarios = $this->ModelUsuarios->find($auth->getUserID())->current();


            if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
                $data = $form->getValues();
                if ($usuarios->senha_usuario == $data['senha_usuario']) {
                    
                } else {
                    $data['senha_usuario'] = sha1($data['senha_usuario']);
                }
                $file = App_Plugins_FileUpload::upload(APPLICATION_PATH . "/../public/uploads/");
                if ($file) {
                    $data['imagem_rodape_usuario'] = $file;
                } else {
                    $data['imagem_rodape_usuario'] = $usuarios->imagem_rodape_usuario;
                }
                if ($this->ModelUsuarios->update($data, "id_usuario = " . $auth->getUserID())) {
                    App_Plugins_ZenMessage::createMessage("admin", "success", "Usuário editado com sucesso");
                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
                } else {
                    App_Plugins_ZenMessage::createMessage("admin", "warning", "Nenhuma informação alterada");
                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
                }
            }
        } else {
            $this->view->title = "Últimos usuarios cadastrados";
        }
    }

    public function logAction() {

        $this->view->title = "Logs";

        $filters = array(
            "t" => $this->_getParam("t", 20),
            "p" => $this->_getParam("p", 1)
        );
        $this->view->filters = $filters;
        $this->view->logs = $this->ModelLogs->listar($filters);
    }

    public function headerAction() {
        $auth = Zend_Auth::getInstance()->getIdentity();
        $idUsuario = $auth->getUserID();
        $perfil = $auth->getRoleId();
        $this->view->perfil = $perfil;
        $this->view->usuario = $this->ModelUsuarios->buscar($idUsuario, $perfil);
    }

    public function footerAction() {
        
    }

}
