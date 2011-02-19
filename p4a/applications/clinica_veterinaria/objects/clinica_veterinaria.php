<?php
/**
 * This file is part of P4A - PHP For Applications.
 *
 * P4A is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 * 
 * P4A is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with P4A.  If not, see <http://www.gnu.org/licenses/lgpl.html>.
 * 
 * To contact the authors write to:                                     <br />
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                     <br />
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * @author Fabrizio Balliano <fabrizio@fabrizioballiano.it>
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @copyright Copyright (c) 2003-2010 Fabrizio Balliano, Andrea Giardina
 * @link http://p4a.sourceforge.net
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package p4a
 */

/**
 * @author Fabrizio Balliano <fabrizio@fabrizioballiano.it>
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @copyright Copyright (c) 2003-2010 Fabrizio Balliano, Andrea Giardina
 * @package p4a
 */
class Clinica_Veterinaria extends P4A
{
	/**
	 * @var P4A_DB_Source
	 */
	public $pessoa = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $funcionario = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $veterinario = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $animal = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $consulta = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $vacina = null;
	
	/**
	 * @var P4A_DB_Source
	 */
	public $tipo_pessoa = null;
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTitle("Clinica Veterinaria");

		 //Menu
		$this->build("p4a_menu", "menu");
		
		$this->menu->addItem("cadastra_vacinacao","Cartao de Vacinacao")
			->implement("onclick", $this, "menuClick");

		$this->menu->addItem("consulta", "Cadastrar Consulta")
			->implement("onclick", $this, "menuClick");
		
		$this->menu->addItem("gerenciar","Gerenciar");

		$this->menu->items->gerenciar->addItem("cliente","Consultar/Cadastrar Cliente")
			->implement("onclick", $this, "menuClick");

		$this->menu->items->gerenciar->addItem("animal","Consultar/Cadastrar Animal")
			->implement("onclick", $this, "menuClick");
		
		$this->menu->items->gerenciar->addItem("vacina","Consultar/Cadastrar Vacina")
			->implement("onclick", $this, "menuClick");
		
		$this->menu->items->gerenciar->addItem("veterinario","Consultar/Cadastrar Veterinario")
			->implement("onclick", $this, "menuClick");			
			
		// Data sources
		$this->build("p4a_db_source", "cliente")
			->setTable("cliente")
			->addOrder("id_cliente")
			->load();
					
		$this->build("p4a_db_source", "vacina")
			->setTable("vacina")
			->addOrder("id_vacina")
			->load();
			
		$this->build("p4a_db_source", "veterinario")
			->setTable("veterinario")
			->addOrder("id_veterinario")
			->load();
			
		$this->build("p4a_db_source", "animal")
			->setTable("animal")
			->addOrder("id_animal")
			->load();
			
		$this->build("p4a_db_source", "consulta")
			->setTable("consulta")
			->addOrder("id_consulta")
			->load();

		// Primary action
		//$this->openMask("P4A_Login_Mask");
		//$this->active_mask->implement('onLogin', $this, 'login');
		//$this->active_mask->username->setTooltip("Simply type p4a");
		//$this->active_mask->password->setTooltip("Type p4a here too");
		//$this->loginInfo();
		//$this->openMask("veterinario");
		//$this->openMask("consulta");
		//$this->openMask("animal");
		//$this->openMask("cliente");
		//$this->openMask("vacina");  
		$this->openMask("cadastra_vacinacao");
	}

	public function menuClick()
	{
		$this->openMask($this->active_object->getName());
		
	}
	
	public function login()
	{
		$username = $this->active_mask->username->getNewValue();
		$password = $this->active_mask->password->getNewValue();
		
		if ($username == "p4a" and $password == md5("p4a")) {
			$this->messageInfo("Login successful");
			$this->openMask("cartao_vacina");
		} else {
			$this->messageError("Login failed");
			$this->loginInfo();
		}
	}
	
	protected function loginInfo()
	{
		$this->messageInfo('To login type:<br />username: p4a<br />password: p4a', 'info');
	}
}