<?php

class Admin_LoginController extends Zend_Controller_Action {

    private $modelRecover;
    private $ModelUsuarios;
    private $ModelPerfis;
    private $Log;

    public function init() {

        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('login');

        $this->ModelUsuarios = new Application_Model_DbTable_Admin_Usuarios();
        $this->ModelPerfis = new Application_Model_DbTable_Admin_Perfis();
        $this->Log = new App_Plugins_Log();



        parent::init();
    }

    public function indexAction() {
        $this->view->headTitle('Login');

        if ($this->_helper->FlashMessenger->setNamespace('login')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        if ($this->getRequest()->isPost()) {


            $login = $_POST['email']; // o padrão do nome do campo da tabela é o nomedocampo_nomedatabela
            $pass = sha1($_POST['senha']);
            $table = "usuarios";

            if (App_Plugins_LoginUser::login($table, $login, $pass)) {
                $ip = $this->getRequest()->getServer('REMOTE_ADDR');
                $this->Log->gravar("Entrou no sistema", $ip);
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
            } else {
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
            }
        }
    }

    public function logoutAction() {
        $ip = $this->getRequest()->getServer('REMOTE_ADDR');
        $this->Log->gravar("Saiu do sistema", $ip);

        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_helper->FlashMessenger->clearMessages();
        $this->_helper->FlashMessenger->setNamespace('login')->addMessage(array('warning' => 'Você foi deslogado!'));
        return $this->_helper->redirector('index');
    }

    /**
     * Controller para recuperar senha
     * @return void
     */
    public function cadastroAction() {

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;
        
        $dadosForm = new Zend_Session_Namespace('Default');
        
       
        
        


        $form = new Application_Form_Admin_Usuarios();
        if($dadosForm->cadastro){
            $form->populate($dadosForm->cadastro);
        }
        $form->removeElement('login_usuario');
        $form->removeElement('senha_usuario');
        $form->removeElement('imagem_rodape_usuario');
        $form->removeElement('id_perfil');
        $form->removeElement('status_usuario');
        $form->getElement('submit')->setLabel(" Pedir senha");
        $form->setAction($this->view->url(array("module" => "admin", "controller" => "login", "action" => "cadastro")));
        $this->view->form = $form;


        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {

                $data = $form->getValues();
                $busca_email = $this->ModelUsuarios->email($data['email_usuario']);

                if ($busca_email->email_usuario == $data['email_usuario']) {
                    App_Plugins_ZenMessage::createMessage("login", "danger", "Este e-mail já está cadastrado em nossa base");
                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                } else {



                    if ($this->ModelPerfis->sigla('opr')) {
                        $perfil = $this->ModelPerfis->sigla('opr');
                    } else {
                        $perfil = $this->ModelPerfis->sigla('agt');
                    }

                    $data['id_perfil'] = $perfil->id_perfil;

                    if ($this->ModelUsuarios->insert($data)) {
                        App_Plugins_ZenMessage::createMessage("login", "success", "Ok, já foi cadastrado seu pedido, aguarde a confirmação e sua chave de acesso, Obrigado!");
                        return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                    }
                }
            } else {
                $message = "Foi encontrado os seguintes erros: <br />";
                foreach ($form->getMessages() as $input) {
                    foreach ($input as $callback) {
                        $message .= $callback;
                    }
                }
                if (!empty($callback)) {
                    App_Plugins_ZenMessage::createMessage("login", "danger", $message);
                    
                    $dadosForm->cadastro = $form->getValues();
                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                }
            }
        }
    }

    public function recoverAction() {

        $this->_helper->layout->disableLayout();
        $email = $this->_getParam('email');
        $recover = $this->modelRecover = new Application_Model_DbTable_Admin_Usuarios();

        // Verifica se o campo nÃ£o estÃ¡ vazio
        if (!empty($email)) {
            // Verifica se o email Ã© vÃ¡lido
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Faz a busca na base
                $user = $recover->email($email);

                if ($user) {

                    try {
                        // Gera nova senha
                        $novaSenha = App_Plugins_KeyRandon::keyrandon();

                        // Update da nova senha
                        $senha = array(
                            'senha_usuario' => sha1($novaSenha)
                        );

                        $onde = array(
                            'email_usuario = ?' => $email
                        );

                        // Atualiza no banco
                        if ($recover->update($senha, $onde)) {
                            // Enviar email
                            $assunto = utf8_decode('Nova Senha');

                            try {
                                $m = new App_Plugins_HtmlMail();
                                $m->setSubject($assunto)
                                        ->addTo($email)
                                        ->setViewParam("nome", $email)
                                        ->setViewParam("email", $email)
                                        ->setViewParam("senha", $novaSenha)
                                        ->sendHtmlTemplate("cadastro.phtml");

                                if ($m) {
                                    App_Plugins_ZenMessage::createMessage("login", "success", "Sua nova senha foi enviada para o e-mail fornecido abaixo.");
                                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                                } else {
                                    App_Plugins_ZenMessage::createMessage("login", "danger", "Não foi possível enviar o e-mail com sua nova senha, tente novamente.");
                                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                                }
                            } catch (Exception $e) {
                                App_Plugins_ZenMessage::createMessage("login", "danger", "Não foi possível enviar o e-mail com sua nova senha, tente novamente.");
                                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                            }
                        }
                    } catch (Exception $e) {
                        App_Plugins_ZenMessage::createMessage("login", "danger", "Não foi possível enviar o e-mail com sua nova senha, tente novamente.");
                        return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                    }
                } else {
                    App_Plugins_ZenMessage::createMessage("login", "danger", "E-mail fornecido não existe, tente outro.");
                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
                }
            } else {
                App_Plugins_ZenMessage::createMessage("login", "danger", "E-mail inválido.");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
            }
        } else {
            App_Plugins_ZenMessage::createMessage("login", "danger", "Campo E-mail obrigatório");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), null, true);
        }
    }

}
