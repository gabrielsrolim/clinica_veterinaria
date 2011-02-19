<?php


class Cartao_Vacina extends P4A_Base_Mask
{
	public $fs_search = null;
	public $txt_search = null;
	public $cmd_search = null;
	
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	public $fs_details2 = null;
	public $frame = null;
	

	
	
	public function __construct()
	{
		parent::__construct();
		/*Titulo da pagina*/
		$this->setTitle("Cartao de Vacina");
		//select * from cartao_vacina join animal on() join vacina on ()
		// DB Source
		$this->build("p4a_db_source", "source")
			->setTable("cartao_vacina")
			->addJoin("animal",
					  "cartao_vacina.id_animal = animal.id_animal",
					  array('nome','raca'))
			->addJoin("vacina",
					  "cartao_vacina.id_vacina = vacina.id_vacina",
					 array('nome_vacina'))
			->addJoin("cliente",
					  "cliente.id_cliente = animal.id_animal",
					  array('nome_completo'))
			->addOrder("cartao_vacina.id_animal")
			->setPageLimit(10)
			->load();
		
			
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
		
		$this->build("p4a_button", "button")
			->setLabel("Cadastrar Vacinacao")
			->implement("onclick",$this, "menuClick");
		
		/*barra de coisas(salvar, imprimir e tal)*/
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);	
		
		$this->build("p4a_table", "table")
			->setWidth(600)
			->setSource($this->source)
			->setVisibleCols(array("nome","data_vacinacao","nome_vacina","aplicado"))
			->showNavigationBar();
		$this->table->cols->data_vacinacao->setLabel("Data Vacinacao");
		$this->table->cols->nome->setLabel("Nome Animal");
		
		$this->build("p4a_fieldset", "fs_details2")
			->setLabel("Cadastrar Vacinacao")
			->anchor($this->fields->id_vacinacao)
			->anchor($this->fields->nome_completo)
			->anchor($this->fields->nome)
			->anchor($this->fields->data_vacinacao)
			->anchor($this->fields->nome_vacina)
			->anchorLeft($this->fields->aplicado);
		
		// Customizing fields properties
		$this->setFieldsProperties();
		
		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Cartao")
			->anchor($this->table);   
		

		$this->frame
			->anchor($this->fs_search)
			 ->anchor($this->fs_details)
		    ->anchor($this->fs_details2);
		
		/*Apresenta menu*/
		$this
			->display("menu", P4A::singleton()->menu)
			->display("top", $this->toolbar)
			->setFocus($this->txt_search);
		
	}

private function setFieldsProperties()
	{
		
		$this->fields->id_vacinacao
			->setLabel("ID Vacinacao")
			->setType("text")
			->enable(false)
			->setWidth(300);
		$this->fields->nome
			->setType("text")
			->setLabel("Nome Animal")
			->setWidth(300);
		
		$this->fields->nome_vacina
			->setType("text")
			->setLabel("Nome Vacina")
			->setWidth(300);
		
		$this->fields->data_vacinacao
			->setWidth(178)
			->setLabel("Data Vacina");
		
		$this->fields->aplicado;
		
		$this->fields->nome_completo
			->setwidth(200)
			->setLabel("Dono")
			->setType("text")
			->enable(false);
		
		
			
	}
	


public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		
		$this->source
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('animal.nome', "%{$value}%"))
			->firstRow();
		
		if (!$this->source->getNumRows()) {
			$this->warning("Cadastre Vacinacao");
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}
	
}