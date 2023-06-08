<?php
    require_once "./util/UtilOperacoesCriptografiaEDescriptografia.php";

    class Descriptografia{
        public function __construct(){}

        public function descriptografar($arquivo, $valorN){
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

                return $this->decodificar(array_filter($linhasDivididas), $valorN);
            } else {
                echo "O arquivo não existe.";
            }
        }

        public function decodificar($linhasDivididas, $valorN){
            $e = UtilOperacoesCriptografiaEDescriptografia::obterE($valorN);
            $d = UtilOperacoesCriptografiaEDescriptografia::calD($e, $valorN);
    
            $blocosDecodificados = array();
            foreach($linhasDivididas as $linha){
                $blocoCodificado = UtilOperacoesCriptografiaEDescriptografia::blocoNumerico($linha, $valorN);

                UtilOperacoesCriptografiaEDescriptografia::printVariavel($blocoCodificado);
                $resultadoDecodificacao = [];
                foreach ($blocoCodificado as $bloco) {
                    $resultadoDecodificacao[] = UtilOperacoesCriptografiaEDescriptografia::decodificaBloco($valorN, $d, $bloco);
                }

                $resultadoConversaoBlocoDecodificadoUnificado = UtilOperacoesCriptografiaEDescriptografia::converteNumeroParaCaractere(UtilOperacoesCriptografiaEDescriptografia::concatenaBloco($resultadoDecodificacao));
                $blocosDecodificados[] = $resultadoConversaoBlocoDecodificadoUnificado;
            }

            // return $blocosCodificados;
        }

    }