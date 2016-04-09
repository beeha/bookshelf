<?php
//Token should only be created if correct username and password is supplied
//Returns corresponding id of of authenticated user
function authenticate($email, $password)
{
    
     $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
     $stmt= $pdo->query("SELECT * FROM Users WHERE email='$email' AND password='$password';");
     $row= $stmt->fetch(PDO::FETCH_ASSOC);
     return $row['id'];
}


   function users_index()
    {
     $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');  
        $stmt= $pdo->query("SELECT * FROM Users;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $users = array();
        
        foreach($rows as $row)
        {
            $user = new User();
            $user->name= $row['name'];
            $user->surname= $row['surname'];
            $user->email= $row['email'];
            $user->user_level= $row['user_level'];
            
            array_push($users,$user);
        }
        
        $pdo->null;
        return $users; 
        
    }

    function users_show($id)
    {
     $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');  
     $stmt= $pdo->query("SELECT * FROM Users WHERE id=".$id.";");
     $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
            $user = new User();
            $user->name= $row['name'];
            $user->surname= $row['surname'];
            $user->email= $row['email'];
            $user->password= $row['password'];
            $user->user_level= $row['user_level'];
            
       
        
        $pdo->null;
        return $user; 
        
    }


    function users_add($user)
    {
        
         $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');  
         $stmt= $pdo->prepare("INSERT INTO Users (name,surname,email,password,user_level) VALUES (:name,:surname,:email,:password,:user_level;");
         $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
         $stmt->bindParam(':surname', $user->surname, PDO::PARAM_STR);
         $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
         $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
         $stmt->bindParam(':user_level', $user->user_level, PDO::PARAM_STR);
        
         $stmt->execute();

         // Get the id of the new record and return the associated user
         $id = $pdo->lastInsertId();
         $pdo->null;

         return users_show($id); 
        
    }



class User {
    public $name;
    public $surname;
    public $email;
    public $password;
    public $user_level;
    
    
}

?>