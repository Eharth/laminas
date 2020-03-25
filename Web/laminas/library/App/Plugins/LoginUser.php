<?php

class App_Plugins_LoginUser {

    public static function login($table = false, $login = false, $pass = false) {


        if ($login != NULL or $pass != NULL) {

            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

            $authAdapter->setTableName($table);
            $authAdapter->setIdentityColumn('email_usuario');
            $authAdapter->setCredentialColumn('senha_usuario');

            //$go_to_page = array('module' => 'admin', 'controller' => 'index', 'action' => 'index');

            if (!empty($login) && !empty($login)) {
                $authAdapter->setIdentity($login);
                $authAdapter->setCredential($pass);
            } else {
                App_Plugins_ZenMessage::createMessage("login", "danger", "Login incorreto");
                return FALSE;
            }


            $select = $authAdapter->getDbSelect();
            $select->join(array('p' => 'perfis'), 'p.id_perfil = usuarios.id_perfil', array('nome_perfil' => 'sigla_perfil'));

            //Efetua o login

            $auth = Zend_Auth::getInstance();

            $result = $auth->authenticate($authAdapter);


            //Verifica se o login foi efetuado com sucesso
            if ($result->isValid()) {
                //Armazena os dados do usuário em sessão, apenas desconsiderando
                //a senha do usuário



                $info = $authAdapter->getResultRowObject(null, 'senha_usuario');

                if ($info->status_usuario != 1) {
                    App_Plugins_ZenMessage::createMessage("login", "danger", "Conta desativada");
                    return FALSE;
                }

                $usuario = new Application_Model_DbTable_Usuario();
                //$usuario->setFullName($info->emailUsuario);
                $usuario->setUserName($info->nome_usuario);
                $usuario->setUserID($info->id_usuario);
                //die(print $info->nome_perfil);
                $usuario->setRoleId($info->nome_perfil);

                $storage = $auth->getStorage();
                $storage->write($usuario);
                return TRUE;
            } else {

                //Dados inválidos
                App_Plugins_ZenMessage::createMessage("login", "danger", "Login incorreto");
                return FALSE;
            }
        } else {
            //Formulário preenchido de forma incorreta
            return FALSE;
        }
    }

}
