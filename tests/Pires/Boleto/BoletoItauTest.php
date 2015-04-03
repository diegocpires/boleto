<?php
date_default_timezone_set("America/Sao_Paulo");

class BoletoItauTest extends PHPUnit_Framework_TestCase
{
    public function testNumeroBanco()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $this->assertEquals(341, $boletoItau->getCodigoBanco());
    }

    public function testNumeroMoeda()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $this->assertEquals(9, $boletoItau->getNumeroMoeda());
    }

    public function testInstrucoes()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setInstrucao1('1');
        $boletoItau->setInstrucao2('2');
        $boletoItau->setInstrucao3('3');
        $boletoItau->setInstrucao4('4');
        $this->assertEquals('1', $boletoItau->getInstrucao1());
        $this->assertEquals('2', $boletoItau->getInstrucao2());
        $this->assertEquals('3', $boletoItau->getInstrucao3());
        $this->assertEquals('4', $boletoItau->getInstrucao4());
    }

    public function testObservacoes()
    {
        $boletoItau = new \Pires\Boleto\BoletoItau();
        $boletoItau->setObservacao1('1');
        $boletoItau->setObservacao2('2');
        $boletoItau->setObservacao3('3');
        $this->assertEquals('1', $boletoItau->getObservacao1());
        $this->assertEquals('2', $boletoItau->getObservacao2());
        $this->assertEquals('3', $boletoItau->getObservacao3());
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
        $cedente = new \Pires\Boleto\Cedente();

        $boletoItau->setCedente($cedente);
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
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setAgencia(5);

        $boletoItau->setCedente($cedente);
        $boletoItau->setValorBoleto(100);
        $boletoItau->setCarteira(175);
        $boletoItau->setNossoNumero(123);
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
        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setAgencia(1565);
        $cedente->setConta(13877);
        $boletoItau->setCedente($cedente);

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

        $cedente = new \Pires\Boleto\Cedente();
        $cedente->setAgencia(1565);
        $cedente->setConta(13877);
        $boletoItau->setCedente($cedente);

        $this->assertEquals("9", 
            $boletoItau->geraCodigoBarras()->getCodigoBarrasDv()
        );
    }

    public function testCodigoBarras44()
    {
        $ini = <<<INI
; Directory where templates are
boletodir = \Pires\Boleto\Boleto::DIR_TEMPLATE
view_path_boleto           = '[boletodir]/templates/'
; Twig options (default values)
[optionsboleto]
debug               = true
charset             = 'UTF-8'
base_template_class = 'Twig_Template'
strict_variables    = false
autoescape          = true
cache               = /tmp
auto_reload         = null
; Create a 'Twig_Loader_Filesystem' instance with 'view_path'
[loaderboleto Twig_Loader_Filesystem]
paths   = [view_path_boleto]
; Create a 'Twig_Environment' with the loader and options given
[twigboleto Twig_Environment]
loader  = [loaderboleto]
options = [optionsboleto]

[vencimento DateTime]
createFromFormat[] = [Y-m-d H:i:s, 2015-03-29 00:00:01]

[cedente \Pires\Boleto\Cedente]
setAgencia[] = 1565
setConta[] = 13877

[valores_padrao stdClass]
valor_boleto = 2952.95
carteira = 175
nosso_numero = 12345678
cedente = [cedente]
endereco = 'Rua Itapeva, 490'
identificacao = 'MWells'
cpf_cnpj = '12.058.755/0001-26'
data_vencimento = [vencimento]
INI;
        $container = new \Respect\Config\Container(parse_ini_string($ini, true));
        $boletoItau = new \Pires\Boleto\BoletoItau($container);
        $this->assertEquals("34199638200002952951751234567861565138771000", 
            $boletoItau->geraCodigoBarras()->getCodigoBarras44()
        );
    }

    public function testLinhaDigitavel()
    {
        $ini = <<<INI
; Directory where templates are
boletodir = \Pires\Boleto\Boleto::DIR_TEMPLATE
view_path_boleto           = '[boletodir]/templates/'
; Twig options (default values)
[optionsboleto]
debug               = true
charset             = 'UTF-8'
base_template_class = 'Twig_Template'
strict_variables    = false
autoescape          = true
cache               = /tmp
auto_reload         = null
; Create a 'Twig_Loader_Filesystem' instance with 'view_path'
[loaderboleto Twig_Loader_Filesystem]
paths   = [view_path_boleto]
; Create a 'Twig_Environment' with the loader and options given
[twigboleto Twig_Environment]
loader  = [loaderboleto]
options = [optionsboleto]

[vencimento DateTime]
createFromFormat[] = [Y-m-d H:i:s, 2015-03-29 00:00:01]

[cedente \Pires\Boleto\Cedente]
setAgencia[] = 1565
setConta[] = 13877

[valores_padrao stdClass]
valor_boleto = 2952.95
carteira = 175
nosso_numero = 12345678
cedente = [cedente]
endereco = 'Rua Itapeva, 490'
identificacao = 'MWells'
cpf_cnpj = '12.058.755/0001-26'
data_vencimento = [vencimento]
INI;
        $container = new \Respect\Config\Container(parse_ini_string($ini, true));
        $boletoItau = new \Pires\Boleto\BoletoItau($container);
        $boletoItau->geraCodigoBarras();
        $this->assertEquals("34191.75124 34567.861561 51387.710000 9 63820000295295", 
            $boletoItau->getLinhaDigitavel()
        );
    }

    public function testImagemCodigoBarras()
    {
        $ini = <<<INI
; Directory where templates are
boletodir = \Pires\Boleto\Boleto::DIR_TEMPLATE
view_path_boleto           = '[boletodir]/templates/'
; Twig options (default values)
[optionsboleto]
debug               = true
charset             = 'UTF-8'
base_template_class = 'Twig_Template'
strict_variables    = false
autoescape          = true
cache               = /tmp
auto_reload         = null
; Create a 'Twig_Loader_Filesystem' instance with 'view_path'
[loaderboleto Twig_Loader_Filesystem]
paths   = [view_path_boleto]
; Create a 'Twig_Environment' with the loader and options given
[twigboleto Twig_Environment]
loader  = [loaderboleto]
options = [optionsboleto]

[vencimento DateTime]
createFromFormat[] = [Y-m-d H:i:s, 2015-03-29 00:00:01]

[cedente \Pires\Boleto\Cedente]
setAgencia[] = 1565
setConta[] = 13877

[valores_padrao stdClass]
valor_boleto = 2952.95
carteira = 175
nosso_numero = 12345678
cedente = [cedente]
endereco = 'Rua Itapeva, 490'
identificacao = 'MWells'
cpf_cnpj = '12.058.755/0001-26'
data_vencimento = [vencimento]
INI;
        $container = new \Respect\Config\Container(parse_ini_string($ini, true));
        $boletoItau = new \Pires\Boleto\BoletoItau($container);
        $boletoItau->geraCodigoBarras();
        $this->assertEquals("c273a61757d36eaee38fab30b05eab404e714f45", 
            sha1($boletoItau->generateBarCode())
        );
    }
}
