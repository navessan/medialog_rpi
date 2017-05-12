<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<script type="text/javascript">
function getDate()
{
    var date = new Date();
	
    document.getElementById('timedisplay').innerHTML = 
	date.getFullYear() 
	+ '.' 
	+ ('0' + (date.getMonth() + 1)).slice(-2) 
	+ '.' 
	+ ('0' + date.getDate()).slice(-2)
	+ ' '
	+ ('0' + date.getHours()).slice(-2)
	+ ':'
	+ ('0' + date.getMinutes()).slice(-2)
	+ ':'
	+ ('0' + date.getSeconds()).slice(-2)
	;
}
setInterval(getDate, 990);
</script>

<style type="text/css">
</style>


<title>tv</title>

<meta content="text/html; charset=utf-8" name="Content">
<meta http-equiv="Content-Type"
	content="text/html; charset=utf-8">
<meta name="keywords"
	content="web, database, gui">
<meta name="description"
	content="web database gui">

</head>
<body>
<!--<p style="text-align: center;"><img src="logo.png" alt="" /></p>-->

<?php

/* Default database settings*/
$database_type = "sqlsrv";
$database_default = "medialog";
$database_hostname = "localhost";
$database_username = "sa";
$database_password = "password";
$database_port = "";

$debug=0;
/* display ALL errors */
error_reporting(E_ALL);

/* Include configuration */
include("config.php");

if (isset($_REQUEST['phpinfo']))
{
	phpinfo();
	die( "exit!" );
}
if (isset($_REQUEST['debug']))
{
	$debug=1;
}

if (!isset($_REQUEST['place']))
{
	print
	'<table width="100%" align="center">
		<tr>
			<td>
				Place is not set.
			</td>
		</tr>
	</table>';
	exit;
}
else
	$place=sanitize_search_string($_REQUEST['place']);


if($database_type=="sqlsrv")
	$dsn = "$database_type:server=$database_hostname;database=$database_default";
else 	
	$dsn = "$database_type:host=$database_hostname;dbname=$database_default;charset=$database_charset";


$opt = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

try {
	$conn = new PDO($dsn, $database_username, $database_password, $opt);
}
catch(PDOException $e) {
	die($e->getMessage());

}

/* Set up and execute the query. */

// $tsql = "declare @d as datetime
// select @d=getDate()/*'2016-10-10 21:00:00.000'*/

// select top 1
// PL_SUBJ_ID
// from dbo.pl_us_tv(@d,'215') as t
// ";

// $stmt = $conn->query($tsql);
// $rows = $stmt->fetchAll();

$tsql = "declare @d as datetime
select @d=getDate()/*'2016-10-10 21:00:00.000'*/

select top 1
PL_SUBJ_ID
from dbo.pl_us_tv(@d, :place ) as t
";

$stmt = $conn->prepare($tsql);

$stmt->bindValue(':place',$place);
$stmt->execute(); 

$rows = $stmt->fetchAll();


$numRows = count($rows);
//echo "<p>$numRows Row" . ($numRows == 1 ? "" : "s") . " Returned </p>";

if($numRows>0)
{	

	/* Retrieve each row as an associative array and display the results.*/
	foreach ($rows as $row)
	{
		
		//print_r($row);

					
		$field=$row["PL_SUBJ_ID"];
		$text='';			
		$text = trim($field);
		
		$img_path='';
		
		if($text!='')
			$img_path ='img/'.$text.'.jpg';
		
		if(file_exists($img_path))
			$text ='<img src="'.$img_path.'" alt="'.$field.'" width="95%" />';
		else
			$text='no image for id='.$field;
		
		print "<center>".$text."</center>";
	}
}
else 
{
	echo "No rows returned.";
}


/* sanitize_search_string - cleans up a search string submitted by the user to be passed
     to the database. NOTE: some of the code for this function came from the phpBB project.
   @arg $string - the original raw search string
   @returns - the sanitized search string */
function sanitize_search_string($string) {
	static $drop_char_match =   array('^', '$', '<', '>', '`', '\'', '"', '|', ',', '?', '~', '+', '[', ']', '{', '}', '#', ';', '!', '=');
	static $drop_char_replace = array(' ', ' ', ' ', ' ',  '',   '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

	/* Replace line endings by a space */
	$string = preg_replace('/[\n\r]/is', ' ', $string);
	/* HTML entities like &nbsp; */
	$string = preg_replace('/\b&[a-z]+;\b/', ' ', $string);
	/* Remove URL's */
	$string = preg_replace('/\b[a-z0-9]+:\/\/[a-z0-9\.\-]+(\/[a-z0-9\?\.%_\-\+=&\/]+)?/', ' ', $string);

	/* Filter out strange characters like ^, $, &, change "it's" to "its" */
	for($i = 0; $i < count($drop_char_match); $i++) {
		$string =  str_replace($drop_char_match[$i], $drop_char_replace[$i], $string);
	}

	$string = str_replace('*', ' ', $string);

	return $string;
}

?>
<!--		<center><h1 id="timedisplay"></h1></center>
--!> 
</body>
</html>
