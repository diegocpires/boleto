<?php
namespace Pires\Boleto;

class BoletoItau extends Boleto {

    
    public function __construct(\Respect\Config\Container $container = null)
    {
        $this->setCodigoBanco(341)
            ->geraCodigoBanco()
            ->setNumeroMoeda(9)
            ->setNomeTemplate('boleto_itau.html.twig');

        $this->logo_banco = base64_encode(fread(fopen(__DIR__.'/templates/imagens/logoitau.jpg', 'r'),filesize(__DIR__.'/templates/imagens/logoitau.jpg')));
        

        parent::__construct($container);
    }

    public function calculaDigitoVerificadorCodigoBarras() {
        $resto2 = $this->geraModulo11($this->getCodigoBarras(), 9, 1);
        $digito = 11 - $resto2;
         if ($digito == 0 || $digito == 1 || $digito == 10  || $digito == 11) {
            $dv = 1;
         } else {
            $dv = $digito;
         }
         return $dv;
    }

    public function getLinhaDigitavel()
    {
        $codigo = $this->getCodigoBarras44();

        // campo 1
        $banco    = substr($codigo,0,3);
        $moeda    = substr($codigo,3,1);
        $ccc      = substr($codigo,19,3);
        $ddnnum   = substr($codigo,22,2);
        $dv1      = $this->geraModulo10($banco.$moeda.$ccc.$ddnnum);
        // campo 2
        $resnnum  = substr($codigo,24,6);
        $dac1     = substr($codigo,30,1);//modulo_10($agencia.$conta.$carteira.$nnum);
        $dddag    = substr($codigo,31,3);
        $dv2      = $this->geraModulo10($resnnum.$dac1.$dddag);
        // campo 3
        $resag    = substr($codigo,34,1);
        $contadac = substr($codigo,35,6); //substr($codigo,35,5).modulo_10(substr($codigo,35,5));
        $zeros    = substr($codigo,41,3);
        $dv3      = $this->geraModulo10($resag.$contadac.$zeros);
        // campo 4
        $dv4      = substr($codigo,4,1);
        // campo 5
        $fator    = substr($codigo,5,4);
        $valor    = substr($codigo,9,10);

        $campo1 = substr($banco.$moeda.$ccc.$ddnnum.$dv1,0,5) . '.' . substr($banco.$moeda.$ccc.$ddnnum.$dv1,5,5);
        $campo2 = substr($resnnum.$dac1.$dddag.$dv2,0,5) . '.' . substr($resnnum.$dac1.$dddag.$dv2,5,6);
        $campo3 = substr($resag.$contadac.$zeros.$dv3,0,5) . '.' . substr($resag.$contadac.$zeros.$dv3,5,6);
        $campo4 = $dv4;
        $campo5 = $fator.$valor;

        return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
    }
}