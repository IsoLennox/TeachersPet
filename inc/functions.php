<?php


function GetBetween($var1="",$var2="",$pool){
        $temp1 = strpos($pool,$var1)+strlen($var1);
        $result = substr($pool,$temp1,strlen($pool));
        $dd=strpos($result,$var2);
        if($dd == 0){
            $dd = strlen($result);
        }

        return substr($result,0,$dd);
    }  




function get_doctype($returned_content){
    //look for !doctype
    if (preg_match("/&lt;!doctype html/i", $returned_content) || preg_match("/&lt; !doctype html/i", $returned_content)) {
        
         $result= "<strong class=\"green\">Proper doctype detected</strong><br/>";
    } else {
       $result= "<strong class=\"red\">!doctype not found</strong><br/>";
    }
    return $result;
}



function get_stylesheets($returned_content){
            //look external stylesheet
    if (preg_match("/link href/i", $returned_content) || preg_match("/link rel/i", $returned_content)) {
        echo "<strong class=\"green\">External stylesheet detected</strong><br/>";
    } else {
         echo "<strong class=\"red\">No external stylesheet detected</strong><br/>";
    }
    
}




function get_inline($returned_content, $no_space){
//     $inline_styles=array();
    global $inline_styles;
    global $dom;
    
     $all = $dom->getElementsByTagName('style');
	foreach ($all as $element){
		echo "<strong class=\"red\">Embedded CSS detected at line " . $element->getLineNo() . "</strong><br/>";
	}
    

    
    //ECHO TYPES OF INLINE STLYING USED
        //look for inline styling <style>
    if (preg_match("/&lt;style&gt;/i", $no_space)) { 
        if (preg_match("/&lt;style&gt;/i", $returned_content)) { array_push($inline_styles,"&lt;style&gt;"); }
        if (preg_match("/&lt; style &gt;/i", $returned_content)) { array_push($inline_styles,"&lt; style &gt;"); }
            echo "<strong class=\"red\">inline \"&lt;style&gt;\" styling found</strong><br/>";
    }
        //look for inline styling style=
    if (preg_match("/style=/i", $no_space)) { 
        if (preg_match("/style=/i", $returned_content)) { array_push($inline_styles,"style="); }
        if (preg_match("/style =/i", $returned_content)) { array_push($inline_styles,"style ="); }
            echo "<strong class=\"red\">inline \"style=\" styling found</strong><br/>";
    }
        
        
        //check for width inline styles
         if (preg_match("/width=/i", $no_space) || preg_match("/width:/i", $returned_content)) {
             
                if (preg_match("/width=/i", $returned_content)) { array_push($inline_styles,"width="); }
                if (preg_match("/width =/i", $returned_content)) { array_push($inline_styles,"width ="); }
                if (preg_match("/width:/i", $returned_content)) { array_push($inline_styles,"width:"); }
                if (preg_match("/width :/i", $returned_content)) { array_push($inline_styles,"width :"); }
             
            echo "<strong class=\"red\">inline \"width\" styling found</strong><br/>";
        }
         //check for height inline styles
         if (preg_match("/height=/i", $no_space)|| preg_match("/height:/i", $no_space)) {
 
                if (preg_match("/height=/i", $returned_content)) { array_push($inline_styles,"height="); }
                if (preg_match("/height =/i", $returned_content)) { array_push($inline_styles,"height ="); }
                if (preg_match("/height:/i", $returned_content)) { array_push($inline_styles,"height:"); }
                if (preg_match("/height :/i", $returned_content)) { array_push($inline_styles,"height :"); }
            echo "<strong class=\"red\">inline \"height\" styling found</strong><br/>";
        }    
}





