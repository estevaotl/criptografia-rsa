<?php
    require_once "./util/UtilOperacoesCriptografiaEDescriptografia.php";

    class Criptografia{
        public function __construct(){}

        public function criptografar($arquivo, $valorN){
            if(count($arquivo) <= 0){
                throw new Exception("Arquivo não enviado.");
            }

            // Caminho para o arquivo TXT
            $arquivo = $arquivo['tmp_name'];

            // Verifica se o arquivo existe
            if (file_exists($arquivo)) {
                // Abre o arquivo para leitura
                $handle = fopen($arquivo, "r");

                // Lê o arquivo linha por linha
                $linhas = "";
                while (($linha = fgets($handle)) !== false) {
                    // Remove quebras de linha ao final de cada linha
                    $linha = rtrim($linha, "\r\n");

                    // Exibe a linha na página web
                    $linhas .= htmlspecialchars($linha) . "<br>";
                }

                // Fecha o arquivo
                fclose($handle);

                $linhasDivididas = explode("<br>", $linhas);

                return codificar(array_filter($linhasDivididas), $valorN);
            } else {
                echo "O arquivo não existe.";
            }
        }
    }

    function codificar($linhasDivididas, $valorN){
        $blocosCodificados = array();
        foreach ($linhasDivididas as $linha) {
            $texto = str_split($linha);
            $codigo = "";
            for ($i = 0; $i < count($texto); $i++) {
                $codigo .= UtilOperacoesCriptografiaEDescriptografia::caractereParaAsc($texto[$i]);
            }

            $bloco = blocoNumerico($codigo, $valorN);
            $blocoCodificado = ["palavra" => $linha, "blocoCodificado" => codificacaoBloco($bloco, $valorN)];
            array_push($blocosCodificados, $blocoCodificado);
        }

        return $blocosCodificados;
    }