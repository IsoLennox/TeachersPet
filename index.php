   <?php include('inc/functions.php');
 
?>
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
*a.  Check to see if there is any Inline CSS in the HTML file 
b.  Be sure all of the tags and attributes are in lower-case letters
*c.  Be sure all tags have proper closing tags. What I am thinking here is that the break tag and image tags need to have a trailing />.
*d.  List all of the href and src values and classify them as either relative or absolute
*e.  Check that the header, footer, content, nav and sidebar divs, if present are correctly coded
*f.   If it is a true HTML5 page (the Project pages) then they must have the tags "header", "nav", "sidebar" and "footer"
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
        $returned_content = curl_exec($ch);
        curl_close($ch);

        # Create a DOM parser object
        $dom = new DOMDocument();

        # Parse the HTML from URL.
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom->loadHTML($returned_content);
    
 
     //define arrays for highlighting functionality
    $error=array();
    $tags=array();
    $end_tags=array();
    $inline_styles=array();
     
    $total_img_tags=0;
    $total_meta_tags=0;
    
    
    $url=$_POST['url_input'];
    echo "<h2>Looking at: ".$url." </h2>"; 
    $returned_content=htmlentities($returned_content); 
    //prevent needing to add conditions for spaces
    $no_space=str_replace(" ","",$returned_content);
    
     
    $doctype=get_doctype($returned_content);
    echo $doctype;
    
    $stylesheets=get_stylesheets($returned_content);
    echo $stylesheets;
    
    
    
//************************
//
//    HTML5 TAGS
//
//************************ 
    
    $html5_tags=get_html5_tags($returned_content, $no_space);
    echo $html5_tags;
    
//************************
//
//    INLINE STLYING
//
//************************
     
    $inline=get_inline($returned_content, $no_space);
    echo $inline;
    
    
    

    
             //check for br styles
//         if (preg_match("/&lt;br/i", $no_space)) {
//            array_push($inline_styles,"&lt;br"); 
//            array_push($inline_styles,"&lt; br"); 
//            array_push($inline_styles,"&lt; br &gt;"); 
//            array_push($inline_styles,"&lt; br / &gt;"); 
//            array_push($inline_styles,"&lt; br /&gt;"); 
//            array_push($inline_styles,"&lt; br/&gt;"); 
//            array_push($inline_styles,"&lt; br/ &gt;"); 
//            echo "<strong class=\"red\">inline \"&lt;br&gt;\" styling found</strong><br/>";
//        }  
    
    
    
    
    //    check for deprecated tags
    
//                 //check for b styles
//         if (preg_match("/&lt;b&gt;/i", $no_space)) {
////            array_push($error,"&lt;b"); 
////            array_push($error,"&lt; b"); 
////            array_push($error,"&lt; b &gt;"); 
////            array_push($error,"&lt; b  &gt;");  
////            array_push($error,"&lt; b&gt;");  
//            echo "<strong class=\"red\">inline \"&lt;b&gt;\" styling found</strong><br/>";
//        } 
//    
//        //Check for center tags
//        if (preg_match("/&lt;center&gt;/i", $no_space)) {
//            array_push($error,"&lt;center&gt;");  
//            array_push($error,"&lt; center &gt;");  
//            echo "<strong class=\"red\">inline \"center\" styling found</strong><br/>";
//        } 
    
    
    
//************************
//
//    SELF CLOSING TAGS
//
//************************
    
    $self_closing=get_self_closing($returned_content, $no_space);
    echo $self_closing;
    
    $count_meta=0;
    $count_external=0;
    foreach($dom->getElementsByTagName('meta') as $meta_found) { $count_meta++;}
    foreach($dom->getElementsByTagName('link') as $meta_found) { $count_external++;} 
    
