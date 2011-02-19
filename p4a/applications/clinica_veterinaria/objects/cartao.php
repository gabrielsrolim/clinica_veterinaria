<?php


class Cartao extends P4A_Base_Mask
{
	public $fs_search = null;
	public $txt_search = null;
	public $cmd_search = null;
	
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	

	
	
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
					  array('nome','raca'))
			->addJoin("vacina",
					  "cartao_vacina.id_vacina = vacina.id_vacina",
					 array('nome_vacina'))
			->addJoin("cliente",
					  "cartao_vacina.id_cliente = cliente.id_cliente",
					  array('nome_completo'))
			->addOrder("cartao_vacina.id_animal")
			->setPageLimit(10)
			->load();
		
			
		$this->setSource($this->source);
		$this->firstRow();
		
		// Customizing fields properties
		$this->setFieldsProperties();
		
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
		
		/*barra de coisas(salvar, imprimir e tal)*/
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);
			
		
		$this->build("p4a_table", "table")
			->setWidth(600)
			->setSource($this->source)
			->setVisibleCols(array("id_animal","raca", "nome_vacina", "data_vacinacao", "aplicado"))
			->showNavigationBar();
		$this->table->cols->id_animal->setLabel("Animal ID");
		$this->table->cols->nome->setLabel("Nome Animal");
		
		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Cartao")
			->anchor($this->fields->nome_completo)
			->anchor($this->fields->nome)
			//->anchor($this->table)
			
			//->anchor($this->fields->nome)
			->anchor($this->fields->id_vacina)
			->anchorLeft($this->fields->data_vacinacao);   
		
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
			->anchorLeft($this->fs_search);

		/*Apresenta menu*/
		$this
			->display("menu", P4A::singleton()->menu)
			->display("top", $this->toolbar)
			->setFocus($this->txt_search);
		
	}
	
	private function setFieldsProperties()
	{
		
		$this->fields->nome_completo
			->setLabel("Cliente")
			->setType("text")
			->enable(false)
			->setWidth(300);

		$this->fields->nome
			->setLabel("Nome do Animal")
			->setType("text")
			->enable(false)
			->setWidth(300);
		
		$this->fields->id_vacina
			->setType("select")
			->setSource(P4A::singleton()->vacina)
			->setSourceDescriptionField("nome_vacina")
			->setLabel("Proxima Vacina")
			
			->setTooltip("Escolha a vacina na Lista")
			->setWidth(300);
		
		$this->fields->data_vacinacao
			->setWidth(178)
			->setLabel("Data proxima Vacina");
		
		
		//$this->
		
			
	}
public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		$this->source
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('animal.nome', "%{$value}%"))
			->firstRow();

		if (!$this->source->getNumRows() && $value=='') {
			$this->warning("Digite o nome de seu Animal.");
			$this->source->setWhere(null);
			$this->source->firstRow();
			$this->setFocus($this->txt_search);
		}else if(!$this->source->getNumRows() && $value){
			$this->warning("Animal nao encontrado.");
			$this->source->setWhere(null);
			$this->source->firstRow();
			$this->frame
			//->anchor($this->table)
			->anchor($this->fs_details);
		}else
		 {
			$this->frame
			->anchor($this->table)
			->anchor($this->fs_details);
		}
	}
	
}