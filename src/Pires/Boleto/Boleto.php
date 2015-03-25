<?php
namespace Pires\Boleto;

abstract class Boleto {

    /**
     * @var String Código do Banco
     */
    protected $codigo_banco;

    /**
     * @var String Código do Banco com DV
     */
    protected $codigo_banco_dv;

    /**
     * @var String Fator Vencimento
     */
    private $fator_vencimento;

    /**
     * @var String Código de Barras
     */
    protected $codigo_barras;

    /**
     * @var String Dígito Verificador do Código de Barras
     */
    protected $codigo_barras_dv;

    /**
     * @var String Código de Barras com 44 dígitos
     */
    protected $codigo_barras44;

    /**
     * @var int Número Moeda
     */
    protected $numero_moeda;

    /**
     * @var int Dias de prazo para pagamento do boleto
     */
    protected $prazo_pagamento;

    /**
     * @var float Taxa de emissão do boleto
     */
    protected $taxa_emissao;

    /**
     * @var \Datetime Data de vencimento do boleto
     */
    protected $data_vencimento;

    /**
     * @var \Datetime Data de emissão do boleto
     */
    protected $data_emissao;

    /**
     * @var \Datetime Data de processamento do boleto
     */
    protected $data_processamento;

    /**
     * @var float Valor do boleto
     */
    protected $valor_boleto;

    /**
     * @var String Nosso número
     */
    protected $nosso_numero;

    /**
     * @var String Número do documento
     */
    protected $numero_documento;

    /**
     * @var String Nome do Sacado
     */
    protected $sacado;

    /**
     * @var String Endereço do Sacado
     */
    protected $endereco_sacado;

    /**
     * @var String Complemento do endereço do Sacado
     */
    protected $endereco_complemento_sacado;

    /**
     * @var String Linha 1 da Observação
     */
    protected $observacao1;

    /**
     * @var String Linha 2 da Observação
     */
    protected $observacao2;

    /**
     * @var String Linha 3 da Observação
     */
    protected $observacao3;

    /**
     * @var String Linha 1 das Instruções
     */
    protected $instrucao1;

    /**
     * @var String Linha 2 das Instruções
     */
    protected $instrucao2;

    /**
     * @var String Linha 3 das Instruções
     */
    protected $instrucao3;

    /**
     * @var String Linha 4 das Instruções
     */
    protected $instrucao4;

    /**
     * @var int Quantidade
     */
    protected $quantidade;

    /**
     * @var float Valor Unitário
     */
    protected $valor_unitario;

    /**
     * @var String Aceite
     */
    protected $aceite;

    /**
     * @var String Espécie
     */
    protected $especie;

    /**
     * @var String Espécie Doc
     */
    protected $especie_doc;

    /**
     * @var String Agência
     */
    protected $agencia;

    /**
     * @var String Conta
     */
    protected $conta;

    /**
     * @var String Dígito Verificador
     */
    protected $conta_dv;

    /**
     * @var String Carteira
     */
    protected $carteira;

    /**
     * @var String Identificação Empresa
     */
    protected $identificacao;

    /**
     * @var String CPF - CNPJ
     */
    protected $cpf_cnpj;

    /**
     * @var String Endereço
     */
    protected $endereco;

    /**
     * @var String Cidade / UF
     */
    protected $cidade_uf;

    /**
     * @var String Cedente
     */
    protected $cedente;

    protected $twig;

    protected $nome_template;

    public function __construct(\Respect\Config\Container $container = null)
    {
        if (is_null($this->twig)) {
            if(!is_null($container)) {
                $this->twig = $container->twigboleto;
            } else {
                $container = new \Respect\Config\Container(__DIR__.'/config_default.ini');
                $this->twig = $container->twigboleto;
            }
        }

        if(isset($container->valores_padrao)) {
            $variaveis = get_object_vars($container->valores_padrao);
            foreach($variaveis as $chave => $valor) {
                $this->{$chave} = $valor;
            }
        }
    }

