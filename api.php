<?php
    require_once __DIR__.'/vendor/autoload.php';
    require_once 'books.php';
    require_once 'papers.php';
    require_once 'journals.php';
    require_once 'users.php';
    require_once 'tokens.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, HEAD,DELETE,PUT, OPTIONS");
    header("Access-Control-Allow-Headers:Content-Type, X-Bearer-Token");

    // Silex support for accessing the HTTP Request and Response
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\ParameterBag;

    date_default_timezone_set("Africa/Johannesburg");
    $app = new Silex\Application();

    // After receiving a request, before doing anything else
   $app->before(function (Request $request)
    {
        $method = $request->getMethod();
        $route = $request->get('_route');
        if ($route != "POST_tokens" && $method != "OPTIONS")
        {
            //get token from header
            $token = $request->headers->get('X-Bearer-Token');
            $user_id = token_check($token);
            
            if ($user_id != null)
            {
                $request->headers->set('X-User',$user_id);  
            }else
            {
                return new Response('Forbidden',403);
            }
            
        }
        
        // If we received JSON
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json'))
        {
            // Decode it
            $data = json_decode($request->getContent(), true);
            // And replace the encoded data with the decoded data
            $request->request->replace(is_array($data) ? $data : array());
        }
    });

    
    $app->get('/', function()
    {
         return json_encode("Books");    
             
    });

    $app->match("{url}", function($url) use ($app)
    {   
        return "OK";
                    
    })->assert('url','.*')->method("OPTIONS");


//token
    $app->post('/tokens',function(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        
        $user_id = authenticate($email,$password);
        
        if ($user_id != null)
        {
            return json_encode(token_create($user_id));
        }
        else
        {
            return new Response ('Unauthorized',401);
        }
    });

//users
     $app->get('/users', function()
    {
        return json_encode (users_index());
    });
    
      $app->get('/users/{id}', function($id)
    {
        return json_encode (users_show($id));
    });

    //add user
//not working gives null values
     $app->post('/users', function(Request $request)
    {
        $user = new User();
        $user->name = $request->request->get('name');
        $user->surname = $request->request->get('surname');
        $user->email = $request->request->get('email');
        $user->password= $request->request->get('password');
        $user->user_role= $request->request->get('user_role');
        
        return json_encode(users_add($user));
        
    });

//books
   
     //show all books and details
    $app->get('/books', function()
    {
        return json_encode (books_index());
    });

    //show one book
    $app->get('/books/{id}', function($id)
    {
        return json_encode (books_show($id));
    });

    //add book
     $app->post('/books', function(Request $request)
    {
        $book = new Book();
        $book->title = $request->request->get('title');
        $book->author = $request->request->get('author');
        $book->quantity = $request->request->get('quantity');
        $book->fiction = $request->request->get('fiction');
        $book->language = $request->request->get('language');
        $book->costBuy = $request->request->get('costBuy');
        $book->costBorrow = $request->request->get('costBorrow');
        $book->description = $request->request->get('description');
        $book->image = $request->request->get('image');

        return json_encode(books_add($book));
        
    });
  
    //edit book
    $app->put('/books/{id}', function(Request $request, $id)
    {
        $book = new Book();
        
        // Grab data from the request
        $book->title = $request->request->get('title');
        $book->author = $request->request->get('author');
        $book->quantity = $request->request->get('quantity');
        $book->fiction = $request->request->get('fiction');
        $book->language = $request->request->get('language');
        $book->costBuy = $request->request->get('costBuy');
        $book->costBorrow = $request->request->get('costBorrow');
        $book->description = $request->request->get('description');
        $book->image = $request->request->get('image');

        books_edit($id, $book);
        
        return json_encode($book);

    });

    $app->delete('/books/{id}' , function($id)
    {
       books_remove($id);
        
        return '{"success":true}';
                     
                     
    });


//papers
    $app->get('/papers', function()
    {
        return json_encode (papers_index());
    });

    $app->get('/papers/{id}', function($id)
    {
        return json_encode (papers_show($id));
    });
    
     $app->post('/papers', function(Request $request)
    {
        $paper = new Paper();
        $paper->subject = $request->request->get('subject');
        $paper->year = $request->request->get('year');
        $paper->semester = $request->request->get('semester');
        $paper->type = $request->request->get('type');
        $paper->costBuy = $request->request->get('costBuy');
       
        return json_encode(papers_add($paper));
        
    });

    $app->put('/papers/{id}', function(Request $request, $id)
    {
        $papers = new Paper();
        
        // Grab data from the request
        $paper = new Paper();
        $paper->subject = $request->request->get('subject');
        $paper->year = $request->request->get('year');
        $paper->semester = $request->request->get('semester');
        $paper->type = $request->request->get('type');
        $paper->costBuy = $request->request->get('costBuy');

        papers_edit($id,$paper);
        
        return json_encode($paper);

    });

     $app->delete('/papers/{id}' , function($id)
    {
       papers_remove($id);
        
        return '{"success":true}';
                     
                     
    });

//journals
    $app->get('/journals', function()
    {
        return json_encode (journals_index());
    });

     $app->get('/journals/{id}', function($id)
    {
        return json_encode (journals_show($id));
    });

    $app->post('/journals', function(Request $request)
    {
        
        
        $journal = new Journal();
        $journal->rank = $request->request->get('rank');
        $journal->title = $request->request->get('title');
        $journal->type = $request->request->get('type');
        $journal->issn = $request->request->get('issn');
        $journal->totalDocuments = $request->request->get('totalDocuments');
        $journal->averageCitations = $request->request->get('averageCitations');
        $journal->costBuy = $request->request->get('costBuy');
        $journal->country = $request->request->get('country');
       
        return json_encode(journals_add($journal));
        
    });

    $app->put('/journals/{id}', function(Request $request, $id)
    {
        
        // Grab data from the request
        $journal = new Journal();
        $journal->rank = $request->request->get('rank');
        $journal->title = $request->request->get('title');
        $journal->type = $request->request->get('type');
        $journal->issn = $request->request->get('issn');
        $journal->totalDocuments = $request->request->get('totalDocuments');
        $journal->averageCitations = $request->request->get('averageCitations');
        $journal->costBuy = $request->request->get('costBuy');
        $journal->country = $request->request->get('country');

        journals_edit($id,$journal);
        
        return json_encode($journal);

    });
    
    $app->delete('/journals/{id}' , function($id)
    {
       journals_remove($id);
        
        return '{"success":true}';
                     
                     
    });
    
    $app->run();
    
    ?>