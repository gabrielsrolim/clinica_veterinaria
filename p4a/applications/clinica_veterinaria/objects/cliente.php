<?php


class Cliente extends P4A_Base_Mask
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
			->setTable("cliente")
			->addOrder("cliente.id_cliente")
			->setPageLimit(10)
			->load();

		//$this->setSource($p4a->animal);
		$this->setSource($this->source);
		$this->firstRow();
		
		/*Tipo field*/
		$this->build("p4a_field", "txt_search")
			->setLabel("Nome do Cliente:")
			->implement("onreturnpress", $this, "search");
		
		/*botão*/
		$this->build("p4a_button", "cmd_search")
			->setLabel("Go")
			->implement("onclick", $this, "search");
		
		/*Caixa Consulta*/	
		$this->build("p4a_fieldset", "fs_search")
			->setLabel("Consultar")
			->anchor($this->txt_search)
			->anchorLeft($this->cmd_search);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->build("p4a_table", "table")
			->setSource($this->source)
			->showNavigationBar()
			->setVisibleCols(array("id_cliente","nome_completo", "telefone","rua","data_de_nascimento"));

		
		$this->table->cols->id_cliente->setLabel("Cliente ID");
		$this->table->cols->nome_completo->setLabel("Cliente Nome");
		
		$this->setFieldsProperties();
		
		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Detalhe Cliente")
			->anchor($this->fields->id_cliente)
			->anchor($this->fields->nome_completo)
			->anchor($this->fields->data_de_nascimento)
			->anchorLeft($this->fields->data_cadastro)
			->anchor($this->fields->rua)
			->anchorLeft($this->fields->numero)
			->anchor($this->fields->bairro)
			->anchorLeft($this->fields->cidade)
			->anchor($this->fields->uf)
			->anchorLeft($this->fields->cep);
			
		
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
		
		$this->fields->id_cliente
			->setLabel("ID Cliente");
		
		$this->fields->nome_completo
			->setwidth(300)
			->setLabel("Nome Completo:");
			
		$this->fields->telefone
			->setLabel("Telefone de Contato:");
			
		$this->fields->numero
			->setLabel("Numero:");
			
		$this->fields->rua
			->setWidth(300)
			->setLabel("Rua:");
			
		$this->fields->cidade
			->setWidth(200)
			->setLabel("Cidade:");
			
		$this->fields->cep
			->setWidth(200)
			->setLabel("Cep");
			
		$this->fields->uf
			->setLabel("UF(Ex.:'PB'):");
			
		$this->fields->complemento
			->setLabel("Complemento:");
			
		$this->fields->bairro
			->setLabel("Bairro");
			
		$this->fields->data_cadastro
			->setLabel("Data Cadastro");
			
		$this->fields->data_de_nascimento
			->setLabel("Data de Nascimento");
		
		$this->fields->data_de_nascimento
			->setLabel("Data de Nascimento")
			->setYearRange(1900,2020);
			
			
	}
public function search()
	{
		$value = $this->txt_search->getSQLNewValue();
		
		$this->source
			->setWhere(P4A_DB::singleton()->getCaseInsensitiveLikeSQL('nome_completo', "%{$value}%"))
			->firstRow();
		
		if (!$this->source->getNumRows()) {
			$this->warning("Cliente nao encontrado");
			$this->source->setWhere(null);
			$this->source->firstRow();
		}
		
	}
}