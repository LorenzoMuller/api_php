<?php
    //cabeçalho
    header("Content-Type: application/json; charet=UTF-8");//DEFINE O TIPO DE RESPOSTA

    $metodo = $_SERVER["REQUEST_METHOD"];
    //echo "METODO DE REQUISIÇÃO: . $metodo";
   
    //RECUPERA O ARQUIVO JSON NA MESMA PASTA DO PROJETO
    $arquivo = 'usuarios.json';

    // VERIFICA SE  O ARQUIVO EXISTE, SE NÃO EXISTIR UM ARRAY VAZIO
    if(!file_exists($arquivo)){
        file_put_contents($arquivo,json_encode([],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    // LE O CONTEUDO DO ARQUIVO JSON
    $usuarios = json_decode(file_get_contents($arquivo),true);

    //CONTEUDO
    // $usuarios = [
    //     ["id"=>1, "nome"=>"Maria Luiza", "email"=>"marilu@gmail.com"],
    //     ["id"=>2, "nome"=>"João Pedro", "email"=>"jotape@gmail.com"]
    // ];

    switch ($metodo){
        case "GET":
            // echo "Aquisição do tipo GET";
            //CONVERTE PARA JSON E RETORNA
            echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        case "POST":
            // echo "Aquisição do tipo POST"; 
            // LER OS DADOS NO CORPO DA REQUISIÇAO 
            $dados = json_decode(file_get_contents("php://input"), true);
            // print_r($dados);  
            
            //VERIFICA SE OS CAMPOS OBRIGATORIOS FORAM PREENCHIDOS 
            if (!isset($dados["id"]) || !isset($dados["nome"]) || !isset($dados["email"])){
                http_response_code(400);
                echo json_encode(["erro" => "Dados incompletos."], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            //CRIA NOVO USUARIO
            $novo_usuario = [
                "id" => $dados["id"],
                "nome" => $dados["nome"],
                "email" => $dados["email"],
            ];

           // ADICIONA AO ARRAY DE USUARIOS
           $usuarios[] = $novo_usuario;

           //SALVA O ARRAY ATUALIZADO NO ARQUIVO JSON
           file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

           //RETORNA MENSAGEM DE SUCESSO
           echo json_encode(["mensagem" => "Usuário inserido com sucesso!", "usuarios" => $usuarios], JSON_UNESCAPED_UNICODE );
            break;

            //ADICIONA O NOVO USUARIO AO ARRAY EXISTENTE
            // array_push($usuarios, $novo_usuario);
            // echo json_encode('Usuário inserido com sucesso!', JSON_UNESCAPED_UNICODE):
            //print_r($usuarios);

            break;

        default:
            // echo "Método de aquisição não encontrado";
            // break;
            http_response_code(405); // método não permitido
            echo json_encode(["erro" => "Método não permitido!"], JSON_UNESCAPED_UNICODE);
            break;
    }
            
?>  