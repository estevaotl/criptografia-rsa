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
                // Lê o conteúdo do arquivo em um array
                $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);
                $blocosCodificados = [];
                
                // Percorre cada linha e separa os valores por vírgulas
                foreach ($linhas as $linha) {
                    // Remove a tag <br /> e quebra os valores em um array
                    $valores = explode(',', str_replace(PHP_EOL, '', $linha));
                    $blocosCodificados[] = $valores;
                }
                
                return $this->decodificar(array_filter($blocosCodificados), $valorN);
            } else {
                echo "O arquivo não existe.";
            }
        }

        public function decodificar($blocosCodificados, $valorN){
            $e = UtilOperacoesCriptografiaEDescriptografia::obterE($valorN);
            $d = UtilOperacoesCriptografiaEDescriptografia::calD($e, $valorN);

            $blocosDecodificados = array();
            foreach($blocosCodificados as $key => $blocoCodificado){
                $resultadoDecodificacao = [];
                foreach ($blocoCodificado as $bloco) {
                    $resultadoDecodificacao[] =  UtilOperacoesCriptografiaEDescriptografia::decodificaBloco($valorN, $d, $bloco);
                }
                
                $resultadoConversaoBlocoDecodificadoUnificado = UtilOperacoesCriptografiaEDescriptografia::converteNumeroParaCaractere(UtilOperacoesCriptografiaEDescriptografia::concatenaBloco($resultadoDecodificacao));
                
                $blocoCodificado = ["palavra" => $resultadoConversaoBlocoDecodificadoUnificado, "blocosCodificados" => $resultadoDecodificacao];
                
                $blocosDecodificados[$key] = $blocoCodificado;
            }

            return $blocosDecodificados;
        }

    }