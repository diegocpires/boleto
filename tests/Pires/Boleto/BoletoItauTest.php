<?php
class BoletoItauTest extends PHPUnit_Framework_TestCase
{
    public function testNumeroBaco()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $this->assertEquals(341, $boletoItau->getCodigoBanco());
    }

    public function testNumeroMoeda()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $this->assertEquals(9, $boletoItau->getNumeroMoeda());
    }

    public function testFatorVencimento()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setDataVencimento(
            \DateTime::createFromFormat("Y-m-d", date("2015-03-24"))
        );
        $this->assertEquals(6377.0, $boletoItau->geraFatorVencimento());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDataVencimentoIncorreta()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setDataVencimento(
            date("2015-03-24")
        );
        $boletoItau->geraFatorVencimento();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDataVencimentoEmBranco()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->geraFatorVencimento();
        // $this->assertEquals(-6377.0, $boletoItau->geraFatorVencimento());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGeraBarrasSemValorBoleto()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->geraCodigoBarras();
        // $this->assertEquals(-6377.0, $boletoItau->geraFatorVencimento());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGeraBarrasSemCarteira()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setValorBoleto(100);
        $boletoItau->geraCodigoBarras();
        // $this->assertEquals(-6377.0, $boletoItau->geraFatorVencimento());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGeraBarrasSemNossoNumero()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setValorBoleto(100);
        $boletoItau->setCarteira(175);
        $boletoItau->geraCodigoBarras();
        // $this->assertEquals(-6377.0, $boletoItau->geraFatorVencimento());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGeraBarrasSemAgencia()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setValorBoleto(100);
        $boletoItau->setCarteira(175);
        $boletoItau->setNossoNumero(123);
        $boletoItau->geraCodigoBarras();
        // $this->assertEquals(-6377.0, $boletoItau->geraFatorVencimento());
    }

    /**
      * @expectedException InvalidArgumentException
      */
    public function testGeraBarrasSemConta()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setValorBoleto(100);
        $boletoItau->setCarteira(175);
        $boletoItau->setNossoNumero(123);
        $boletoItau->setAgencia(5);
        $boletoItau->geraCodigoBarras();
        // $this->assertEquals(-6377.0, $boletoItau->geraFatorVencimento());
    }

    public function testGeraBarras()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setDataVencimento(\DateTime::createFromFormat("Y-m-d", "2015-03-29"));
        $boletoItau->setValorBoleto(2952.95);
        $boletoItau->setCarteira(175);
        $boletoItau->setNossoNumero(12345678);
        $boletoItau->setAgencia(1565);
        $boletoItau->setConta(13877);
        $this->assertEquals("3419638200002952951751234567861565138771000",
            $boletoItau->geraCodigoBarras()->getCodigoBarras()
        );
    }


    public function testCodigoBarrasDv()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setDataVencimento(\DateTime::createFromFormat("Y-m-d", "2015-03-29"));
        $boletoItau->setValorBoleto(2952.95);
        $boletoItau->setCarteira(175);
        $boletoItau->setNossoNumero(12345678);
        $boletoItau->setAgencia(1565);
        $boletoItau->setConta(13877);
        $this->assertEquals("9", 
            $boletoItau->geraCodigoBarras()->getCodigoBarrasDv()
        );
    }

    public function testCodigoBarras44()
    {
        $container = new \Respect\Config\Container(CONFIG_DIR.'/tests/config_testes.ini');
        $boletoItau = new \Pires\Boleto\BoletoItau($container);
        $this->assertEquals("34199638200002952951751234567861565138771000", 
            $boletoItau->geraCodigoBarras()->getCodigoBarras44()
        );
    }

    public function testLinhaDigitavel()
    {
        $container = new \Respect\Config\Container(CONFIG_DIR.'/tests/config_testes.ini');
        $boletoItau = new \Pires\Boleto\BoletoItau($container);
        $boletoItau->geraCodigoBarras();
        $this->assertEquals("34191.75124 34567.861561 51387.710000 9 63820000295295", 
            $boletoItau->getLinhaDigitavel()
        );
    }

    public function testImpressao()
    {
        $container = new \Respect\Config\Container(CONFIG_DIR.'/tests/config_testes.ini');
        $boletoItau = new \Pires\Boleto\BoletoItau($container);
        $boletoItau->geraCodigoBarras();
        echo $boletoItau->imprimeBoleto();
    }
}