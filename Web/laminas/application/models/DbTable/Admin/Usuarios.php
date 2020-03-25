<?php

class Application_Model_DbTable_Admin_Usuarios extends Zend_Db_Table_Abstract {

    protected $_name = 'usuarios';

    public function listar($filters = false) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from("usuarios");
        $select->join("perfis", "usuarios.id_perfil = perfis.id_perfil");
        $select->order("usuarios.id_usuario DESC");
        $select->where("perfis.excluido <> 1");
        $select->where("usuarios.excluido <> 1");
        if(!empty($filters['filtro']) && $filters['filtro'] == "novos"){
            $select->where("usuarios.senha_usuario IS NULL");
        }
		if(!empty($filters['buscar'])){
            $select->where("usuarios.nome_usuario LIKE ?", '%'.$filters['buscar'].'%');
        }

        if (!(empty($filters['limit']))) {
            $select->limit($filters['limit']);
            return $this->fetchAll($select);
        } else {
            $paginator = Zend_Paginator::factory($select);
            $paginator->setCurrentPageNumber($filters['p']);
            $paginator->setDefaultItemCountPerPage($filters['t']);
            $paginator->setDefaultScrollingStyle('Sliding');
            return $paginator;
        }
    }
    
    public function usuarios_novos() {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from("usuarios");        
        $select->join("perfis", "usuarios.id_perfil = perfis.id_perfil");
        $select->where("usuarios.senha_usuario IS NULL");
        $select->where("perfis.excluido <> 1");
        $select->where("usuarios.excluido <> 1");
        $select->order("usuarios.id_usuario DESC");
        
        return $this->fetchAll($select);
    }

    public function buscar($idUsuario, $perfil) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from("usuarios");
        $select->join("perfis", "usuarios.id_perfil = perfis.id_perfil");


        $select->where("usuarios.id_usuario = ?", $idUsuario);

        return $this->fetchRow($select);
    }
    
     public function email($email) {
        $select = $this->select()
                ->where("email_usuario = ?", $email)
                ->limit(1);
        return $this->fetchRow($select);
    }

    public function findUser($IdUsuario) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from("Usuarios")
                ->join("perfis", "usuarios.IdPerfil = perfis.IdPerfil")
                ->join("complementos", "usuarios.IdComplemento = complementos.IdComplemento")
                ->join("cidades", "complementos.IdCidade = cidades.IdCidade")
                ->join("estados", "cidades.IdEstado = estados.IdEstado")
                ->where("IdUsuario = ?", $IdUsuario);

        return $this->fetchRow($select);
    }

    public function inserir($data) {
        return $this->insert($data);
    }

    public function editar($data) {
        return $this->update($data, 'idUsuario = ' . (int) $data['idUsuario']);
    }

    public function excluir($IDUsuario) {
        return $this->delete('IdUsuario = ' . (int) $IDUsuario);
    }

}
