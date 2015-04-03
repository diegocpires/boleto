<?php
date_default_timezone_set("America/Sao_Paulo");

class SacadoTest extends PHPUnit_Framework_TestCase
{
    public function testNome()
    {
        $sacado = new \Pires\Boleto\Sacado();
        $sacado->setNome("Diego Pires");
        $this->assertEquals("Diego Pires", $sacado->getNome());
    }

    public function testEndereco()
    {
        $sacado = new \Pires\Boleto\Sacado();
        $sacado->setEndereco("Endereço Teste");
        $this->assertEquals("Endereço Teste", $sacado->getEndereco());
    }

    public function testEnderecoComplemento()
    {
        $sacado = new \Pires\Boleto\Sacado();
        $sacado->setEnderecoComplemento("Complemento");
        $this->assertEquals("Complemento", $sacado->getEnderecoComplemento());
    }

    /**
     * @expectedException Exception
     */
    public function testGetPropriedadeNaoExiste()
    {
        $sacado = new \Pires\Boleto\Sacado();
        $sacado->getQualquerCoisa();
    }

}