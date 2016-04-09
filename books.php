<?php

//create functions 

//show all books
function books_index()
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
    $stmt= $pdo->query("SELECT * FROM Books ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $books = array();
    
    foreach($rows as $row)
        {
            $book = new Book();
            $book->id = $row['id'];
            $book->title= $row['title'];
            $book->author= $row['author'];
            $book->image= $row['image'];
            $book->costBuy= $row['costBuy'];
            
            array_push($books,$book);
        }
    $pdo->null;
    return $books;    
}


//show one book when view is clicked
function books_show($id)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
    $stmt= $pdo->query("SELECT * FROM Books WHERE id=".$id.";");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $book = new Book();
    $book->title= $row['title'];
    $book->author= $row['author'];
    $book->fiction= $row['fiction'];
    $book->language= $row['language'];
    $book->description= $row['description'];
    $book->quantity= $row['quantity'];
    $book->costBuy= $row['costBuy'];
    $book->costBorrow= $row['costBorrow'];
    
    $pdo->null;
    return $book;
     
}

//
function books_add($book)
{
     $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
     $stmt= $pdo->prepare("INSERT INTO Books (title,author,quantity,fiction,language,costBuy,costBorrow,description,image) VALUES (:title,:author,:quantity,:fiction,:language,:costBuy,:costBorrow,:description,:image);");
     $stmt->bindParam(':title', $book->title, PDO::PARAM_STR);
     $stmt->bindParam(':author', $book->author, PDO::PARAM_STR);
     $stmt->bindParam(':quantity', $book->quantity, PDO::PARAM_INT);
     $stmt->bindParam(':fiction', $book->fiction, PDO::PARAM_STR);
     $stmt->bindParam(':language', $book->language, PDO::PARAM_STR);
     $stmt->bindParam(':costBuy', $book->costBuy, PDO::PARAM_INT);
     $stmt->bindParam(':costBorrow', $book->costBorrow, PDO::PARAM_INT);
     $stmt->bindParam(':description', $book->description, PDO::PARAM_STR);
     $stmt->bindParam(':image', $book->image, PDO::PARAM_STR);
    
    
     $stmt->execute();

     // Get the id of the new record and return the associated lecturer
     $id = $pdo->lastInsertId();
     $pdo->null;

     return books_show($id);  
}

function books_edit($id,$book)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt = $pdo->prepare("UPDATE Books SET title=:title,author=:author,quantity=:quantity,fiction=:fiction,language=:language,costBuy=:costBuy,costBorrow=:costBorrow,description=:description,image=:image WHERE id=:id;");
    
    
     $stmt->bindParam(':title',$book->title, PDO::PARAM_STR);
     $stmt->bindParam(':author',$book->author, PDO::PARAM_STR);
     $stmt->bindParam(':quantity',$book->quantity, PDO::PARAM_INT);
     $stmt->bindParam(':fiction',$book->fiction, PDO::PARAM_STR);
     $stmt->bindParam(':language',$book->language, PDO::PARAM_STR);
     $stmt->bindParam(':costBuy',$book->costBuy, PDO::PARAM_INT);
     $stmt->bindParam(':costBorrow',$book->costBorrow, PDO::PARAM_INT);
     $stmt->bindParam(':description',$book->description, PDO::PARAM_STR);
     $stmt->bindParam(':image',$book->image, PDO::PARAM_STR);
     $stmt->bindParam(':id',$id, PDO::PARAM_INT);
        
     $stmt->execute();

     $pdo->null;
     return null;  
}

function  books_remove($id)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt= $pdo->prepare("DELETE FROM Books WHERE id=:id;");
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        
    $stmt->execute();
    $pdo->null;
    return null;
    
    
}

class Book {
    public $id;
    public $title;
    public $author;
    public $fiction;
    public $language;
    public $description; 
    public $quantity;
    public $costBuy;
    public $costBorrow;
    public $image;
    
}




?>