<?php
/*TO DO
 initial setup UI (email, notification frequency)
 format listings
 fill post response form
*/

include('simple_html_dom.php'); // Parsing library

header("refresh: 3600;"); // If the page is open in a browser, refresh the page to run the code again
?>
<html>
	<head><title>Kijiji Parser</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<?php
$html = file_get_html('http://www.kijiji.ca/b-old-video-games/gta-greater-toronto-area/c623l1700272?ad=offering');

//$prices = $html->find('td[class=price]');
//$titles = $html->find('td[class=description]');
//$dates = $html->find('td[class=posted]');

$listings = $html->find('table[class=regular-ad]');
$newListings = array();
$toEmail = "artemym@gmail.com";

foreach($listings as $element)
{
	$tags = str_get_html($element);
	$rawDate = trim($tags->find('td[class=posted]',0)->innertext);
	// If the time posted is not in hours, then add it to the listings
	if(strpos($rawDate,'hours') == false){
		//$tags = $tags->find('td[class=description]',0)->find('a',0)->href;
		//$newLink = substr_replace($tags, 'http://kijiji.ca', 0, 0);
		//$newLink = 'http://kijiji.ca' . $tags;
		//$element = substr_replace($element, $newLink, strpos($element, $tags, 100), strlen($tags));
		$element->find('td[class=description]',0)->find('a',0)->href = 'http://kijiji.ca' . $element->find('td[class=description]',0)->find('a',0)->href;
		array_push($newListings, $element);
	}
}
	
$numOfListings = sizeof($newListings);
//echo "numOfListings: " . $numOfListings . "<br>";
?>
<script>
function submit() {
    "<?php $toEmail ?>" = document.getElementById('textbox_id').value;
	"<?php echo $toEmail; ?>"
}
</script>
<?php

// Makes sure to not send emails with no listings
if($numOfListings > 0)
{
	$newListings = html_entity_decode(htmlentities(implode(" ",$newListings)));
	$listHeader = '<a href="http://www.kijiji.ca/b-old-video-games/gta-greater-toronto-area/c623l1700272?ad=offering"><img src="http://artemym.ca/kijiji-logo.png" alt="Kijiji" height="100" style="display:block; margin-left:auto; margin-right:auto;"></a><br>';
	//echo $listHeader . $newListings;
		
	$too = $toEmail;
	$subject = "(" . $numOfListings . ") Kijiji Notification";
	$message = $listHeader . $newListings;
	$user_email = "artemym@gmail.com"; // valid POST email address

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Kijiji Parser <artemym@gmail.com>' . "\r\n";
	 
	mail($too,$subject,$message, $headers);  
}
?>
	</head>
	<body>
		<h3>Please input the email address you wish to send notifications to</h3>
		<label for="email">Email: </label><input type="text" id="email">
		<input type="button" id="submit" onclick="submit()" value="Submit">
	</body>
</html>