function get_html5_tags($returned_content, $no_space){
    global $error;
    global $dom;
    
//    HEADER
    if (preg_match("/&lt;header&gt;/i", $no_space)) { 
       
         echo "<strong class=\"green\">html5 'header' detected</strong><br/>";
    } else {
          echo "<strong class=\"red\">html5 &lt;header&gt; not found</strong><br/>";
    }
    
    
         
        if (preg_match("/=&quot;header/i", $returned_content)) { array_push($error,"=&quot;header"); }
        if (preg_match("/= &quot;header/i", $returned_content)) { array_push($error,"= &quot;header"); }
        if (preg_match("/= &quot; header/i", $returned_content)) { array_push($error,"= &quot; header"); }
        if (preg_match("/=&quot; header/i", $returned_content)) { array_push($error,"=&quot; header"); }
        
        
     
    
    //    NAV
    if (preg_match("/&lt;nav&gt;/i", $no_space)) {
        
         echo "<strong class=\"green\">html5 'nav' detected</strong><br/>";
    } else {
             echo "<strong class=\"red\">html5 &lt;nav&gt; not found</strong><br/>";
    }
    
        if (preg_match("/=&quot;nav/i", $returned_content)) { array_push($error,"=&quot;nav"); }
        if (preg_match("/= &quot;nav/i", $returned_content)) { array_push($error,"= &quot;nav"); }
        if (preg_match("/= &quot; nav/i", $returned_content)) { array_push($error,"= &quot; nav"); }
        if (preg_match("/=&quot; nav/i", $returned_content)) { array_push($error,"=&quot; nav"); }
   
 
    if(!empty($dom->getElementById('nav'))){
    echo "ID of nav on line " . $dom->getElementById('nav')->getLineNo(). "<br/>";
    }
    
    
    //    ASIDE
    if (preg_match("/&lt;aside&gt;/i", $no_space)) {
         echo "<strong class=\"green\">html5 'aside' detected</strong><br/>";
        
    } else {  
             echo "<strong class=\"red\">html5 &lt;aside&gt; not found</strong><br/>";
    }
        if (preg_match("/=&quot;aside/i", $returned_content)) { array_push($error,"=&quot;aside"); }
        if (preg_match("/= &quot;aside/i", $returned_content)) { array_push($error,"= &quot;aside"); }
        if (preg_match("/= &quot; aside/i", $returned_content)) { array_push($error,"= &quot; aside"); }
        if (preg_match("/=&quot; aside/i", $returned_content)) { array_push($error,"=&quot; aside"); }
        
  //CHECK FOR  ="sidebar"
 
        if (preg_match("/=&quot;sidebar/i", $returned_content)) { array_push($error,"=&quot;sidebar"); }
        if (preg_match("/= &quot;sidebar/i", $returned_content)) { array_push($error,"= &quot;sidebar"); }
        if (preg_match("/= &quot; sidebar/i", $returned_content)) { array_push($error,"= &quot; sidebar"); }
        if (preg_match("/=&quot; sidebar/i", $returned_content)) { array_push($error,"=&quot; sidebar"); }
    
    
    
    //    FOOTER
    if (preg_match("/&lt;footer&gt;/i", $no_space)) {
         echo "<strong class=\"green\">html5 'footer' detected</strong><br/>";
    } else {
            echo "<strong class=\"red\">html5 &lt;footer&gt; not found</strong><br/>";
    }
        if (preg_match("/=&qout;footer/i", $returned_content)) { array_push($error,"=&quot;footer"); }
        if (preg_match("/= &quot;footer/i", $returned_content)) { array_push($error,"= &quot;footer"); }
        if (preg_match("/= &quot; footer/i", $returned_content)) { array_push($error,"= &quot; footer"); }
        if (preg_match("/=&quot; footer/i", $returned_content)) { array_push($error,"=&quot; footer"); }
       
}




  function get_self_closing($returned_content, $no_space){  
      global $total_end_tags;
      global $total_meta_tags;
      global $total_img_tags;
      global $total_link_tags;
      global $tags;
      global $end_tags;
       //meta
        if (preg_match("/&lt;meta/i", $no_space)) {
            $total_meta_tags=-1;
            if (preg_match("/&lt;meta/i", $returned_content)) { 
                array_push($tags,"&lt;meta"); 
                
                                //COUTNING EACH INSTANCE
                    $words = explode("&lt;meta", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_meta_tags++;  }
  
              }
            if (preg_match("/&lt; meta/i", $returned_content)) { 
                array_push($tags,"&lt; meta"); 
                                  //COUTNING EACH INSTANCE
                    $words = explode("&lt; meta", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_meta_tags++;  }
            }
        } 
  
    
           
        //img
        if (preg_match("/&lt;img/i", $no_space)) { 
            //could be multiples, count each instance 
            $total_img_tags=-1;
            
//            <img
            if (preg_match("/&lt;img/i", $returned_content)) { 
                array_push($tags,"&lt;img"); 

                
                //COUTNING EACH INSTANCE
                    $words = explode("&lt;img", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_img_tags++;  } 
            }
            
//            < img
            if (preg_match("/&lt; img/i", $returned_content)) { 
                array_push($tags,"&lt; img"); 
 
                                                      
                    //COUTNING EACH INSTANCE
                    $words = explode("&lt;img", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_img_tags++;  }   
                    }//end 
                    
        } 
    
     
                    //link
        if (preg_match("/&lt;link/i", $no_space)) {
            $total_link_tags=-1; 
            
            if (preg_match("/&lt;link/i", $returned_content)) { 
                array_push($tags,"&lt;link"); 

                //COUTNING EACH INSTANCE
                    $words = explode("&lt;link", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_link_tags++;  } 
            } 
            if (preg_match("/&lt; link/i", $returned_content)) { 
                array_push($tags,"&lt; link"); 

                    //COUTNING EACH INSTANCE
                    $words = explode("&lt; link", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_link_tags++;  } 
                        }
        } 
      
      
      
      
             //BR
        if (preg_match("/&lt;br/i", $no_space)) {
            $total_meta_tags=-1;
            if (preg_match("/&lt;br/i", $returned_content)) { 
                array_push($tags,"&lt;br"); 
                
                                //COUTNING EACH INSTANCE
                    $words = explode("&lt;br", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_meta_tags++;  }
  
              }
            if (preg_match("/&lt; br/i", $returned_content)) { 
                array_push($tags,"&lt; br"); 
                                  //COUTNING EACH INSTANCE
                    $words = explode("&lt; br", $returned_content);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_meta_tags++;  }
            }
        } 
    
    $total_end_tags=-1;
    if (preg_match("/\/&gt/i", $no_space)){ 
        array_push($end_tags,"\/&gt"); 
                            //COUTNING EACH INSTANCE
                    $words = explode("/&gt", $no_space);
                    $result = array_combine($words, array_fill(0, count($words), 0));
                    foreach($words as $word) {  $total_end_tags++;  } 
    }
}

?>