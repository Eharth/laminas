<?php

class Application_Model_DbTable_Front_Templates extends Zend_Db_Table_Abstract {

    protected $_name = 'templates';

    public function listar($filters) {
        $select = $this->select()
                ->order("id_template");

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
