<?php

/**
 * User: Daniel Alves
 * Date: 02/11/2017
 * Time: 21:41
 */
class Empresa
{
    function __construct()
    {
        $this->municipio_id = new Municipio();
    }

    public $CLASS_NAME = 'empresa';
    public $CLASS_PREFIX = 'empresa_';
    public $id;
    public $razao;
    public $nome_proprietario;
    public $cnpj;
    public $cpf;
    public $area;
    public $municipio_id;
    public $email;
    public $telefone;
    public $ocupacao;
    public $primeiro_processo;
    public $primeiro_num;
    public $data_cadastro;
    public $observacoes;
}

class Municipio
{
    function __construct()
    {
        $this->uf = new UF();
    }

    public $CLASS_NAME = 'municipio';
    public $CLASS_PREFIX = 'municipio_';
    public $id;
    public $nome;
    public $uf;
}

class UF {
    function __construct()
    {
        $this->ocupacao = new Ocupacao();
        $this->tipoProcesso = new TipoProcesso();
    }

    public $CLASS_NAME = 'uf';
    public $CLASS_PREFIX = 'uf_';
    public $id;
    public $nome;
    public $ocupacao;
    public $tipoProcesso;
}

class Ocupacao
{
    public $CLASS_NAME = 'ocupacao';
    public $CLASS_PREFIX = 'ocupcao_';
    public $id;
    public $nome;
}

class TipoProcesso
{
    public $CLASS_NAME = 'tipo_processo';
    public $CLASS_PREFIX = 'tipo_processo_';
    public $id;
    public $nome;
}