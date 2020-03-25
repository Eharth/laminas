<?php

class App_Plugins_Route {
	
	/**
	 * Cria uma rota para ser acessada via get
	 * @param  string $rota     A rota em si, 'hello/:world'
	 * @param  string $nomeRota Nome da rota, para ser usada para redirects, etc
	 * @param  array $options  	Passa um array com o nome do controller, action, module e defaults
	 * @return object           Adiciona a rota
	 */
	public function createRoute($rota, $nomeRota, $options) {

		$route = new Zend_Controller_Router_Route(
			$rota,
			$options
		);

		return Zend_Controller_Front::getInstance()->getRouter()->addRoute($nomeRota, $route);
	}

}