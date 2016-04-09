<?php



function get_token_list()
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt = $pdo->query("SELECT * from Tokens;");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $token_list = array();
    
    foreach($rows as $row)
    {
        array_push($token_list, $row['token']);
    }
    
    $pdo->null;
    
    return $token_list;  
}

function generate_random_string($length)
{
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rs= '';
    
    for($i = 0; $i< $length; $i++)
    {
        $pos = rand(0,strlen($chars)-1);
        $rs = $rs. $chars[$pos];
        
    }
    
    return $rs;
    
}



//loads a token from database and returns the id of the corresponding person
function token_check($token)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt = $pdo->query("SELECT * FROM Tokens WHERE token='$token';");
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo->null;
    return $rows["user_id"];
}



//assigns a person to a new token
//to ensure it is unique, new tokens are created in a loop which ends only when the new token does not yest exists in db
function token_create($user_id)
{
    $current_tokens = get_token_list();
    $token = generate_random_string(16);
    while (in_array( $token, $current_tokens ))
    {
        $token = generate_random_string(16);
    } 
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt = $pdo->prepare("INSERT INTO Tokens(token,user_id) VALUES (:token,:user_id);");
    $stmt->bindParam(':token',$token, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT );
    
    $stmt->execute();
    
    $pdo->null;
     
    return array("token"=> $token);
}



?>