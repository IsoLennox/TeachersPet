<?php

 // a.  Check to see if there is any Inline CSS in the HTML file
 // b.  Be sure all of the tags and attributes are in lower-case letters
 // c.  Be sure all tags have proper closing tags. What I am thinking here is that the break tag and image tags need to have a trailing />.
 // d.  List all of the href and src values and classify them as either relative or absolute
 // e.  Check that the header, footer, content, nav and sidebar divs, if present are correctly coded
 // f.  If it is a true HTML5 page (the Project pages) then they must have the tags "header", "nav", "sidebar" and "footer"

function isPartUppercase($string) {
	return strtolower($string) !== $string;
}

function get_data($url) {
	// grab html of page
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function process_html($html){
	$dom = new DOMDocument;
	$dom->loadHTML($html);
	
	// does it validate
	// if ($dom->validate()) {
 //    	echo "This document is valid!\n" . "<br>";
	// } else {
	// 	echo "This document does not validate\n" . "<br>";
	// }

	echo "<ol>";

	// check for <style> tags
	$all = $dom->getElementsByTagName('style');
	foreach ($all as $element){
		echo "<li>Embedded CSS detected at line " . $element->getLineNo() . "</li>";
	}

	// get all elements
	$all = $dom->getElementsByTagName('*');

	foreach ($all as $element){
		//echo isPartUppercase($element->tagName);

		if (isPartUppercase($element->tagName)){
			echo "<li>Uppercase Tag: " . $element->tagName . " at line " . $element->getLineNo() . "</li>";
		}

		if($element->getAttribute('style')){
			echo "<li>INLINE STYLE FOUND on line " . $element->getLineNo() . "</li>";
		}
	}

	$images = $dom->getElementsByTagName('img');
	// any images?
	foreach ($images as $image) {
			// image name
			if ($image->hasAttribute('src')){
	        	echo "<li>Image: " . $image->getAttribute('src') . "</li>";
	    	} 
	    	if ($image->hasAttribute('height')){
	    		echo "<li>Image Height: " . $image->getAttribute('height') . "</li>";
	    	} else {
	    		echo "<li>No HEIGHT attribute for image</li>";
	    	}

	    	if ($image->hasAttribute('width')){
	    		echo "<li>Image WIDTH: " . $image->getAttribute('width') . "</li>";
	    	} else {
	    		echo "<li>No WIDTH attribute for image</li>";
	    	}

	    	if ($image->hasAttribute('alt')){
	    		echo "<li>Image ALT: " . $image->getAttribute('alt') . "</li>";
	    	} else {
	    		echo "<li>No ALT attribute for image</li>";
	    	}
	}

	$divs = $dom->getElementsByTagName('div');
	// any divs
	foreach ($divs as $div) {
			if($div->hasAttribute('id')){
	        	echo "<li>div found: ID=" . $div->getAttribute('id') . "</li>";
	    	}
	    	if($div->hasAttribute('class')){
	        	echo "<li>div found: CLASS=" . $div->getAttribute('class') . "</li>";
	    	}
	}

	echo "</ol>";

	//$html = $dom->saveHTML();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CTEC 122 Web Page Processor</title>
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
	<h1>CTEC 122 Web Page Analyzer</h1>
	<h2>BETA</h2>
	<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
		<div>
			<label for="url">Enter URL: </label>
			<input type="text" id="url" name="url" size="120" value="<?php if(isset($_POST['url'])) echo $_POST['url'];?>">
  
			<input type="submit" value="Evaluate">
		</div>
	</form>

<?php
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$html = get_data($_POST['url']);
		process_html($html);
	} // end if
?>
</body>
</html>


