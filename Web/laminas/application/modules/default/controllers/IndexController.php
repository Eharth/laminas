<?php

class IndexController extends Zend_Controller_Action {

    private $ModelTemplates;
    private $ModelUsuarios;
    private $ModelPerfis;

    public function init() {
        $this->view->headTitle('Bem-vindo');

        $this->ModelTemplates = new Application_Model_DbTable_Admin_Templates();
        $this->ModelUsuarios = new Application_Model_DbTable_Admin_Usuarios();
        $this->ModelPerfis = new Application_Model_DbTable_Admin_Perfis();
    }

    public function indexAction() {
        return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'), null, true);
    }

    public function laminasAction() {
        //return $this->_helper->redirector->goToRoute(array('module' => 'admin', 'controller' => 'clinicas', 'action' => 'index'));
        //$this->_helper->layout()->disableLayout(); 
        
        $auth = Zend_Auth::getInstance()->getIdentity();
        if($auth){
            $this->view->idUsuario = $auth->getUserID();
            $this->view->perfil = $auth->getRoleId();
        }
        

        if ($this->_getParam('nid') && $this->_getParam('uid')) {
            $template = $this->ModelTemplates->find($this->_getParam('nid'))->current();
            $usuario = $this->ModelUsuarios->find($this->_getParam('uid'))->current();
        } else {
            $template = $this->ModelTemplates->ultimo();
            $usuario = false;
        }

        $this->view->template = $template;
        $this->view->usuario = $usuario;
    }

