<?php


class Consulta extends P4A_Base_Mask
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
			->setTable("consulta")
			->addJoin("veterinario",
					  "veterinario.id_veterinario = consulta.id_veterinario",
					  array('nome_completo'=>'nome_veterinario'))
			->addJoin("animal",
					  "animal.id_animal = consulta.id_animal",
					  array('nome'=>'nome_amimal'))
			->addOrder("consulta.id_consulta")
			->setPageLimit(10)
			->load();

		//$this->setSource($p4a->animal);
		$this->setSource($this->source);
		$this->firstRow();
		//$this->fields->tipo_pessoa->setValue(2);
		/*Tipo field*/
		$this->build("p4a_field", "txt_search")
			->setLabel("Nome Animal:")
			->setType("text")
			->implement("onreturnpress", $this, "search");
		//$this->txt_search->label->setwidth(100);
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
			->setWidth(700)
			->setVisibleCols(array("id_consulta","nome_veterinario","nome_amimal","data_consulta","hora_inicio","compareceu"))
			->showNavigationBar();

		//$this->setRequiredField("nome_completo");
		$this->table->cols->nome_veterinario->setLabel("Veterinario");
			
		$this->setFieldsProperties();
		
		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Detalhe Veterinario")
			->anchor($this->fields->id_consulta)
			->anchor($this->fields->id_veterinario) 
			->anchor($this->fields->id_animal)
			->anchorLeft($this->fields->data_consulta)
			->anchor($this->fields->hora_inicio)
			->anchorLeft($this->fields->hora_fim)
			->anchor($this->fields->compareceu)
			->anchorLeft($this->fields->valor)
			->anchor($this->fields->observacao)
			->anchor($this->fields->diagnostico);		
			
					
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
		
		
		$this->fields->id_consulta
			->setLabel("ID Consulta")
			->enable(false);
		
		$this->fields->id_veterinario
			->setLabel("Veterinario")
			->setType("select")
			->setSource(P4A::singleton()->veterinario)
			->setSourceDescriptionField("nome_completo")
			->setSourceValueField("id_veterinario");		
			
		$this->fields->id_animal
			->setLabel("Nome Animal")
			->setType("select")
			->setSource(P4A::singleton()->animal)
			->setSourceDescriptionField("nome")
			->setSourceValueField("id_animal");

		
		$this->fields->data_consulta
			->setLabel("Data da Consulta");
		
		$this->fields->hora_inicio
			->setLabel("Hora Inicio da Consulta");
		$this->fields->hora_inicio->label->setwidth(150);
			
		$this->fields->hora_fim
			->setLabel("Hora Fim da Consulta");
		$this->fields->hora_fim->label->setwidth(150);
			
		$this->fields->valor
			->setLabel("Valor");
			
		$this->fields->observacao
			->setLabel("Observacao")
			->settype("textarea");
			
		$this->fields->diagnostico
			->setLabel("Diagnostico")
			->settype("textarea");
		
		$this->fields->compareceu
			->setLabel("Compareceu");
		
		
		
		
		
			
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
