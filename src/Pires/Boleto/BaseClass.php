<?php
namespace Pires\Boleto;

class BaseClass {

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

    public function getMagico($nome, $argumentos = array())
    {
        $nomePropriedade = self::fromCamelCase($nome);
        if(property_exists(self::nomeClasse(), $nomePropriedade)) {
            return $this->{$nomePropriedade};
        }
    }

    public function setMagico($nome, $argumentos)
    {
        $nomePropriedade = self::fromCamelCase($nome);
        if(property_exists(self::nomeClasse(), $nomePropriedade)) {
            $this->{$nomePropriedade} = $argumentos[0];
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
}