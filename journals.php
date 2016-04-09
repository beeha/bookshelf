<?php

function journals_index()
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
    $stmt= $pdo->query("SELECT * FROM Journals ;");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $journals = array();
    
    foreach($rows as $row)
        {
            $journal = new Journal();
            $journal->rank= $row['rank'];
            $journal->title= $row['title'];
            $journal->type= $row['type'];
            $journal->issn= $row['issn'];
            $journal->totalDocuments= $row['totalDocuments'];
            $journal->averageCitations= $row['averageCitations'];
            $journal->costBuy= $row['costBuy'];
            $journal->country= $row['country'];
            
            array_push($journals,$journal);
        }
    $pdo->null;
    return $journals;    
}

function journals_show($id)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root'); 
    $stmt= $pdo->query("SELECT * FROM Journals WHERE id=".$id.";");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $journal = new Journal();
    $journal->rank= $row['rank'];
    $journal->title= $row['title'];
    $journal->type= $row['type'];
    $journal->issn= $row['issn'];
    $journal->totalDocuments= $row['totalDocuments'];
    $journal->averageCitations= $row['averageCitations'];
    $journal->costBuy= $row['costBuy'];
    $journal->country= $row['country'];
    
    $pdo->null;
    return $journal;
}

function journals_add($journal)
{
     $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
     $stmt= $pdo->prepare("INSERT INTO Journals (rank,title,type,issn,totalDocuments,averageCitations,costBuy,country) VALUES (:rank,:title,:type,:issn,:totalDocuments,:averageCitations,:costBuy,:country);");
    
     $stmt->bindParam(':rank', $journal->rank, PDO::PARAM_INT);
     $stmt->bindParam(':title', $journal->title, PDO::PARAM_STR);
     $stmt->bindParam(':type', $journal->type, PDO::PARAM_STR);
     $stmt->bindParam(':issn', $journal->issn, PDO::PARAM_INT);
     $stmt->bindParam(':totalDocuments', $journal->totalDocuments, PDO::PARAM_INT);
     $stmt->bindParam(':averageCitations', $journal->averageCitations, PDO::PARAM_INT);
     $stmt->bindParam(':costBuy', $journal->costBuy, PDO::PARAM_INT);
     $stmt->bindParam(':country', $journal->country, PDO::PARAM_STR);
    
     $stmt->execute();

     // Get the id of the new record and return the associated lecturer
     $id = $pdo->lastInsertId();
     $pdo->null;

     return journals_show($id);  
}

function journals_edit($id,$journal)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt = $pdo->prepare("UPDATE Journals SET rank=:rank,title=:title,type=:type,issn=:issn,totalDocuments=:totalDocuments,averageCitations=:averageCitations,costBuy=:costBuy,country=:country WHERE id=:id;");
    
    
     $stmt->bindParam(':rank',$journal->rank, PDO::PARAM_INT);
     $stmt->bindParam(':title',$journal->title, PDO::PARAM_STR);
     $stmt->bindParam(':type',$journal->type, PDO::PARAM_STR);
     $stmt->bindParam(':issn',$journal->issn, PDO::PARAM_INT);
     $stmt->bindParam(':totalDocuments',$journal->totalDocuments, PDO::PARAM_INT);
     $stmt->bindParam(':averageCitations',$journal->averageCitations, PDO::PARAM_INT);
     $stmt->bindParam(':costBuy',$journal->costBuy, PDO::PARAM_INT);
     $stmt->bindParam(':country',$journal->country, PDO::PARAM_STR);
     $stmt->bindParam(':id',$id, PDO::PARAM_INT);
        
     $stmt->execute();

     $pdo->null;
     return null;  
}

function  journals_remove($id)
{
    $pdo = new PDO("mysql:host=localhost:8889;dbname=bookshelf", 'root', 'root');
    $stmt= $pdo->prepare("DELETE FROM Journals WHERE id=:id;");
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        
    $stmt->execute();
    $pdo->null;
    return null;
    
    
}





class Journal 
{
    public $rank;
    public $title; 
    public $type;
    public $issn;
    public $totalDocuments;
    public $averageCitations; 
    public $costBuy;
    public $country;
    
    
}

?>