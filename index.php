<?php
/* 
   Created By Antony Magee @ antony.magee@gmail.com 
   -----------------------January 2013 ----------------------- 
*/
    session_start();
    
    
    if (isset($_SESSION['id'])) {
    
    
        function searchStore($search) {
    
        // Pulls the searched term from the twitter search api, with entities ON, to allow for the capture of img urls
        $request = 'http://search.twitter.com/search.json?q=%23' .$search. '&include_entities=true';
    
        // Pulls json data from page with file_get_contents
        $response = file_get_contents($request);
    
        // passes the raw json blob of data to the json_decode function, with the param true to force it to output an array.
        $jsonArr = json_decode($response, true);
    
        // Database connection information
        include 'connect.php'; 
    
        foreach($jsonArr['results'] as $item)   {          
    
        // Takes the Ass Array jsonArr and for every results heading creates an $item, these are then places in variables below
        // with the mysql escape function, They are pulled from the the $item var, which holds 1 result.
    
            $user = mysql_real_escape_string($item['from_user']);
            $text = mysql_real_escape_string($item['text']);
            // stores the date in the unix timestamp format e.g "1361196608"
            $tCreated = strtotime($item['created_at']);
            $id = $item['id_str'];
    
            // Block which handles going to the enteties array of the json results and returning the expanded url.
            // Assigns all the entities within an item to the $entities var
            $entities = $item["entities"];
    
            // From the enteties var fron items choose the first array within the urls var, [0] = first array.
            $urls = $entities["urls"][0];
    
            // Assign the expanded url field to the $done var
            $done = $urls["expanded_url"];
    
            $memid = $_SESSION['id'];
    
            // After the var's are stored they are then places in to the databsae in the correct fields    
            $query = mysql_query("INSERT into storedtweets VALUES('$id', '$user', '$text', '$tCreated', '$done', '', '$memid')") or die(mysql_error());  

            } // end of Foreach
        } // end of searchStore
    
    // if there is no session set when the users tries to access page, redirects to signin page with approiate error code passed.
    } else {
        header("location: signin.php?l=4");
    }
    ?>


<html>
    <head>
        <link rel="shortcut icon" href="img/favicon.ico" />
        <title>ILoveqc Social</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet" media="screen" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="span9">
                    <div class="page-header">
                        <h1>Twitter Search<small> See Who's Talking About You</small></h1>
                    </div>
                    <div class="span9">
                        <form class="form-search" method="submit" action="index.php">
                            <div class="input-append">
                                <input class="input input-large" name="search" id="appendedInputButtons" type="text" />
                                <button class="btn" type="submit" name="submit">Search</button>
                            </div>
                            <div class="btn-group">
                                <a href="#myModal" role="button" class="btn" data-toggle="modal">Get RSS URL</a>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-warning" href="logout.php" type="button">Logout</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="span2 pull-right">
                    <img src="img/logo.jpg" class="img-circle" />
                </div>
            </div>

            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">RSS URL for Quartz Composer</h3>
              </div>
              <div class="modal-body">
                <?php 
                        include 'connect.php'; 
                            $idmem = $_SESSION['id'];
                            $sql = mysql_query("SELECT uni FROM members WHERE id = $idmem")or die(mysql_error());
                            //$val = mysql('sql');
                            $unqadd = mysql_fetch_array($sql); 
                            //$addition = $unqadd['uni'];
                            $addition = $_SESSION['id'];
                        ?>

                    <div class="well well-large">
                        
                      <h3>
                        
                            <a href="http://localhost/tweets/dispApp.php?h=<?php echo $addition; ?>">http://localhost/tweets/dispApp.php?h=<?php echo $addition; ?></a>
                      </h3>
                    </div>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
              </div>
            </div>
            <!--- search form where user enters hastag to search -->
            <?php 
                if ($_GET['search']){
                    searchStore($_GET['search']);
                    display();
                
                } else {
                    display();
                }
                ?>
            <p>
                <?php
                    function display() {
                    
                    // Database connection information
                    include 'connect.php'; 
                    
                    $memberid = $_SESSION['id'];
                    
                    $result = mysql_query("SELECT * FROM storedtweets WHERE memid = $memberid");
                    
                    if($result === FALSE) {
                    die(mysql_error()); // TODO: better error handling
                    }
                    
                    echo "<table class='table'>
                        <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th></th>
                        <th>Image</th>
                        </tr>";
                    
                    while($row = mysql_fetch_array($result))
                      {
                    
                        $img = strstr($row['img_url'], 'insta');
                        $expInsta = explode("/", $img);
                    
                        $twitpic = strstr($row['img_url'], 'twitpic');
                        $expTwitpic = explode("/", $twitpic);
                          
                          echo "<tr>";
                          echo "<td><b>" . $row['user'] . "</b></td>";
                          echo "<td>" . $row['text'] . "</td>";
                    
                          if ($row['approved']==0 ){
                            echo "<td><a class='btn btn-small' href='approval.php?id=" .$row['id']. "'><i class='icon-thumbs-up'></i></a></td>";
                            } else {
                            echo "<td><a class='btn btn-danger' href='remove.php?id=" .$row['id']. "'><i class='icon-thumbs-down'></i></a></td>";   
                            }   
                    
                    
                          if ($img != FALSE) {
                            echo "<td><a href='http://instagr.am/p/" .$expInsta[2]. "/media/?size=l' target='_blank'><img src='http://instagr.am/p/" .$expInsta[2]. "/media/?size=t' alt='Lamp'></td>";
                          }   elseif ($twitpic != FALSE){
                            echo "<td><a href='http://twitpic.com/show/full/" .$expTwitpic[1]. "' target='_blank'><img src='http://twitpic.com/show/thumb/" .$expTwitpic[1]. "' alt='Lamp'></td>";
                          }   else {
                            echo "<td></td>";
                          }
                           
                          echo "</tr>";
                      }
                    echo "</table>";
                    
                    }
                    
                    
                    
                    
                    
                    ?>
        </div>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
    </body>
</html>