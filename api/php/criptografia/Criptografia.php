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

                $blocoCodificado = codificar(array_filter($linhasDivididas), $valorN);

                // Obtém o diretório pai do diretório atual
                $diretorio_pai = dirname(dirname(dirname(dirname(__FILE__))));

                // Constrói o caminho absoluto para o arquivo na raiz do projeto
                $caminho_arquivo = $diretorio_pai . '/arquivoDescriptografia.txt';

                UtilOperacoesCriptografiaEDescriptografia::escreverBlocoCodificadoEmArquivo($blocoCodificado, $caminho_arquivo);

                return $blocoCodificado;
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

            $bloco = UtilOperacoesCriptografiaEDescriptografia::blocoNumerico($codigo, $valorN);
            $blocoCodificado = ["palavra" => $linha, "blocosCodificados" => UtilOperacoesCriptografiaEDescriptografia::codificacaoBloco($bloco, $valorN)];
            array_push($blocosCodificados, $blocoCodificado);
        }

        return $blocosCodificados;
    }