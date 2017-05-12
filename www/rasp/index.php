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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
<script src="snow/jquery.snow.min.1.0.js"></script>

<style type="text/css">
body {
    background: url(bg3.png); /* Фоновый рисунок */
   }
.outer {
    width: 1080px;
    height: 1900px;
    background-color: rgba(255, 255, 255, 0.8);
    text-align: center;
    vertical-align: middle;

    //background-color: #ffc;
}

.inner {
    display: inline-block;
    background-color: rgba(255, 255, 255, 0.5);

    //background-color: #fcc;

}

.demo {
    font-size: 3vw; /* 3% of viewport width */
}

h1 {
    font-size: 210%; /* Размер шрифта в процентах */ 
   }
   
</style>


<title>rasp</title>

<meta content="text/html; charset=utf-8" name="Content">
<meta http-equiv="Content-Type"
	content="text/html; charset=utf-8">
<meta name="keywords"
	content="web, database, gui">
<meta name="description"
	content="web database gui">

</head>
<body>
<div id="page-wrapper">
<table>
    <td class="outer">
        <div class="inner">
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

$fields_array=array(
		'SUBJNAME'=>array(
				"name"=>'Врач'
				,"type"=>"text"
				,"html"=>""
				,"visible"=>1),
		'SPECIALIZATION'=>array(
				"name"=>'Специальность'
				,"html"=>" style=\"text-align: center;\""
				,"visible"=>1),
		'CABINET'=>array(
				"name"=>'Кабинет'
				,"html"=>" style=\"text-align: center;\""
				,"visible"=>0),
		'TIMEWORK'=>array(
				"name"=>'Время'
				,"type"=>"text"
				,"html"=>" style=\"text-align: center; font-size: smaller\""
				,"visible"=>1),
		'COMMENTS'=>array(
				"name"=>'Прочее'
				,"type"=>"text"
				,"html"=>""
				,"visible"=>0),				
		'CNTFREETIMESLOTS'=>array(
				"name"=>'Свободно'
				,"type"=>"text"
				,"html"=>""
				,"visible"=>0)
);

/* Set up and execute the query. */
$tsql = "SELECT top 100
SubjName
,Specialization
,Cabinet
,TimeWork
,Comments
,CntFreeTimeSlots
from pl_GetSubjsGrid_tablo(getdate(),18)
order by SubjName
";

$stmt = $conn->query($tsql);
$rows = $stmt->fetchAll();

$numRows = count($rows);
//echo "<p>$numRows Row" . ($numRows == 1 ? "" : "s") . " Returned </p>";

if($numRows>0)
{	
	print '<table cellspacing="0" cellpadding="1" border="1" align="center"
	width="100%" >
	<tbody>';
		
	$metadata=array();
	$i=0;
	// add the table headers
	foreach ($rows[0] as $key => $useless){
		//print "<th>$key</th>";
		$metadata[$i]['Name']=$key;
		$i++;
	}
	
	//print_r($metadata);
	$column_name="";
/*
	//internal column names
	echo '<tr>';
	for ($i=0;$i < count($metadata);$i++)
	{
		$meta = $metadata[$i];
		//print_r($meta);
		$column_name=strtoupper($meta['Name']);
		
		if(get_column_visibility($column_name)==1)
			echo '<td>' . $meta['Name'] . '</td>';
	}
	echo '</tr>';
*/

	//human readable column names
	echo '<tr>';
	for ($i=0;$i < count($metadata);$i++)
	{
		$meta = $metadata[$i];
		$column_name=strtoupper($meta['Name']);
		//print_r($meta);
		$header=get_column_username($column_name,"&nbsp");
		
		if(get_column_visibility($column_name)==1)
			echo '<td'.get_column_style($column_name).'><h1>' . $header . '</h1></td>';
	}
	echo '</tr>';


	/* Retrieve each row as an associative array and display the results.*/
	foreach ($rows as $row)
	{
		$rowColor='White';
		echo '<tr>';
		//echo '<tr>';
		
		//print_r($row);
		
		for ($i=0;$i < count($row);$i++)
		{
			$column_name=$metadata[$i]['Name'];
					
			if(get_column_visibility($column_name)==1)
			{					
				$field=$row[$column_name];
				$text='';
					
				if (gettype($field)=="object" && (get_class($field)=="DateTime"))
				{
					$text = $field->format('Y-m-d');
					if($text=='1899-12-30')
						$text="&nbsp";
				}
				else
					$text = trim($field);

				if($text=='')
					$text ='&nbsp';
				
				echo '<td'.get_column_style($column_name).'><h1>' . $text . '</h1></td>';
			}
		}
		print "</a></tr> \n";
	}
	print '	</tbody>
	</table>';
}
else 
{
	echo "No rows returned.";
}


function get_column_visibility($name, $default = 1)
{
	global $fields_array;
	
	$name=strtoupper($name);

	if (isset($fields_array[$name]['visible']))
		return $visible_flag=$fields_array[$name]['visible'];

	else
		return $default;
}
function get_column_username($name, $default = '')
{
	global $fields_array;

	if (isset($fields_array[$name]['name']))
		return $visible_flag=$fields_array[$name]['name'];

	else
		return $default;
}

function get_column_style($name, $default = '')
{
	global $fields_array;
	
	$name=strtoupper($name);

	if (isset($fields_array[$name]['html']))
		return $visible_flag=$fields_array[$name]['html'];

	else
		return $default;
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
		<h1 id="timedisplay"></h1>
        </div>
    </td>
</table>
</div>
</body>
</html>
