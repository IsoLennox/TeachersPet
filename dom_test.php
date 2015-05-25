   <?php include('inc/functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher's Pet</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
   <form method="POST">
       <label for="url_input">URL:</label><input type="text" name="url_input" id="url_input" placeholder="http://...">
<!--
       checkboxes:
       -ussume meta tags self closing?
       -assume img tags self closing?
       -assume link tags self closing?
-->
       <input type="submit" name="submit" value="Check Source">
   </form>
   
   
<!--
  REQUIREMENTS 
a.  Check to see if there is any Inline CSS in the HTML file 
b.  Be sure all of the tags and attributes are in lower-case letters
*c.  Be sure all tags have proper closing tags. What I am thinking here is that the break tag and image tags need to have a trailing />.
d.  List all of the href and src values and classify them as either relative or absolute
e.  Check that the header, footer, content, nav and sidebar divs, if present are correctly coded
f.   If it is a true HTML5 page (the Project pages) then they must have the tags "header", "nav", "sidebar" and "footer"
-->
 

<?php



if(isset($_POST['submit'])){
    
  

        # Use the Curl extension to query Google and get back a page of results
        $url = $_POST['url_input'];
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        curl_close($ch);

        # Create a DOM parser object
        $dom = new DOMDocument();

        # Parse the HTML from URL.
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom->loadHTML($html);
    
    

        # Iterate over all the <img> tags
        echo "<h3>Image Tags</h3>";
        foreach($dom->getElementsByTagName('img') as $link) {
            # Show the <a href>
            if(!empty($link->getAttribute('alt'))){echo "<strong>ALT</strong> ".$link->getAttribute('alt')."<br />";}
            if(!empty($link->getAttribute('title'))){ echo "<strong>TITLE</strong> ".$link->getAttribute('title')."<br />";}
        }

    
    
        # Iterate over all the <HTML5> tags
        echo "<h3>HTML5</h3>";
        $error=array();
        $no_space=str_replace(" ","",$html);
        $html5_tags= get_html5_tags($html, $no_space);
        echo $html5_tags;

    $source_code=htmlentities($html);
    echo "<div id=\"source\">".$source_code."</div>";
//    echo "<div id=\"source\">".$no_space."</div>";
    echo "<h2>View page source within iframe to see formatted code</h2>";
    echo "<iframe src=\"".$url."\" frameborder=\"0\"></iframe>";
} 

?>
   
   
   
   
    
</body>
</html>


