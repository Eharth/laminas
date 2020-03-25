<?php

class Application_Model_DbTable_Admin_Categorias extends Zend_Db_Table_Abstract {

    protected $_name = 'categorias';

    public function listar($filters) {
        $select = $this->select()
                ->where("excluido <> 1")
                ->order("categorias.nome_categoria ASC");
			

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($filters['p']);
        $paginator->setDefaultItemCountPerPage($filters['t']);
        $paginator->setDefaultScrollingStyle('Sliding');
        return $paginator;
    }

    public function categoria($categoria = 'Cliente') {
        $select = $this->select()
                ->where('siglaCategoria = ?', $categoria);
        return $this->fetchRow($select);
    }
    
    public function sigla($sigla) {
        $select = $this->select()
                ->where("sigla_categoria = ?", $sigla)
                ->limit(1);
        
        return $this->fetchRow($select);
    }

}
