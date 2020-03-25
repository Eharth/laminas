<?php

class Application_Model_DbTable_Admin_Templates extends Zend_Db_Table_Abstract {

    protected $_name = 'templates';

    public function listar($filters) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from('templates');
        $select->join("categorias", "templates.id_categoria = categorias.id_categoria");
        $select->where("templates.excluido <> 1");
        

        if (!(empty($filters['date']))) {
            $select->where("templates.datavalid_template >= ?", $filters['date']);
        }
        if (!(empty($filters['status']))) {
            $select->where("templates.status_template = ?", $filters['status']);
        }
        if (!(empty($filters['cat']))) {
            $select->where("templates.id_categoria = ?", $filters['cat']);
        }
		if (!(empty($filters['buscar']))) {
            $select->where("templates.titulo_template LIKE ?", '%'.$filters['buscar'].'%');
        }
        $select->order("templates.id_template DESC");

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

    public function ultimo() {
        $select = $this->select()
                ->order("id_template DESC")
                ->limit(1);
        return $this->fetchRow($select);
    }

}
