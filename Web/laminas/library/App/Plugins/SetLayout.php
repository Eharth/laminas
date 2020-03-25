<?php

//library/App/Plugins/SetLayout.php

class App_Plugins_SetLayout extends Zend_Layout_Controller_Plugin_Layout
{

// o preDispatch é chamado antes de uma ação ser despachada pelo dispatcher
// com isso, usamos o nosso objeto request e extraimos o nome do módulo

	public function preDispatch (Zend_Controller_Request_Abstract $request)
	{
		$MyPath = $request->getModuleName();
		$this->getLayout()->setLayoutPath(APPLICATION_PATH . '/modules/'. $MyPath . '/layouts/scripts');
		$this->getLayout()->setLayout('layout');
	}

// Dispensa comentários

	protected function _setupLayout ($moduleName)
	{

		$this->getLayout()->setLayoutPath(APPLICATION_PATH . '/modules/'. $moduleName . '/layouts/scripts');

		$this->getLayout()->setLayout($moduleName);

	}

}
