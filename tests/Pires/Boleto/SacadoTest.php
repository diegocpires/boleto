<?php
date_default_timezone_set("America/Sao_Paulo");

class SacadoTest extends PHPUnit_Framework_TestCase
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

    public function testConta()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setConta("213");
        $this->assertEquals("213", $cedente->getConta());
    }

    public function testAgencia()
    {
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setAgencia("0123");
        $this->assertEquals("0123", $cedente->getAgencia());
    }

}
