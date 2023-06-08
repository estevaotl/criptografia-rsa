
<?php
    $texto = "CARRO";
    $n = 187;
    // $d = 107;
    $e = obterE($n);
    $d = calD($e, $n);

    // para cifrar o texto em blocos
    $bloco = blocoNumerico(retornaPalavra($texto), $n);
    echo "----------- BLOCO CODIFICADO - INICIO ------------------"  . PHP_EOL;

    echo "----------- BLOCO CIFRADO DE TEXTO - INICIO ------------"  . PHP_EOL;

    foreach($bloco as $b){
        echo $b . " ";
    }

    echo PHP_EOL;

    echo "----------- BLOCO CIFRADO DE TEXTO - FIM ---------------"  . PHP_EOL;

    echo PHP_EOL;

    echo "----------- BLOCO CODIFICADO - INICIO ------------------"  . PHP_EOL;

    $resultadoCodificacaoBloco = codificacaoBloco($bloco, $n);
    var_dump($resultadoCodificacaoBloco); die();
    foreach($resultadoCodificacaoBloco as $result){
        echo $result . " ";
    }

    echo PHP_EOL;

    echo "----------- BLOCO CODIFICADO - FIM ---------------------" . PHP_EOL . PHP_EOL;

    echo "----------- BLOCO DECODIFICADO - INICIO  ---------------" . PHP_EOL;

    $blocoDecodificado = [];
    foreach($resultadoCodificacaoBloco as $b){
        $resultadoDecodificacao = decodificaBloco($n, $d, $b);
        $blocoDecodificado[] = $resultadoDecodificacao;
        echo $resultadoDecodificacao . " ";
    }

    echo PHP_EOL;

    echo "----------- BLOCO DECODIFICADO - FIM --------------------"  . PHP_EOL . PHP_EOL;

    echo "----------- RETORNANDO NUMERICO PARA PALAVRA - INICIO ---"  . PHP_EOL;


    $resultadoConversaoBlocoDecodificadoUnificado = converteNumeroParaCaractere(concatenaBloco($blocoDecodificado));
    echo $resultadoConversaoBlocoDecodificadoUnificado;

    echo PHP_EOL;

    echo "----------- RETORNANDO NUMERICO PARA PALAVRA - FIM ------"  . PHP_EOL;
    
    //   ===================================================================== 

    function calD($e, $n){
        $phi = obterFi($n);
        $d = invModular($e, $phi);

        return $d;
    }

    function invModular($b, $n){
        $arr = euclidianoEstendido($b, $n);
        if($arr['MDC'] == 1){
            if($arr['Alpha'] < 0){
                $arr['Alpha'] += $n;
            }

            return $arr['Alpha'];
        } else {
            return 0;
        }
    }

    function somaAoAsc($c){
        return ord($c) + 100;
    }

    function numeroParaTexto($numero){
        return (string) $numero;
    }

    function retornaPalavra($texto){
        $aux = null;
        $ctexto = null;
        foreach (str_split($texto) as $t) {
            $aux = numeroParaTexto(somaAoAsc($t));
            $ctexto .= $aux;
        }

        return $ctexto;
    }

    function codificacaoBloco(Array $bloco, $n){
        $e = obterE($n);
        $resultado = array();
        for ($i = 0; $i < count($bloco); $i++) { 
            $blocoCodificado = fmod(($bloco[$i] ** $e), $n);
            array_push($resultado, $blocoCodificado);
        }

        return $resultado;
    }

    function obterFI($numero){
        $fatores = algoritmoFermat($numero);
        return ($fatores['x'] - 1) * ($fatores['y'] - 1);
    }

    function obterE($numero){
        $y = obterFI($numero);
        for ($i = 2; $i < $y; $i++) { 
            $aux = euclidianoEstendido($i, $y);
            if($aux['MDC'] == 1){
                return $i;
            }
        }

        return 0;
    }

    function algoritmoFermat($numero){
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

    function euclidianoEstendido($a, $b){
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

		return ['MDC' => $b, 'Alpha' => $x[1], 'Beta' => $y[1]];
	}

    function decodificaBloco($n, $d, $a){
        $decodifica = potencializacao($a, $d, $n);
        if(end($decodifica) < 0){
            return end($decodifica) + $n;
        } else {
            return end($decodifica);
        }
    }

    function concatenaBloco($bloco){
        $numero = null;
        foreach($bloco as $b){
            $numero .= $b;
        }

        return $numero;
    }

    function converteNumeroParaCaractere($numero){
        $array = str_split($numero);
        $aux = "";
        $palavra = null;

        foreach($array as $n){
            if( mb_strlen($aux) < 3 ){
                $aux .= $n;
            }

            if(mb_strlen($aux) == 3){
                $palavra .= ascParaCaractere($aux);
                $aux = "";
            }
        }

        return $palavra;
    }

    function ascParaCaractere($caractere){
        return chr($caractere - 100);
    }

    function potencializacao($x, $y, $n){
        $v = array();

        if($y == 0)
            return [0 => 1];

        for ($i = 0; $i <= $y; $i++) { 
            if( $i == 0 )
                $v[$i] = 1;
            else 
                $v[$i] = congruencia($v[$i - 1] * congruencia($x, $n), $n);
        }

        return $v;
    }

    function congruencia($x, $n){
        $modulo = ($n + ($x % $n)) % $n;

        if($modulo > floor($n/2)){
            $modulo = $modulo - $n;
        }

        return $modulo;
    }

    function blocoNumerico($codigoASC, $n){
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
?>