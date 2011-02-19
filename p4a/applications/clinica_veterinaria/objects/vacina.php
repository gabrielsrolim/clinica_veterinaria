<?php


class Vacina extends P4A_Base_Mask
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
			->setTable("vacina")
			->addOrder("vacina.id_vacina")
			->setPageLimit(10)
			->load();

		//$this->setSource($p4a->animal);
		$this->setSource($this->source);
		$this->firstRow();
		
		/*Tipo field*/
		$this->build("p4a_field", "txt_search")
			->setLabel("Nome da Vacina:")
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
			
		//$this->setSource($p4a->vacina);
		//$this->firstRow();

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($this->source)
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("nome");
		$this->table->cols->id_vacina->setLabel("Vacina ID");
		$this->fields->id_vacina
			->disable()
			->setLabel("Vacina ID");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Vacina detail")
			->anchor($this->fields->id_vacina)
			->anchor($this->fields->nome_laboratorio)
			->anchor($this->fields->nome)
			->anchor($this->fields->lote)
			->anchor($this->fields->laboratorio);
		
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
			$this->warning("Vacina nao encontrada");
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}
}