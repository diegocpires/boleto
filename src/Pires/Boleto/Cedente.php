<?php
namespace Pires\Boleto;

/**
 * Classe base para geração de boletos bancários
 */
class Cedente extends BaseClass {
    /**
     * @var string Nome do Sacado
     */
    protected $nome;

    /**
     * @var string Endereço do Sacado
     */
    protected $endereco;

    /**
     * @var string Path para o logo da empresa
     */
    protected $logo;

    /**
     * @var string Agência
     */
    protected $agencia;

    /**
     * @var string Conta
     */
    protected $conta;

    /**
     * @var string CPF - CNPJ
     */
    protected $cpf_cnpj;

    /**
     * @var string Cidade / UF
     */
    protected $cidade_uf;


    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    public function setCpfCnpj($documento)
    {
        $this->cpf_cnpj = $documento;
        return $this;
    }

    public function setCidadeUf($cidade_uf)
    {
        $this->cidade_uf = $cidade_uf;
        return $this;
    }

}