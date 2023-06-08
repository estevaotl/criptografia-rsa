<?php
    require_once "./criptografia/Criptografia.php";
    require_once "./descriptografia/Descriptografia.php";

    $metodo = $_SERVER[ 'REQUEST_METHOD' ];

    $rota = str_replace(
        dirname( $_SERVER[ 'PHP_SELF' ] ),
        '',
        $_SERVER[ 'REQUEST_URI' ]
    );

    if ( $metodo === 'POST' && preg_match( '/^\/trabalho_final\/api\/php\/criptografar\/?$/i', $rota) ) {
        $criptografia = new Criptografia();
        $resposta = null;
        header( 'Content-Type: application/json' );

        try {
            $blocosCriptografados = $criptografia->criptografar($_FILES['file'], $_POST['valorN']);
            http_response_code( 200 );
            $resposta = $blocosCriptografados;
        } catch (Exception $e) {
            http_response_code( 500 );
            $resposta = $e->getMessage();
        }

        die( json_encode($resposta, JSON_PRETTY_PRINT) );
    } elseif ( $metodo === 'POST' && preg_match( '/^\/trabalho_final\/api\/php\/descriptografar\/?$/i', $rota) ) {
        $criptografia = new Descriptografia();
        $resposta = null;
        header( 'Content-Type: application/json' );

        try {
            $blocosCriptografados = $criptografia->descriptografar($_FILES['file'], $_POST['valorN']);
            http_response_code( 200 );
            $resposta = $blocosCriptografados;
        } catch (Exception $e) {
            http_response_code( 500 );
            $resposta = $e->getMessage();
        }

        die( json_encode($resposta, JSON_PRETTY_PRINT) );
    } else {
        http_response_code( 404 );
        die( json_encode("Não encontrado 🤷‍♂️", JSON_PRETTY_PRINT) );
    }
?>