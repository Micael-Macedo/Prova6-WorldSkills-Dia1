<?php
$db =  new mysqli("localhost", "root", 1234, "myProdutos", 3310);

function sql($sql){
    global $db;
    $result = $db->query($sql);
    $array = [];
    while ($produto = $result->fetch_object()) {
        array_push($array, $produto);
    }
    return $array;
}


function buscarUsuario($usuario){
    $sql = "Select * from usuarios where email = '$usuario'";
    $result = json_encode(sql($sql));
    if(is_null($result)){
        $result = false;
    }else{
        http_response_code(200);
        return $result;
    }
}
function buscarProduto($produto){
    $sql = "Select * from $produto";
    return json_encode(sql($sql));
}
function createUsuario($usuario){
    $sql = "INSERT INTO `myprodutos`.`usuarios` (`email`, `senha`, `nivelAcesso`) 
    VALUES ('$usuario->email', '$usuario->senha', '$usuario->nivelAcesso')";
    sql($sql);
    return http_response_code(200);

}
function createProduto($produto){
    $sql = "INSERT INTO `myprodutos`.`produtos` (`nome`, `categoriaId`, `fornecedorId`, `imagem`, `localEstoque`, `qtdDisponivel`) 
    VALUES ('$produto->nome', '$produto->categoria', '$produto->fornecedor', '$produto->imagem', '$produto->localEstoque', '$produto->qtdDisponivel');";
    sql($sql);
    return http_response_code(200);

}
function deleteProduto($produto){
    $sql = "delete from Produtos where id = $produto->id";
    sql($sql);
    return http_response_code(200);
}
function updateProduto($produto){
    $sql = "UPDATE produtos
    SET nome = $produto->nome, categoria = $produto->categoria, fornecedor =  $produto->fornecedor, 
        imagem = $produto->imagem, localEstoque = $produto->localEstoque, 
        qtdDisponivel = $produto->qtdDisponivel
    WHERE id = $produto->id";
    sql($sql);
    return http_response_code(200);
}
function feedbackProduto($avaliacao){
    $sql = "INSERT INTO avalicao(
    'usuarioId',
    'comentario',
    'classificacao') values 
    $avaliacao->usuario,
     $avaliacao->comentario, 
     $avaliacao->classificacao";
    sql($sql);
    return http_response_code(200);

}