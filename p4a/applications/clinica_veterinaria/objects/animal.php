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
 * A simple editing mask built in the "standard" way.
 * @author Fabrizio Balliano <fabrizio@fabrizioballiano.it>
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @copyright Copyright (c) 2003-2010 Fabrizio Balliano, Andrea Giardina
 * @package p4a
 */
class Animal extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	public $fs_search = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();
		
		$this->build("p4a_db_source", "source")
			->setTable("animal")
			->addOrder("animal.id_animal")
			->setPageLimit(10)
			->load();

		//$this->setSource($p4a->animal);
		$this->setSource($this->source);
		$this->firstRow();
		
		/*Tipo field*/
		$this->build("p4a_field", "txt_search")
			->setLabel("Nome do Animal:")
			->implement("onreturnpress", $this, "search");
		
		/*botão*/
		$this->build("p4a_button", "cmd_search")
			->setLabel("Go")
			->implement("onclick", $this, "search");
		
		/*Caixa Consulta*/	
		$this->build("p4a_fieldset", "fs_search")
			->setLabel("Consulta")
			->anchor($this->txt_search)
			->anchorLeft($this->cmd_search);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);
			
			
		$this->build("p4a_table", "table")
			->setSource($this->source)
			->showNavigationBar()
			->setVisibleCols(array("id_animal","nome", "raca","sexo","especie","data_de_nascimento"));

		$this->table->cols->nome->setLabel("Nome Animal");

		$this->setRequiredField("nome");
		
		$this->table->cols->id_animal->setLabel("ID Animal");
		
		$this->fields->id_animal
			->setWidth(50)
			->disable()
			->setLabel("Animal ID");
		
		$this->fields->id_cliente
			->setType("select")
			->setLabel("Dono")
			->setSource(P4A::singleton()->cliente)
			->setSourceDescriptionField("nome_completo");
		
		$this->fields->nome
			->setWidth(300)
			->setLabel("Nome do Animal:");
		
		$this->fields->raca
			->setWidth(100)
			->setLabel("Raca:");
		
		$this->fields->especie
			->setWidth(270)
			->setLabel("Especie:");
		
		$this->fields->sexo
			->setType("text")
			->setLabel("Sexo");
		
		$this->fields->id_animal
			->disable()
			->setLabel("Animal ID");

		$this->fields->nome->label->setwidth(150);

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Detalhe Animal")
			->anchor($this->fields->id_animal)
			->anchor($this->fields->id_cliente)
			->anchorLeft($this->fields->nome)
			->anchor($this->fields->raca)
			->anchorLeft($this->fields->especie)
			->anchor($this->fields->sexo)
			->anchorLeft($this->fields->data_de_nascimento);
		
		$this->frame
			->anchor($this->fs_search)
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->txt_search);
	}
	
public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		
		$this->source
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('nome', "%{$value}%"))
			->firstRow();
		
		if (!$this->source->getNumRows()) {
			$this->warning("Cadastre Vacinacao");
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}
}