//************************
//
//    ALTS AND TITLES
//
//************************
    //    List all of the href and src values and classify them as either relative or absolute
    // list all alt values and titles
    
    function startsWith($string, $chars) { 
    return $chars === "" || strrpos($string, $chars, -strlen($string)) !== FALSE;
}
    
        # Iterate over all the <img> tags
        echo "<h3>Image Tags</h3>";
        
        $count_img_tags=0;
        $count_img_alt=0;
        $count_img_title=0;
        foreach($dom->getElementsByTagName('img') as $link) {
            $count_img_tags++; 
            
            
            if(!empty($link->getAttribute('src'))){
                echo "<strong>SRC </strong> ".$link->getAttribute('src'); 
                //CHECK IF RELATIVE OR ABS
                if(startswith($link->getAttribute('src'),"http") || startswith($link->getAttribute('src'),"www")){ echo "<strong class=\"red\"> (absolute)</strong>";}else{ echo "<strong class=\"green\"> (relative)</strong>";}
                echo "<br />";
            }
            if(!empty($link->getAttribute('alt'))){echo "<strong>ALT</strong> ".$link->getAttribute('alt')."<br />"; $count_img_alt++;}
            if(!empty($link->getAttribute('title'))){ echo "<strong>TITLE</strong> ".$link->getAttribute('title')."<br />";$count_img_title++;}
        }
    
    //ECHO OUT RESULTS TO COMPARE
        echo $count_img_tags." images found<br/>";
        echo $count_img_alt." have ALT <br/>";
        echo $count_img_title." have TITLE <br/>";
    
    
    
    
            # Iterate over all the <href> tags
        echo "<h3>Anchor Tags</h3>";
        $count_anchor_tags=0;
        $count_anchor_title=0;
        $count_anchor_alt=0;
        foreach($dom->getElementsByTagName('a') as $link) {
            $count_anchor_tags++; 
            
            if(!empty($link->getAttribute('href'))){
                echo "<strong>HREF</strong> ".$link->getAttribute('href');
               //CHECK IF RELATIVE OR ABS
                if(startswith($link->getAttribute('href'),"http") || startswith($link->getAttribute('src'),"www")){ echo "<strong class=\"red\"> (absolute)</strong>";}else{ echo "<strong class=\"green\"> (relative)</strong>";}
                echo "<br />";
                    }
            if(!empty($link->getAttribute('alt'))){echo "<strong>ALT</strong> ".$link->getAttribute('alt')."<br />"; $count_anchor_alt++;}
            if(!empty($link->getAttribute('title'))){ echo "<strong>TITLE</strong> ".$link->getAttribute('title')."<br />"; $count_anchor_title++;}
        }
    //ECHO OUT RESULTS TO COMPARE
        echo $count_anchor_tags." links found<br/>";
        echo $count_anchor_alt." have ALT <br/>";
        echo $count_anchor_title." have TITLE <br/>";
    
 
 
    
//************************
//
//    HIGHLIGHT RESULTS
//
//************************
    
    
    //COUNT EACH TYPE
    $self_close_open=0;
    $self_close_end=0;
    $non_html5=0;
    $inline=0;
    
     foreach($error as $val){
         //count all "should be html5 tags"
         $non_html5++;
            $returned_content= preg_replace("/".$val."/", "<span class=\"highlight\">".$val."</span>", $returned_content); 
     }
    
    foreach($tags as $tag){
        //count all opening tags if they are assumed to need self closing tags
        $self_close_open++;
            $returned_content= preg_replace("/".$tag."/", "<span class=\"highlight-green\">".stripcslashes($tag)."</span>", $returned_content); 
     }
    
    foreach($end_tags as $end){
        //count all /> self closing tags
        $self_close_end++;
            $returned_content= preg_replace("/".$end."/", "<span class=\"highlight-blue\">".stripcslashes($end)."</span>", $returned_content); 
     }
    
    foreach($inline_styles as $in){
        //count all inline styles
        $inline++;
            $returned_content= preg_replace("/".$in."/", "<span class=\"highlight-pink\">".stripcslashes($in)."</span>", $returned_content); 
     }

    
    
    echo "<h3>Color Legend: </h3>";
    echo "<p><span class=\"highlight\">Should be HTML5 tags</span>(".$non_html5." found)</p>";
    echo "<p><span class=\"highlight-pink\">inline styles</span>(".$inline." unique found)</p>";
    
    //self close open counts unique only, so get total_img_tags and minus 1 for the count as an image tag being a unique member
    
    $total_open_tags=$self_close_open+$count_img_tags+$count_meta+$count_external;
    if($total_open_tags<0){$total_open_tags=0;}
    if($total_end_tags<0){$total_end_tags=0;}
    echo "<p><span class=\"highlight-green\">Start self closing tag</span>(".$total_open_tags."  found) Assuming that meta, link and img tags are self-closing</p>";  
     
    echo "<p><span class=\"highlight-blue\">end self closing tag</span>(".$total_end_tags." found)</p>";  

    echo "<div id=\"source\">".$returned_content."</div>";
//    echo "<div id=\"source\">".$no_space."</div>";
    echo "<h2>View page source within iframe to see formatted code</h2>";
    echo "<iframe src=\"".$url."\" frameborder=\"0\"></iframe>";
} 

?>
   
   
   
   
    
</body>
</html>


