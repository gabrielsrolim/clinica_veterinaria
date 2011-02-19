<?php


class Veterinario extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	public $fs_search = null;
	public $teste = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();
		
		$this->build("p4a_db_source", "source")
			->setTable("veterinario")
			->addOrder("veterinario.id_veterinario")
			->setPageLimit(10)
			->load();

		//$this->setSource($p4a->animal);
		$this->setSource($this->source);
		$this->firstRow();
		//$this->fields->tipo_pessoa->setValue(2);
		/*Tipo field*/
		$this->build("p4a_field", "txt_search")
			->setLabel("Nome do Veterinario:")
			->implement("onreturnpress", $this, "search");
		
		$this->txt_search->label->setWidth(150);
		
		/*botão*/
		$this->build("p4a_button", "cmd_search")
			->setLabel("Go")
			->implement("onclick", $this, "search");
		
		/*Caixa Consulta*/	
		$this->build("p4a_fieldset", "fs_search")
			->setLabel("Consultar:")
			->anchor($this->txt_search)
			->anchorLeft($this->cmd_search);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($this->source)
			->setWidth(800)
			->setVisibleCols(array("id_veterinario","nome_completo","telefone","carga_horaria","salario","horario_inicio","horario_fim"))
			->showNavigationBar();

		//$this->setRequiredField("nome_completo");
		$this->table->cols->id_veterinario->setLabel("Veterinario ID");
		$this->table->cols->nome_completo->setLabel("Nome Veterinario")->setwidth(200);
		$this->table->cols->carga_horaria->setwidth(200);
		$this->table->cols->salario->setwidth(100);
			
		$this->setFieldsProperties();
		
		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Detalhe Veterinario")
			->anchor($this->fields->id_veterinario)
			->anchor($this->fields->nome_completo)
			->anchor($this->fields->data_de_nascimento)
			->anchorLeft($this->fields->telefone)
			->anchor($this->fields->carga_horaria)
			->anchor($this->fields->horario_inicio)
			->anchorLeft($this->fields->horario_fim)
			->anchor($this->fields->salario);		
			
					
		$this->frame
			->anchor($this->fs_search)
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->txt_search);
	}
	
private function setFieldsProperties()
	{
		
		$this->fields->id_veterinario
			->setLabel("ID Veterinario")
			->enable(false);
		$this->fields->id_veterinario->label->setwidth(150);	
		
			
			
		$this->fields->carga_horaria
			->setLabel("Carga Horaria")
			->setType("text");
		$this->fields->carga_horaria->label->setwidth(150);
			
		$this->fields->nome_completo
			->setLabel("Nome Veterinario:")
			->setWidth(300)
			->setType("text");
		$this->fields->nome_completo->label->setwidth(150);
		
		$this->fields->horario_inicio
			->setLabel("Horario Inicio");
		$this->fields->horario_inicio->label->setwidth(150);
			
		$this->fields->horario_fim
			->setLabel("Horario Fim");

			
		$this->fields->salario
			->setLabel("salario");
		$this->fields->salario->label->setwidth(150);
			
		$this->fields->data_de_nascimento
			->setLabel("Data de Nascimento")
			->setYearRange(1900,2020);
		$this->fields->data_de_nascimento->label->setwidth(150);

		
		
		
		
			
	}
	
public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		
		$this->source
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('nome_completo', "%{$value}%"))
			->firstRow();
		
		if (!$this->source->getNumRows()) {
			$this->warning("Veterinario nao encontrada");
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}
}