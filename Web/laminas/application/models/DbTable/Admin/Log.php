<?php

class Application_Model_DbTable_Admin_Log extends Zend_Db_Table_Abstract {

    protected $_name = 'logs';

    public function listar($filters) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from("logs");
        $select->order("id_log DESC");
        $select->join("usuarios", "usuarios.id_usuario = logs.id_usuario");

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

}