    public function downloadAction() {
        
        $template = $this->ModelTemplates->find($this->_getParam('nid'))->current();
        $usuario = $this->ModelUsuarios->find($this->_getParam('uid'))->current();
        
        
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
            <meta name="viewport" content="width=device-width, user-scalable=no">
                <title>BWT Lâminas</title>
        </head>
        <style>
            body {
                margin: 0;
            }
        </style>
        <body>
            
            <table width="800" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
                <tr>';
        if($template->html_template){
            $html .='<td colspan="2" align="center">'.$template->html_template.'</td>';
        } else {
            $html .='<td colspan="2" align="center"><a href="'.$template->url_template.'"><img width="800" src="'.BASE_URL.'public/uploads/' . $template->imagem_template.'" /></a></td>';
        }
                    
                $html .='</tr>
              
                <tr>
                    <td>
                     <div style="padding: 10px; margin-top: 10px; width: 800px">
                        <table width="100%" height="84" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
                            <tr>                               
                                <td width="160">';
                                   if ($usuario->imagem_rodape_usuario):
                                        $html .='<img width="150" src="'.BASE_URL .'public/uploads/'. $usuario->imagem_rodape_usuario.'" />';
                                  endif;
                                  $html .='
                                </td>
                                <td valign="top" style="padding: 10px; font-family: Arial; font-size: 12px; color: #666; line-height: 17px">';
                                  if($usuario->nome_usuario){
                                      $html .='<span style="color: #656565; font-family: Arial; font-weight : bold">'.$usuario->nome_usuario.'</span><br />';
                                  }
                                  if($usuario->operadora_usuario){
                                      $html .='<span style="color: #656565; font-family: Arial;">'.$usuario->operadora_usuario.'</span><br />';
                                  }
                                  if($usuario->telefone_usuario){
                                      $html .='<span style="color: #656565; font-family: Arial;">'.str_replace(")", ") ", $usuario->telefone_usuario).'</span><br />';
                                  }
                                  if($usuario->site_usuario){
                                      $html .='<span style="color: #656565; font-family: Arial;"><a style="text-decoration: none; color: #656565; font-weight : bold" href="' . $usuario->site_usuario . '">' . $usuario->site_usuario . '</a></span><br />';
                                  }
                                  if($usuario->email_usuario){
                                      $html .='<span style="color: #656565; font-family: Arial;"><a style="text-decoration: none; color: #656565; font-weight : bold" href="mailto:' . $usuario->email_usuario . '">' . $usuario->email_usuario . '</a></span><br />';
                                  }
                                  if($usuario->endereco_usuario){
                                      $html .='<span style="color: #656565; font-family: Arial;">'.$usuario->endereco_usuario.'</span>';
                                  }
                                    
                                    
                                    
                                    
                                    
                                $html .= '</td>
                                <td width="83" height="84" valign="bottom">
                                    <table width="83" height="84" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
                                        <tr>
                                            <td>';
                                               if ($usuario->facebook_usuario):
                                                $html .='<a href="'.$usuario->facebook_usuario.'">
                                                        <img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_09.jpg" />
                                                    </a>';
                                                else:
                                                $html .= '<img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_09a.jpg" />';
                                                endif;
                                            $html .= '</td>
                                            <td>';
                                                if ($usuario->instagram_usuario):
                                                    $html .='<a href="'.$usuario->instagram_usuario.'">
                                                        <img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_10.jpg" />
                                                    </a>';
                                                else:
                                                    $html .= '<img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_10a.jpg" />';
                                                endif;
                                            $html .='</td>
                                        </tr>
                                        <tr>
                                            <td>';
                                                if ($usuario->google_usuario):
                                                    $html .='<a href="'.$usuario->google_usuario.'">
                                                        <img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_11.jpg" />
                                                    </a>';
                                                else:
                                                    $html .='<img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_11a.jpg" />';
                                                endif;
                                            $html .='</td>
                                            <td>';
                                                if ($usuario->twitter_usuario):
                                                    $html .='<a href="'.$usuario->twitter_usuario.'">
                                                        <img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_12.jpg" />
                                                    </a>';
                                                else:
                                                    $html.='<img style="display: block; float: left" src="'.BASE_URL.'public/public_admin/images/rodape_12a.jpg" />';
                                                endif;
                                            $html .='</td>
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </div>
                    </td>
                </tr>            
            </table>
                                </body></html>';
                                            
                                
        
       
        //$html = "<html>teste</htm>";
        //$html = file_get_contents(BASE_URL.'index/html-lamina/nid/'.$this->_getParam('nid').'/uid/'.$this->_getParam('uid'));
        $nomearquivo = APPLICATION_PATH . "/../public/uploads/$usuario->nome_usuario.html";
        
//        $url= 'http://bwtoperadora.net/laminas/index/html-lamina/nid/2/uid/1';
//        $timeout=180;
//        $client = new Zend_Http_Client($url, array('timeout' => $timeout));
//        $html=$client->request()->getBody();
        
        
        $fp = fopen($nomearquivo, 'w');
        $fw = fwrite($fp, $html);

        if ($fw == strlen($html)) {
            echo 'Arquivo criado com sucesso!!';
        } else {
            echo 'falha ao criar arquivo';
        }

        $zip = new Zend_Filter_Compress_Zip();

        /* O SEU ZIP COM O PATH E NOME */
        $zip->setArchive(APPLICATION_PATH . "/../public/uploads/$usuario->nome_usuario.zip");
        /* O DESTINO */
        $zip->setTarget(APPLICATION_PATH . "/../public/uploads/");

        /* O ARQUIVO OU PASTA COM OS ARQUIVOS QUE SERÃO COMPRIMIDOS */
        $arquivoZipado = $zip->compress(APPLICATION_PATH . "/../public/uploads/".$usuario->nome_usuario.".html");
        $this->view->arquivoZipado = $arquivoZipado;
        
        return $this->_helper->redirector->gotoUrl(BASE_URL.'public/uploads/'.$usuario->nome_usuario.'.zip');
    }

    public function htmlLaminaAction() {
        $this->_helper->layout()->disableLayout();
        
        
        $auth = Zend_Auth::getInstance()->getIdentity();
        if($auth){
            $this->view->idUsuario = $auth->getUserID();
            $this->view->perfil = $auth->getRoleId();
        }

        if ($this->_getParam('nid') && $this->_getParam('uid')) {
            $template = $this->ModelTemplates->find($this->_getParam('nid'))->current();
            $usuario = $this->ModelUsuarios->find($this->_getParam('uid'))->current();
        } else {
            $template = $this->ModelTemplates->ultimo();
            $usuario = false;
        }

        $this->view->template = $template;
        $this->view->usuario = $usuario;
    }

    public function footerAction() {
        
    }

}
