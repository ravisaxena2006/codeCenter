<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>Progress Bar</title>
	<style>
	/*Generated from Designmycss.com*/
table
{
    border-collapse:collapse;
    border-style:solid;
    border-width:2px;
    border-color:#707070;
    font:14px Georgia, serif;
    padding:0px;
    box-shadow:1px 1px 3px 1px #6E6D6D;
}
 
th
{
    color:#FFFFFF;
    background:#E00909;
    background: -moz-linear-gradient(top,  #E00909 0%, #291616 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#E00909), color-stop(100%,#291616));
    background: -webkit-linear-gradient(top,#E00909 0%,#291616 100%);
    background: -o-linear-gradient(top,#E00909 0%,#291616 100%);
    background: -ms-linear-gradient(top,#E00909 0%,#291616 100%);
    background: linear-gradient(top,#E00909 0%,#291616 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#E00909',endColorstr='#291616',GradientType=0s);
    border-left-style: solid;
    border-width:1px;
    border-color:#707070;
    font-weight:bold;
    padding:5px;
    text-align:center;
    vertical-align:top;
}
 
tr
{
    color:#000000;
    border-top-style: solid;
    border-bottom-style: solid;
    border-width:1px;
    border-color:#707070;
    font-weight:normal;
}
 
tr:hover td
{
    background:#DB2C2C;
    color:#FFFFFF;
}
 
tr:nth-child(odd) td
{
background:#EBEBEB;
}
 
tr:nth-child(odd):hover td
{
    background:#DB2C2C;
}
 
td
{
    background:#FFFFFF;
    border-left-style: solid;
    border-width:1px;
    border-color:#707070;
    padding:3px 5px;
    text-align:left;
    vertical-align:top;
}
	</style>
</head>
<body>
<div style="padding:200px 1px 1px 450px;">
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>

</div>
<?php



require 'lib/AmazonECS.class.php';

function progressBar($total,$i){

    $percent = intval($i/$total * 100)."%";
    
    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
    document.getElementById("information").innerHTML="'.$percent.' processed.";
    </script>';
	
	//document.getElementById("information").innerHTML="'.$percent.' Page(s) processed.";

    
// This is for the buffer achieve the minimum size in order to flush data
    echo str_repeat(' ',1024*64);

    
// Send output to browser immediately
    flush();

    
// Sleep one second so we can see the delay
    //if($i%2==0)
     sleep(2);
	
}

function send_email(){
  $to = "richard@123789.org";
require 'PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Set who the message is to be sent from
$mail->setFrom( $to, '123789.org');
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress( $to, 'John Doe');
//Set the subject line
$mail->Subject = 'Amazondata';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML('Please find attached file..<br>Thanks');
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('amazondata.csv');

//send the message, check for errors
if (!$mail->send()) {
    //echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    //echo "Message sent!";
}





}

function createTabularDisplay($mydata=null){
//$print_data = '';
$print_data = "<br><br><table width=\"90%\" cellspacing=\"1\" cellpadding=\"2\" border=\"1\" align=\"center\" >";
if($mydata!=null){

$i=0;
	foreach($mydata as $dataarray){
		
		if($i==0){
			$print_data .= "<tr>";
			foreach($dataarray as $row) { $print_data .= "<th>".$row."</th>"; }
			$print_data .= "</tr>";
		}else{
			$print_data .= "<tr>";
			foreach($dataarray as $row) { $print_data .= "<td>".$row."</td>"; }
			$print_data .= "</tr>";
		}
		$i++;
	}




}

if($i==1){
$print_data .= "<tr><td colspan=4>Record(s) not found.</td></tr></table>";
}


$print_data .= "</table>";
return $print_data;

}

try
{

    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'com', AWS_ASSOCIATE_TAG);

    $keywordTosearch = "BuyCheapCables";
	$Merchant = 'BuyCheapCables';
	
		// from now on you want to have pure arrays as response
		$amazonEcs->associateTag(AWS_ASSOCIATE_TAG);
		$amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);
		
		$response = $amazonEcs->category('Electronics')->responseGroup('Small,OfferFull')->optionalParameters(array('Condition' => 'All'))->page(1)->search($keywordTosearch);
		
		
		
	
		$total_page = $response['Items']['TotalPages'];
		$total_items = $response['Items']['Item'];
		
		if(!isset($total_items[0])) $total_items = array($response['Items']['Item']);
		
		

		$all_array_data = array();
		
		$all_array_data = array_merge($all_array_data,$total_items);
		
		//$total_page = 1;
		progressBar($total_page,1);
		if($total_page >1){
		
		for($page=2; $page<=$total_page; $page++){	
		
		  // $second = 500000; //1 second = 1000000
		//	usleep($second);
		    $response2 = array();
			progressBar($total_page,$page);
			
			$response2 = $amazonEcs->category('Electronics')->responseGroup('Small,OfferFull')->optionalParameters(array('Condition' => 'All'))->page($page)->search($keywordTosearch);
			
			if(isset($response2['Items']['Item'][0]) and !empty($response2['Items']['Item']))
			{ 			
					$all_array_data = array_merge($all_array_data, $response2['Items']['Item']); 			
			}
			
			
			
		} //endforeach
		
		}

		
	    echo '<script language="javascript">document.getElementById("information").innerHTML="Generating CSV file."</script>';
		$all_products_array = array();
		$all_products_array[] = array('Manufacturer','ASIN','Title','Sellers (Price | Condition)','DetailPageURL');
		foreach($all_array_data as $product){

		$ASIN 			= $product['ASIN'];
		$DetailPageURL 	= $product['DetailPageURL'];
		$Manufacturer 	= $product['ItemAttributes']['Manufacturer'];
		$Title 			= $product['ItemAttributes']['Title'];		
		$TotalOffers 	= (isset($product['Offers']['TotalOffers']))?$product['Offers']['TotalOffers']:'0';
		
		
		
		
		//if($Manufacturer!=$Merchant) continue;
		
		$arraySeller = array();
		$MerchantPrice = array();
	
		if($TotalOffers > 0){		
		 
		 $TotalOffersItems 	= ($TotalOffers>1)?$product['Offers']['Offer']:array($product['Offers']['Offer']);
		  
		  
		
		  
		  foreach($TotalOffersItems as $sellers){
		  
			  $sellerName 		= $sellers['Merchant']['Name'];	
			  $Condition 		= $sellers['OfferAttributes']['Condition'];	
			  $FormattedPrice 	= $sellers['OfferListing']['Price']['FormattedPrice'];	  
					
			  if($sellerName!=$Merchant) {			  
					$arraySeller[] = $sellerName . " ( $FormattedPrice | $Condition ) ";						
			  } 			  
			  
		  }
		
		}
		
		if(empty($arraySeller)) continue;		
		
		
		$item = array(
		        'Manufacturer'=>$Manufacturer,
				'ASIN'=>$ASIN,				
				'Title'=>$Title,				
				'Sellers'=>implode(",",$arraySeller),
				'DetailPageURL'=>'<a href="'.$DetailPageURL.'" target="_blank">Click to view</a>',
				
		);
		$all_products_array[] = $item ;
		
		

		} //endforeach
		
		//echo '<script language="javascript">document.getElementById("information").innerHTML="Sending email."</script>';	
		
		

		 
		$fp = fopen('amazondata.csv', 'w');

		foreach ($all_products_array as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		
		echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed.<br> <a href=\'index.php\'>Back to home</a>"</script>';	
		
		//echo '<script language="javascript">window.location.href="ajaxdownload.php";</script>';
		
		///send_email();
		echo createTabularDisplay($all_products_array);

}
catch(Exception $e)
{
  echo $e->getMessage();
}


