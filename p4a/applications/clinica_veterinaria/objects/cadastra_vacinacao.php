<?php


class Cadastra_Vacinacao extends P4A_Base_Mask
{
	public $fs_search = null;
	public $txt_search = null;
	public $cmd_search = null;
	
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	public $fs_dono = null;

	
	
	public function __construct()
	{
		parent::__construct();
		/*Titulo da pagina*/
		$this->setTitle("Cartao de Vacina");
		
		// DB Source
		$this->build("p4a_db_source", "source")
			->setTable("cartao_vacina")
			->addJoin("animal",
					  "cartao_vacina.id_animal = animal.id_animal",
					  array('nome'=>'nome_animal','raca'))
			->addJoin("vacina",
					  "cartao_vacina.id_vacina = vacina.id_vacina",
					 array('nome'=>'nome_vacina'))
			->addOrder("cartao_vacina.id_cartao_vacina")
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
		
		
		
		$this->build("p4a_table", "table")
			->setSource($this->source)
			->setwidth(700)
			->setVisibleCols(array("nome_animal","raca","nome_vacina","data_vacinacao","aplicado"))
			->showNavigationBar();
			
		$this->table->cols->nome_vacina->setLabel("Nome Vacina");
		
		// Customizing fields properties
		$this->setFieldsProperties();
		
		/*barra de coisas(salvar, imprimir e tal)*/
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);
			
		
		
		
		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Cadastrar Vacinacao")
			->anchor($this->fields->id_vacinacao)
			->anchor($this->fields->id_animal)
			->anchor($this->fields->data_vacinacao)
			->anchor($this->fields->id_vacina)
			->anchorLeft($this->fields->aplicado);   
		
			/*$this
  			->setRequiredField("nome_completo")
  			->setRequiredField("category_id")
  			->setRequiredField("brand_id")
  			->setRequiredField("model")
  			->setRequiredField("price")
  			->setRequiredField("description")
  			->setRequiredField("discount");
			*/
		/*Coloca na tela as tabelas e coisas criadas*/
		$this->frame
			->anchor($this->fs_search)
			->anchor($this->table)
		    ->anchor($this->fs_details);

		/*Apresenta menu*/
		$this
			->display("menu", P4A::singleton()->menu)
			->display("top", $this->toolbar)
			->setFocus($this->txt_search);
		
	}
	
	private function setFieldsProperties()
	{
		
		$this->fields->id_cartao_vacina
			->setLabel("ID Cartao Vacina")
			->setType("text")
			->enable(false)
			->setWidth(300);
		$this->fields->id_cartao_vacina->label->setWidth(150);
		
		$this->fields->id_animal
			->setLabel("Nome do Animal")
			->setType("select")
			->setSource(P4A::singleton()->animal)
			->setSourceDescriptionField("nome")
			->setSourceValueField("id_animal")
			->setWidth(300);

		$this->fields->id_vacina
			->setType("select")
			->setSource(P4A::singleton()->vacina)
			->setSourceDescriptionField("nome")
			->setLabel("Nome Vacina")			
			->setTooltip("Escolha a vacina na Lista")
			->setWidth(300);
		
		$this->fields->data_vacinacao
			->setWidth(178)
			->setLabel("Data Vacina");
		
		$this->fields->aplicado;
		
		
			
	}

public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		if($value!=''){
		$this->source
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('animal.nome', "%{$value}%"))
			->firstRow();
			if (!$this->source->getNumRows()) {
				$this->warning("Consulta nao encontrada");
				$this->source->setWhere(null);
				$this->source->firstRow();
			}
		}else{
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}	

}