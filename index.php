<?php
error_reporting(E_ERROR | E_PARSE);

include("./jwt/index.php");
include_once("./database/mysql.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$url = explode('/', $_GET['url']);

$header = getallheaders();
$jwt = new jwt();

$body = json_decode(file_get_contents('php://input'));

$page = $url[0];
$metodo = $_SERVER["REQUEST_METHOD"];
$autenticado = false;

if ($header['Authorization'] != '') {
    if ($jwt->decode($header['Authorization']) != false) {
        $autenticado = true;
    } else {
        $autenticado = false;
    }
}

if ($metodo === "GET") {
    if ($autenticado) {
    } else {
        switch ($page) {
        }
    }
}
if ($metodo === "POST") {
    switch ($page) {
        case 'produto':
            if ($_COOKIE['nivelAcesso'] >= 2) {
                $produto["nome"] = $body->nome;
                $produto["categoria"] = $body->categoria;
                $produto["fornecedor"] = $body->fornecedor;
                $produto["localEstoque"] = $body->localEstoque;
                $produto["qtdDisponivel"] = $body->qtdDisponivel;
                $objeto = (object) $produto;
                echo createProduto($objeto);
            }
            break;
        case 'feedback':
            if ($_COOKIE['nivelAcesso'] == 1) {
                $avaliacao["usuario"] = $body->usuario;
                $avaliacao["produto"] = $body->produto;
                $avaliacao["comentario"] = $body->comentario;
                $avaliacao["classificacao"] = $body->classificacao;
                echo feedbackProduto($body->avaliacao);
            }

            break;
        case 'logout':
            if ($_COOKIE['nivelAcesso'] >= 1) {
                $_COOKIE['nivelAcesso'] = 0;
            }
            break;
        case 'login':
            $body->senha = $jwt->base64encode($body->senha);
            $token = $jwt->encode((array) $body);
            $pessoa = json_decode(buscarUsuario($body->email));
            $tokenUser = $jwt->encode($pessoa);
            echo  json_encode($token);
            $_COOKIE['nivelAcesso'] = $pessoa->nivelAcesso;

            break;
        case 'produtos':
            echo buscarProduto($page);
            break;
        case 'logout':

        case 'usuario':
            echo createUsuario($body);
        default:
            http_response_code(404);
            break;
    }
}
if ($metodo === "DELETE") {
    if ($autenticado) {
        if ($_COOKIE['nivelAcesso'] >= 3) {
            echo deleteProduto($body->produto);
        }
    }
}
if ($metodo === "PUT") {
    if ($autenticado) {
        if ($_COOKIE['nivelAcesso'] >= 2) {
            echo updateProduto($body->produto);
        }
    }
}
