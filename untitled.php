<head>
</head>
<body>

<?php
    $request = "http://search.twitter.com/search.json?q=%23boysnoize";
    $response = file_get_contents($request);
    $jsonobject = json_decode($response);
?>

    <h1>Testing tweet pull</h1>

    <p>


    <p> 
<?php
        
            foreach ($jsonobject as $tweet)
              {
                echo $tweet->text;
              } 
            


        

?>
</body>
</html>




<?php
echo "<pre>";
echo 'This variable is of class Type:       ' . gettype($jsonobject);
print_r($jsonobject);
 // or var_dump()
echo "</pre><br>";
?>





<?php
    $request = "http://search.twitter.com/search.json?q=%23boysnoize&include_entities=true";
    $response = file_get_contents($request);
    $jsonArr = json_decode($response, true);
?>

<?php

$json ='{ "results" : [ { "entities" : 
[ { "urls" : [ { "expanded_url" : "http://instagr.am/p/Vz4Nnbnjd6/",
                    "url" : "http://t.co/WZnUf68j"
                  } ] } ],
        "from_user" : "A-user-name",
        "from_user_id" : 457304735,
        "text" : "Ich R U #BoysNoize #SuperRola"
      } ] }';
$json_array = (array)(json_decode($json));
echo '<pre>';
 //print_r($json_array);

 echo $json_array['results'][0]->entities[0]->urls[0]->url;

 ?>







 <?php

    $result = mysql_query("SELECT * FROM storedtweets");

    echo "<table border='0'>
        <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>ID</th>
        </tr>";

    while($row = mysql_fetch_array($result))
      {
          echo "<tr>";
          echo "<td>" . $row['user'] . "</td>";
          echo "<td>" . $row['text'] . "</td>";
          echo "<td><input class=btn type=submit id=" .$row['id']. " value=Approve></td>";
          echo "<td>" . $row['img_url'] . "</td>";
          echo "</tr>";
      }
    echo "</table>";
?>

$img = strstr($row['img_url'], 'insta');
          
          echo "<td>" . $img . "</td>";





<input class=btn type=submit id=" .$row['id']. "  value=Approve>






<?php

    $result = mysql_query("SELECT * FROM storedtweets");

    


    echo "<table border='0'>
        <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>ID</th>
        <th>Image</th>
        </tr>";

    while($row = mysql_fetch_array($result))
      {

        $img = strstr($row['img_url'], 'insta');
        $expInsta = explode("/", $img);

        $twitpic = strstr($row['img_url'], 'twitpic');
        $expTwitpic = explode("/", $twitpic);
          
          echo "<tr>";
          echo "<td>" . $row['user'] . "</td>";
          echo "<td>" . $row['text'] . "</td>";
          echo "<td><input class=btn type=submit id=" .$row['id']. " value=Approve></td>";
          echo "<td><img src='http://instagr.am/p/" .$expInsta[2]. "/media/?size=t' alt='Lamp'></td>";
          echo "</tr>";
      }
    echo "</table>";
?>





{
  // Print login failure message to the user and link them back to your login page
    print '<br /><br /><font color="#FF0000">No match in our records, try again </font><br />
  <br /><a href="signin.php">Click here</a> to go back to the login page.';
    exit();
}






  if($login_check > 0){ 
    $line = mysql_fetch_array($sql);
    if ($line['admin'] === 1){
      header("location: adm.php");
    } else {
      while($row = mysql_fetch_array($sql)){ 
          // Get member ID into a session variable
          $id = $row["id"];   
          session_start();
          $_SESSION['id'] = $id;
          // Update last_log_date field for this member now
          mysql_query("UPDATE members SET time_logged=now() WHERE id='$id'"); 
          // Print success message here if all went well then exit the script   
      header("location: index.php?id=$id"); 
      exit();
      } }// close while
  } else {
  // Print login failure message to the user and link them back to your login page
    print '<br /><br /><font color="#FF0000">No match in our records, try again </font><br />
  <br /><a href="signin.php">Click here</a> to go back to the login page.';
    exit();
  }
