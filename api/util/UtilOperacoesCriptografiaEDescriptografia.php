<?php 
    class UtilOperacoesCriptografiaEDescriptografia{
        public function __construct(){}

        public static function codificacaoBloco(Array $bloco, $n){
            $e = self::obterE($n);
            $resultado = array();
            for ($i = 0; $i < count($bloco); $i++) { 
                $blocoCodificado = fmod(($bloco[$i] ** $e), $n);
                array_push($resultado, $blocoCodificado);
            }
    
            return $resultado;
        }

        public static function caractereParaAsc($caractere){
            return ord($caractere) + 100;
        }

        public static function blocoNumerico($codigoASC, $n){
            $arrayCodigoAsc = str_split($codigoASC);
            $aux = null;
            $bloco = [];
            $i = 0;
    
            while ($i < count($arrayCodigoAsc)) {
                $aux .= $arrayCodigoAsc[$i];
                if(array_key_exists($i+1, $arrayCodigoAsc) && $arrayCodigoAsc[$i+1] == 0){
                    $aux .= $arrayCodigoAsc[$i+1];
                }
    
                if($aux >= $n){
                    if(array_key_exists($i+1, $arrayCodigoAsc) && (int) $arrayCodigoAsc[$i+1] === 0 && ($i+1) < count($arrayCodigoAsc)){
                        $ultimaCasaArrayCodigoAscPosicaoAtual = substr($aux, 0, -1);
                        $penultimaCasaArrayCodigoAscPosicaoAtual = substr($ultimaCasaArrayCodigoAscPosicaoAtual, 0, -1);
                        $bloco[] = $penultimaCasaArrayCodigoAscPosicaoAtual;
    
                        $aux = $arrayCodigoAsc[$i];
                    } else {
                        $bloco[] = substr($aux, 0, -1);
                        $i--;
                        $aux = null;
                    }
                }
    
                if($aux == 0){
                    $aux = null;
                }
    
                $i++;
            }
    
            if($aux != null){
                $bloco[] = $aux;
            }
    
            return $bloco;
        }

        public static function obterFI($numero){
            $fatores = self::algoritmoFermat($numero);
            return ($fatores['x'] - 1) * ($fatores['y'] - 1);
        }
    
        public static function obterE($numero){
            $y = self::obterFI($numero);
            for ($i = 2; $i < $y; $i++) { 
                $aux = euclidianoEstendido($i, $y);
                if($aux['MDC'] == 1){
                    return $i;
                }
            }
    
            return 0;
        }

        public static function calD($e, $n){
            $phi = self::obterFi($n);
            $d = self::invModular($e, $phi);
    
            return $d;
        }

        public static function invModular($b, $n){
            $arr = self::euclidianoEstendido($b, $n);
            if($arr['MDC'] == 1){
                if($arr['Alpha'] < 0){
                    $arr['Alpha'] += $n;
                }
    
                return $arr['Alpha'];
            } else {
                return 0;
            }
        }

        public static function algoritmoFermat($numero){
            $y = 0;
            $x = floor(sqrt($numero));
    
            /**
             * primeiro caso de que o numero dividido por 2, o resto é zero
             * Logo o numero/2, que esta no primeiro fator, multiplicado pelo falot y, da o numero inteiro
             */
            if($numero % 2 == 0){
                return array("x" => $numero/2, "y" => 2);
            }
    
            /**
             * segundo caso em que o piso da raiz quadrada, multiplicada por esse valor, é igual ao numero recebido
             * logo, os fatores x e y são os piso da raiz
             */
            if($x * $x == $numero){
                return array("x" => $x, "y" => $x);
            }
    
            /**
             * ultimo caso que devemos ir ate encontrar o y inteiro ou ate x = numero+1/2
             */
            while(true){
                $x++;
                $aux = ($x * $x) - $numero;
                $y = sqrt($aux);
                if($y == floor($y) || $x == ($numero + 1)/2){
                    return array("x" => $x - $y, "y" => $x + $y);
                }
            }
        }

        public static function somaAoAsc($c){
            return ord($c) + 100;
        }
    
        public static function numeroParaTexto($numero){
            return (string) $numero;
        }
    
        public static function retornaPalavra($texto){
            $aux = null;
            $ctexto = null;
            foreach (str_split($texto) as $t) {
                $aux = self::numeroParaTexto(somaAoAsc($t));
                $ctexto .= $aux;
            }
    
            return $ctexto;
        }
    
        public static function euclidianoEstendido($a, $b){
            $x = [0, 1];
            $y = [1, 0];
    
            $r = intval($a % $b);
    
            if($a == $b){
                $alfa = 2;
                $beta = -1;
    
                return ['MDC' => $b, 'Alpha' => $alfa, 'Beta' => $beta];
            } elseif ($r == 0) {
                $alfa = 1;
    
                $resultado = ( $b - ( $alfa * $a ) ) / $b;
            
                return ['MDC' => $b, 'Alpha' => $resultado, 'Beta' => $alfa];
            }
    
            $q = intval($a / $b);
            $xj = intval($x[1] - $q * $x[0]);
            $x[1] = $x[0];
            $x[0] = $xj;
    
            $yj = intval($y[1] - $q * $y[0]);
            $y[1] = $y[0];
            $y[0] = $yj;
    
            while($r != 0){
                $a = $b;
                $b = $r;
    
                $r = intval($a % $b);
                $q = intval($a / $b);
    
                $xj = intval($x[1] - $q * $x[0]);
                $x[1] = $x[0];
                $x[0] = $xj;
    
                $yj = intval($y[1] - $q * $y[0]);
                $y[1] = $y[0];
                $y[0] = $yj;
            }
    
            return ['MDC' => $b, 'Alpha' => $y[1], 'Beta' => $x[1]];
        }

        public static function decodificaBloco($n, $d, $a){
            $decodifica = self::potencializacao($a, $d, $n);
            if(end($decodifica) < 0){
                return end($decodifica) + $n;
            } else {
                return end($decodifica);
            }
        }
    
        public static function concatenaBloco($bloco){
            $numero = null;
            foreach($bloco as $b){
                $numero .= $b;
            }
    
            return $numero;
        }
    
        public static function converteNumeroParaCaractere($numero){
            $array = str_split($numero);
            $aux = "";
            $palavra = null;
    
            foreach($array as $n){
                if( mb_strlen($aux) < 3 ){
                    $aux .= $n;
                }
    
                if(mb_strlen($aux) == 3){
                    $palavra .= self::ascParaCaractere($aux);
                    $aux = "";
                }
            }
    
            return $palavra;
        }
    
        public static function ascParaCaractere($caractere){
            return chr($caractere - 100);
        }
    
        public static function potencializacao($x, $y, $n){
            $v = array();
    
            if($y == 0)
                return [0 => 1];
    
            for ($i = 0; $i <= $y; $i++) { 
                if( $i == 0 )
                    $v[$i] = 1;
                else 
                    $v[$i] = self::congruencia($v[$i - 1] * self::congruencia($x, $n), $n);
            }
    
            return $v;
        }

        public static function printVariavel($variavel){
            var_dump($variavel);
            die();
        }
    
        public static function congruencia($x, $n){
            $modulo = ($n + ($x % $n)) % $n;
    
            if($modulo > floor($n/2)){
                $modulo = $modulo - $n;
            }
    
            return $modulo;
        }
    }