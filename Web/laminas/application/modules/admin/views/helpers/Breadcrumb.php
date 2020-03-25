<?php

class Zend_View_Helper_BreadCrumb extends Zend_View_Helper_Abstract { 

   protected $_request;

   protected $_separator = '&rsaquo;';

   protected $_breadcrumb = array();

   public function __construct() {
      $fc = Zend_Controller_Front::getInstance();
      $this->_request = $fc->getRequest();
   }

   public function setSeparator($separator) {
      $this->_separator = $separator;
   }

   public function set(array $breadcrumb) {
      $this->_breadcrumb = $breadcrumb;

      return $this;
   }

   public function breadcrumb(array $breadcrumb = array()) {
      if (empty($this->_breadcrumb)) {
         if (!empty($breadcrumb)) {
            $this->set($breadcrumb);
         } else {
            $module = $this->_request->getModuleName();
            $controller = $this->_request->getControllerName();
            $action = $this->_request->getActionName();
            if ($module != 'default') {
               $this->_breadcrumb[] = array(
                   'title' => $module,
                   'url' => $this->view->url(array('module' => $module), 'default', true));
            }

            if ($controller != 'index') {
               $this->_breadcrumb[] = array('title' => $controller,
                   'url' => $this->view->url(array('module' => $module, 'controller' => $controller), 'default', true)
               );
            }
            if ($action != 'index') {
               $this->_breadcrumb[] = array(
                   'title' => $action,
                   'url' => $this->view->url(array('module' => $module, 'controller' => $controller, 'action' => $action), 'default', true)
               );
            }

            $this->_breadcrumb[count($this->_breadcrumb) - 1]['url'] = null;
         }
      }
      return $this;
   }

   public function __toString() {
      if (count($this->_breadcrumb) == 1) {
         $breadcrumb = '';
      } else {
         $breadcrumb = '<ol class="breadcrumb">';
         foreach ($this->_breadcrumb as $i => $bc) {
            $breadcrumb .= '<li>' . ($i != 0 ? '<span>' . $this->_separator . '</span>' : null);

            if ($bc['url'] === null) {
               $breadcrumb .= $this->view->escape($bc['title']);
            } else {
               $breadcrumb .= '<a href="' . $bc['url'] . '">' . $this->view->escape($bc['title']) . '</a>';
            }
            $breadcrumb .= '</li>';
         }

         $breadcrumb .= '</ol>';
      }
      return $breadcrumb;
   }

}