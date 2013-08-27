<html>
    <head>
        <meta name="txtweb-appkey" content="bfbf4fdf-af56-4c98-8509-cfce902400b5" />
        <title>WebsisMIT by RAJAT GUPTA</title>
    </head>
    <body>
      <?php
         
         //extracting the parameter "txtweb-message" from the http request sent by txtWeb
         if(isset($_GET['txtweb-message'])) {
            $query = $_GET['txtweb-message']; // Getting string  from the user
         }
          if(isset($_GET['txtweb-mobile'])) {
            $mobile = $_GET['txtweb-mobile']; // Getting user's mobile number
         }
         $pubkey="bfbf4fdf-af56-4c98-8509-cfce902400b5";
         
       
//Get cURL resource
$cookiefile = tempnam("/tmp", "cookies"); //Saving cookies to open other links on page 
$string = explode(" ",$query,2); // Separating ID and Birthdate
$bday = $string[1];
$id =  $string[0];

$curl = curl_init();
// Making a post request to the College website 
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://websismit.manipal.edu/websis/control/createAnonSession?birthDate='.$bday.'&birthDate_i18n='.$bday.'&idValue='.$id,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_FOLLOWLOCATION => 0,
    CURLOPT_COOKIEFILE => $cookiefile,
    CURLOPT_COOKIEJAR => $cookiefile
    
    
    
    ));
 
 

// Send the request & save response to $resp
$resp = curl_exec($curl);

$num = strpos($resp,"cc_ProfileTitle_name");// Fetching the name of the student from html response 
$strpart = substr($resp,$num);
$num2= strpos($strpart,"</spa");
$name= substr($resp,$num+37,$num2);


// Close request to clear up some resources

$url       = "http://websismit.manipal.edu/websis/control/StudentAcademicProfile";  //Link that has Attendance status 
    //We do not have to initialise CURL again  
    curl_setopt($curl, CURLOPT_URL, $url);  
    curl_setopt($curl, CURLOPT_USERAGENT, $agent);  
    curl_setopt($curl, CURLOPT_POST, 1); // set POST method  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);  
    //Remember to use the same cookiefile as above  
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiefile);  
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiefile);  
  
    $mRes = curl_exec($curl);  


  function getgpa($mRes,$name){ 
    $i =  strpos($mRes,"n class=\"l",0);

     $yoho = substr($mRes,$i+41,4);

     $u = strpos ($mRes,"cc_ListAttendanceSummary_productId_1");
     $voho= substr($mRes,$u+60);

    echo "Service by Rajat:READING FORMAT(Total,Attended,Bunks,%ge,Date)<br>".$name."CGPA:".$yoho."<br>".$voho; //HTML reply to be sent 
    return $mRes;
}

  $reply = getgpa($mRes,$name); // $reply contains the final message to be replied
  
    
  curl_close($curl);
// Make a post request to TXTWEB with the reply
$ch = curl_init();

curl_setopt_array($ch , array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://api.txtweb.com/v1/push?txtweb-mobile='.$mobile.'&txtweb-message='.$reply.'&txtweb-pubkey='.$pubkey,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    
));
// Send the request & save response to $resp
$resp = curl_exec($ch );
// Close request to clear up some resources
curl_close($ch );



?>
    </body>
</html>
 
