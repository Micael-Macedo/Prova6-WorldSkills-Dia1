<?php

include_once("senha.php");
class jwt{
    public static function base64encode($data)
    {
        return str_replace(['+','/','=', '\\'], ['-','_','','['], base64_encode($data));
    }
 
    public static function base64decode($string) 
    {
        return base64_decode(str_replace(['-','_','['], ['+','/','\\'], $string));
    }

    public static function encode(array $payload)
    {
        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);
        $payload = json_encode($payload);
        $header_payload = static::base64encode($header) . '.'. 
                            static::base64encode($payload);
 
        $signature = hash_hmac('sha256', $header_payload, SENHA, true);
         
        return 
            static::base64encode($header) . '.' .
            static::base64encode($payload) . '.' .
            static::base64encode($signature);
    }
    public static function decode(string $token)
    {
        if($token != ''){
            $token = explode('.', $token);
            if(count($token) == 3){
                $payload = static::base64decode($token[1]);
         
                $signature = static::base64decode($token[2]);
         
                $header_payload = $token[0] . '.' . $token[1];
                if (hash_hmac('sha256', $header_payload, SENHA, true) !== $signature) {
                    http_response_code(403);
                    echo json_encode((object) ['message' => "Credenciais inválidas"]);
                    return false;
                }
                return json_decode($payload, true);
            }
        }else{
            http_response_code(401);
            echo json_encode((object) ['message' => "“Necessário estar autenticado no sistema"]);
            return false;
        }
        
    }
}