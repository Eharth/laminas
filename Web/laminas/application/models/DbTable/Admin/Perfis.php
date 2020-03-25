<?php

class Application_Model_DbTable_Admin_Perfis extends Zend_Db_Table_Abstract {

    protected $_name = 'perfis';

    public function listar($filters) {
        $select = $this->select()
                ->where("excluido <> 1")
                ->order("id_perfil");

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($filters['p']);
        $paginator->setDefaultItemCountPerPage($filters['t']);
        $paginator->setDefaultScrollingStyle('Sliding');
        return $paginator;
    }

    public function perfil($perfil = 'Cliente') {
        $select = $this->select()
                ->where('siglaPerfil = ?', $perfil);
        return $this->fetchRow($select);
    }
    
    public function sigla($sigla) {
        $select = $this->select()
                ->where("sigla_perfil = ?", $sigla)
                ->limit(1);
        
        return $this->fetchRow($select);
    }

}
