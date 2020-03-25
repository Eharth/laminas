<?php

class Admin_AdministradoresController extends Zend_Controller_Action {

    private $ModelUsuarios;
    private $ModelPerfis;

    // Inicializa os menus de navegação.
    public function init() {

        $this->view->headTitle('Usuários');
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

        $this->view->title = "Usuarios";

        $filters = array(
            "t" => $this->_getParam("t", 30),
            "p" => $this->_getParam("p", 1),
            "filtro" => $this->_getParam("filtro", false),
            "buscar" => $this->_getParam("buscar", false)
        );
        $this->view->filters = $filters;
        $this->view->usuarios = $this->ModelUsuarios->listar($filters);
    }

    public function validarAction() {

        $usuario = $this->ModelUsuarios->find($this->_getParam('nid'))->current();

        if (count($usuario) > 0) {
            $data['status_usuario'] = 1;
            $senha = App_Plugins_KeyRandon::keyrandon();
            $data['senha_usuario'] = sha1($senha);
            $assunto = utf8_decode('Chave de acesso BWT-Laminas');
   
			/*
			04/11/15 - bug, aparentemente foi removido arquivos...
			correção de urgencia....
			
			$m = new App_Plugins_HtmlMail();
            $m->setSubject($assunto)
                    ->addTo($usuario->email_usuario)
                    ->setViewParam("nome", $usuario->nome_usuario)
                    ->setViewParam("email", $usuario->email_usuario)
                    ->setViewParam("senha", $senha)
                    ->sendHtmlTemplate("cadastro.phtml");

            if ($m) {

                $this->ModelUsuarios->update($data, "id_usuario =" . $this->_getParam('nid'));
                App_Plugins_ZenMessage::createMessage("admin", "success", "Senha enviada por email");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
            } else {
                App_Plugins_ZenMessage::createMessage("admin", "warning", "Problema para enviar a senha");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
            }*/
			 
			 
			$date = date('d/m/Y');
			$horario = date('H:i');
			$templateMail = '
			<style>
				body{
					margin: 5px;
					color: #666;
				}
			</style>			
			<center>
				<table width="450" style="border: 1px solid #8f8f8f">
					<tr>
						<td><img src="'.BASE_URL.'public/public_admin/images/8b2ac9d1.logo_BWT.png" alt="" /></td>
					</tr>
					<tr>
						<td>
							<div style="text-align: center; width: 450px; background: #dddddb; padding: 10px 5px; font-family: Arial; color: #736f66; font-size: 18px">Olá '.$this->nome.', seja bem vindo(a)</div>
						</td>
					</tr>
					<tr>
						<td style="padding: 20px">
							<p style="font-family: Arial; font-size: 14px; color: #736f66;">
								Seu e-mail de acesso é: <span style="font-family: Arial; font-size: 14px; color: #004c6c;"> '.$usuario->email_usuario.'</span><br />
								Sua senha: <span style="font-family: Arial; font-size: 14px; color: #004c6c;"> '.$senha.'</span><br />
							</p>
						  
							<p style="font-family: Arial; font-size: 14px; color: #736f66;"> 
								Se tiver alguma dúvida entre em contato pelo nosso e-mail <span style="font-family: Arial; font-size: 14px; color: #000;">contato@bwtoperadora.com.br</span><br />
							</p>
							<br/>
							<br/>
							<p style="font-family: Arial; font-size: 14px; color: #736f66;">
								<a href="http://bwtoperadora.net/laminas/admin">Clique aqui para acessar o admin bwt-laminas!</a>
							</p>
						</td>
					</tr>
				</table>
			</center>

			<p>Este email foi gerado no dia '.$date.' às '.$horario.'.</p>
			';
			
			$mail = new Zend_Mail();
			$mail->setBodyHtml(utf8_decode($templateMail));
			$mail->setFrom('contato@bwtoperadora.com.br', 'Laminas BWT Operadora');
			$mail->addTo($usuario->email_usuario, $usuario->nome_usuario);
			$mail->setSubject($assunto);
			$mail->send();
			
			if ($mail) {

                $this->ModelUsuarios->update($data, "id_usuario =" . $this->_getParam('nid'));
                App_Plugins_ZenMessage::createMessage("admin", "success", "Senha enviada por email");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
            } else {
                App_Plugins_ZenMessage::createMessage("admin", "warning", "Problema para enviar a senha");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
            }
        }
    }

    public function inserirAction() {

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;

        // Criação do Objeto Formulário
        $form = new Application_Form_Admin_Usuarios();



        // Envio para a Camada de Visualização
        $this->view->form = $form;

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {

            $data = $form->getValues();
            $data['senha_usuario'] = sha1($data['senha_usuario']);

            $busca_email = $this->ModelUsuarios->email($data['email_usuario']);

            if ($busca_email->email_usuario == $data['email_usuario']) {
                App_Plugins_ZenMessage::createMessage("admin", "danger", "Este e-mail já está cadastrado em nossa base");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'inserir'), null, true);
            } else {
                $file = App_Plugins_FileUpload::upload(APPLICATION_PATH . "/../public/uploads/");

                if ($file) {
                    $data['imagem_rodape_usuario'] = $file;
                }

                if ($this->ModelUsuarios->insert($data)) {
                    App_Plugins_ZenMessage::createMessage("admin", "success", "Usuário cadastrado com sucesso");
                    return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'index'));
                }
            }
        }
    }

    public function editarAction() {


        // Criação do Objeto Formulário
        $form = new Application_Form_Admin_Usuarios();

        $usuarios = $this->ModelUsuarios->find($this->_getParam('nid'))->current();

        $form->populate($this->ModelUsuarios->find($this->_getParam('nid'))->current()->toArray());

        if ($this->_helper->FlashMessenger->setNamespace('admin')->hasMessages()):
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        endif;



        // Envio para a Camada de Visualização
        $this->view->form = $form;


        $this->render('inserir');


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

			//@@ ------ evita bug de mesmo email na alteração... esse bug impede login de user...
			//@@ robson 02/09/16
			$busca_email = $this->ModelUsuarios->email($data['email_usuario']);
            /*if ($busca_email->email_usuario == $data['email_usuario']) {
                App_Plugins_ZenMessage::createMessage("admin", "danger", "Este e-mail já está cadastrado em nossa base");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'editar', 'nid' => $this->_getParam('nid')), null, true);
            }*/
			//@@

            if ($this->ModelUsuarios->update($data, "id_usuario = " . $this->_getParam('nid'))) {
                App_Plugins_ZenMessage::createMessage("admin", "success", "Usuário editado com sucesso");
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'index'));
            }
        } else {
            $message = "Foi encontrado os seguintes erros: <br />";
            foreach ($form->getMessages() as $input) {
                foreach ($input as $callback) {
                    $message .= $callback;
                }
            }
            if (!empty($callback)) {
                App_Plugins_ZenMessage::createMessage("admin", "danger", $message);
                return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'editar', 'nid' => $this->_getParam('nid')));
            }
        }
    }

    public function deleteAction() {

        $auth = Zend_Auth::getInstance()->getIdentity();
        $this->view->idUsuario = $auth->getUserID();

        if ($auth->getUserID() == $this->_getParam('nid')) {
            App_Plugins_ZenMessage::createMessage("admin", "danger", "Este usuário não pode ser excluido!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'index'), null, true);
        }

        if ($this->ModelUsuarios->update(array('excluido' => 1), 'id_usuario = ' . $this->_getParam('nid'))) {
            App_Plugins_ZenMessage::createMessage("admin", "success", "Usuario excluido com sucesso!");
            return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'administradores', 'action' => 'index'), null, true);
        }
    }

}