    public function __get($nome)
    {
        if (property_exists(self::nomeClasse(), $nome)) {
            return $this->getMagico($nome);
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __call($nome, $argumentos)
    {
        if(substr($nome, 0, 3) == "get")
        {
            $nomePropriedade = substr($nome, 3);
            return $this->getMagico($nomePropriedade, $argumentos);
        }
        if(substr($nome, 0, 3) == "set")
        {
            $nomePropriedade = substr($nome, 3);
            return $this->setMagico($nomePropriedade, $argumentos);
        }

        throw new \Exception("Método $nome na entidade $this->nomeEntidade não existe", 1);

    }

    protected function getMagico($nome, $argumentos = array())
    {
        $nomePropriedade = self::fromCamelCase($nome);
        if(property_exists(self::nomeClasse(), $nomePropriedade)) {
            return $this->{$nomePropriedade};
        }
    }
    protected function setMagico($nome, $argumentos)
    {
        $nomePropriedade = self::fromCamelCase($nome);
        if(property_exists(self::nomeClasse(), $nomePropriedade)) {
            // if($argumentos[0] != "")
            $this->{$nomePropriedade} = $argumentos[0];
            // return $this->{$nomePropriedade} = null;
        } else {
            throw new \Exception("Propriedade ".$nomePropriedade." não existe", 1);

        }
        return $this;
    }

    /**
     * Função para retornar o nome da classe
     * @return String Nome da Classe
     * @author Diego Pires <diego.pires@procorpoestetica.com.br>
     */
    public static function nomeClasse() {
        return get_called_class();
    }

    /**
     * Função responsável por converter camel case em underline
     * @param  String Valor a ser convertido
     * @param  String Separador a ser utilizado
     * @return String Valor convertido
     * @author Diego Pires <diego.pires@procorpoestetica.com.br>
     */
    public static function fromCamelCase($valor,$separador="_")
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $separador.'$0', $valor)));
    }

    protected function geraCodigoBanco()
    {
        $parte1 = substr($this->getCodigoBanco(), 0, 3);
        $parte2 = $this->getModulo11($parte1);
        $this->setCodigoBancoDv($parte1 . "-" . $parte2);
        return $this;
    }


    protected function geraModulo11($num, $base=9, $r=0)
    {
        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2 
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            return $digito;
        } elseif ($r == 1){
            $resto = $soma % 11;
            return $resto;
        }
    }

    protected function geraModulo10($num) { 
        $numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
            $temp = $numeros[$i] * $fator; 
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ 
                $temp0+=$v; 
            }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }
        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }
        return $digito;
    }

    public function geraFatorVencimento() 
    {
        if(is_null($this->getDataVencimento())) {
            throw new \InvalidArgumentException("Data de Vencimento não informada", 1);
        } else if(! $this->getDataVencimento() instanceof \DateTime) {
            throw new \InvalidArgumentException("Data de Vencimento não é do tipo DateTime", 1);
        } else {
            $ano = $this->getDataVencimento()->format("Y");
            $mes = $this->getDataVencimento()->format("m");
            $dia = $this->getDataVencimento()->format("d");
            return (abs(($this->dateToDays("1997","10","07")) - ($this->dateToDays($ano, $mes, $dia))));
        }
    }

    private function dateToDays($year,$month,$day) 
    {
        $century = substr($year, 0, 2);
        $year = substr($year, 2, 2);
        if ($month > 2) {
            $month -= 3;
        } else {
            $month += 9;
            if ($year) {
                $year--;
            } else {
                $year = 99;
                $century --;
            }
        }
        return ( floor((  146097 * $century)    /  4 ) +
                floor(( 1461 * $year)        /  4 ) +
                floor(( 153 * $month +  2) /  5 ) +
                    $day +  1721119);
    }

    public function geraCodigoBarras()
    {
        if(is_null($this->getCodigoBanco())) {
            throw new \InvalidArgumentException("Código do Banco não informado", 1);
        }
        if(is_null($this->getNumeroMoeda())) {
            throw new \InvalidArgumentException("Número da Moeda não informado", 1);
        }
        if(is_null($this->getValorBoleto())) {
            throw new \InvalidArgumentException("Valor do Boleto não informado", 1);
        }
        if(is_null($this->getCarteira())) {
            throw new \InvalidArgumentException("Valor do Boleto não informado", 1);
        }
        if(is_null($this->getNossoNumero())) {
            throw new \InvalidArgumentException("Nosso Número não informado", 1);
        }
        if(is_null($this->getAgencia())) {
            throw new \InvalidArgumentException("Agência não informada", 1);
        }
        if(is_null($this->getConta())) {
            throw new \InvalidArgumentException("Conta não informada", 1);
        }

        $codigo_barras = $this->getCodigoBanco();
        $codigo_barras .= $this->getNumeroMoeda();
        $codigo_barras .= $this->geraFatorVencimento();
        $codigo_barras .= $this->formataValor($this->getValorBoleto(),10,0,"valor");
        $codigo_barras .= $this->getCarteira();
        $codigo_barras .= $this->formataValor($this->getNossoNumero(),8,0);
        $codigo_barras .= $this->geraModulo10(
            $this->getAgencia().$this->getConta().$this->getCarteira().
            $this->formataValor($this->getNossoNumero(),8,0)
        );
        $codigo_barras .= $this->getAgencia();
        $codigo_barras .= $this->getConta();
        $codigo_barras .= $this->geraModulo10(
            $this->getAgencia().$this->getConta()
        );
        $codigo_barras .= '000';

        $this->setCodigoBarras($codigo_barras);
        $this->setCodigoBarrasDv($this->calculaDigitoVerificadorCodigoBarras());
        $this->setCodigoBarras44(substr($this->getCodigoBarras(),0,4).$this->getCodigoBarrasDv().substr($this->getCodigoBarras(),4,43));
        return $this;
    }

    private function formataValor($numero, $loop, $insert, $tipo = "geral") 
    {
        if ($tipo == "geral") {
            $numero = str_replace(",","",$numero);
            // $numero = str_replace(".","",$numero);
            while(strlen($numero)<$loop){
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "valor") {
            /*
                retira as virgulas formata o numero preenche com zeros
            */
            $numero = str_replace(",","",$numero);
            $numero = str_replace(".","",$numero);
            while(strlen($numero)<$loop){
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "convenio") {
            while(strlen($numero)<$loop){
                $numero = $numero . $insert;
            }
        }
        return $numero;
    }


    public function imprimeBoleto()
    {
        return $this->twig->render($this->nome_template, array("boleto"=>$this));
    }

    abstract public function calculaDigitoVerificadorCodigoBarras();

    abstract public function getLinhaDigitavel();
}