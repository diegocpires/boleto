<?php
date_default_timezone_set("America/Sao_Paulo");

class CedenteTest extends PHPUnit_Framework_TestCase
{
    public function testNome()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setNome("Diego Pires");
        $this->assertEquals("Diego Pires", $cedente->getNome());
    }

    public function testEndereco()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setEndereco("Endereço Teste");
        $this->assertEquals("Endereço Teste", $cedente->getEndereco());
    }

    public function testLogo()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setLogo("/var/www/tes");
        $this->assertEquals("/var/www/tes", $cedente->getLogo());
    }

    public function testAgencia()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setAgencia("123");
        $this->assertEquals("123", $cedente->getAgencia());
    }

    public function testConta()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setConta("00432");
        $this->assertEquals("00432", $cedente->getConta());
    }

    public function testCpfCnpj()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setCpfCnpj("12345678954");
        $this->assertEquals("12345678954", $cedente->getCpfCnpj());
    }

    public function testCidadeUf()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setCidadeUf("São Paulo - SP");
        $this->assertEquals("São Paulo - SP", $cedente->getCidadeUf());
    }

    /**
     * @expectedException Exception
     */
    public function testGetPropriedadeNaoExiste()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->getQualquerCoisa();
    }

    /**
     * @expectedException Exception
     */
    public function testMetodoInexistente()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->qualquerCoisa();
    }

}