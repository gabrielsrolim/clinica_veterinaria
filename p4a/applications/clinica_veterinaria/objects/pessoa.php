<?php

class Pessoa extends P4A_Base_Mask
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
			->setTable("pessoa")
			->addOrder("pessoa.id_pessoa")
			->setPageLimit(10)
			->load();

		//$this->setSource($p4a->animal);
		$this->setSource($this->source);
		$this->firstRow();
		
		/*Tipo field*/
		$this->build("p4a_field", "txt_search")
			->setLabel("Nome da Pessoa:")
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
			->showNavigationBar();

		
		//$this->table->cols->id_cliente->setLabel("Cliente ID");
		//$this->table->cols->nome_completo->setLabel("Cliente Nome");
		
		$this->fields->id_pessoa
			->disable()
			->setLabel("Pessoa ID");
		
		$this->fields->nome_completo
			->setLabel("Nome")
			->setWidth(300)
			->setHeight(15);
		$this->fields->rua
			->setLabel("Rua:")
			->setWidth(300)
			->setHeight(15);
		$this->fields->bairro
			->setLabel("bairro:")
			->setWidth(100)
			->setHeight(15);
		$this->fields->numero
			->setLabel("Numero:")
			->setWidth(100)
			->setHeight(15);
		$this->fields->cep
			->setLabel("Cep")
			->setWidth(100)
			->setHeight(15);
		$this->fields->complemento
			->setLabel("Complemeto")
			->setWidth(100)
			->setHeight(15);
		$this->fields->cidade
			->setLabel("Cidade")
			->setWidth(200)
			->setHeight(15);
		$this->fields->telefone
			->setLabel("Telefone")
			->setWidth(200)
			->setHeight(15);
		$this->fields->uf
			->setLabel("UF")
			->setWidth(50)
			->setHeight(15);

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Detalhe Pessoa")
			->anchor($this->fields->id_pessoa)
			->anchor($this->fields->nome_completo)
			->anchor($this->fields->rua)
			->anchorLeft($this->fields->numero)
			->anchor($this->fields->bairro)			
			->anchorLeft($this->fields->cep)
			->anchorLeft($this->fields->complemento)
			->anchor($this->fields->cidade)
			->anchorLeft($this->fields->uf)
			->anchor($this->fields->telefone);
			
		
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
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('nome_completo', "%{$value}%"))
			->firstRow();
		
		if (!$this->source->getNumRows()) {
			$this->warning("Pessoa nao encontrada");
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}
}