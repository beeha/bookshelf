<?php

//create functions 

//show all papers
function papers_index()
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
    $stmt= $pdo->query("SELECT * FROM Papers ;");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $papers = array();
    
    foreach($rows as $row)
        {
            $paper = new Paper();
            $paper->subject= $row['subject'];
            $paper->year= $row['year'];
            $paper->semester= $row['semester'];
            $paper->type= $row['type'];
            $paper->costBuy= $row['costBuy'];
            
            array_push($papers,$paper);
        }
    $pdo->null;
    return $papers;    
}

function papers_show($id)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
    $stmt= $pdo->query("SELECT * FROM Papers WHERE id=".$id.";");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $paper = new Paper();
    
    $paper->subject= $row['subject'];
    $paper->year= $row['year'];
    $paper->semester= $row['semester'];
    $paper->type= $row['type'];
    $paper->costBuy= $row['costBuy'];
    
    $pdo->null;
    return $paper;
     
}


function papers_add($paper)
{
     $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
     $stmt= $pdo->prepare("INSERT INTO Papers (subject,year,semester,type,costBuy) VALUES (:subject,:year,:semester,:type,:costBuy);");
    
     $stmt->bindParam(':subject', $paper->subject, PDO::PARAM_STR);
     $stmt->bindParam(':year', $paper->year, PDO::PARAM_INT);
     $stmt->bindParam(':semester', $paper->semester, PDO::PARAM_INT);
     $stmt->bindParam(':type', $paper->type, PDO::PARAM_STR);
     $stmt->bindParam(':costBuy', $paper->costBuy, PDO::PARAM_INT);
     
     
    
     $stmt->execute();

     // Get the id of the new record and return the associated lecturer
     $id = $pdo->lastInsertId();
     $pdo->null;

     return papers_show($id);  
}


function papers_edit($id,$paper)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt = $pdo->prepare("UPDATE Papers SET subject=:subject,year=:year,semester=:semester,type=:type,costBuy=:costBuy WHERE id=:id;");
    
    
     $stmt->bindParam(':subject', $paper->subject, PDO::PARAM_STR);
     $stmt->bindParam(':year', $paper->year, PDO::PARAM_INT);
     $stmt->bindParam(':semester', $paper->semester, PDO::PARAM_INT);
     $stmt->bindParam(':type', $paper->type, PDO::PARAM_STR);
     $stmt->bindParam(':costBuy', $paper->costBuy, PDO::PARAM_INT);
     $stmt->bindParam(':id',$id, PDO::PARAM_INT);
        
     $stmt->execute();

     $pdo->null;
     return null;  
}

function  papers_remove($id)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt= $pdo->prepare("DELETE FROM Papers WHERE id=:id;");
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        
    $stmt->execute();
    $pdo->null;
    return null;
    
    
}


class Paper {
    public $subject;
    public $year;
    public $semester;
    public $type;
    public $costBuy;
    
    
}

?>