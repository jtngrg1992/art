<?php
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
$data=array();
$con = new mysqli("127.0.0.1", "snapd", "yzasa6ata","zadmin_snapdeal");
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$json_failure=array();
$json_failure['success']=False;
$page=0;
$timestamp=1;
$survey_id=1;
$table_columns=array('sno','orderid','email','survey_id','ticket','product_name','q_1','q_2','q_3','q_4','comment','is_mobile','ip','user_agent','timestamp','survey_sent');
if(isset($_GET['date_lower']) && isset($_GET['date_upper'])){
	if(validateDate($_GET['date_lower']) && validateDate($_GET['date_upper']))
		$timestamp = " timestamp between '". $_GET['date_lower']. "' and '". $_GET['date_upper']."' ";
	else{
		$json_failure['error'][]="Either one of dates given is in wrong format.(yyyy-mm-dd H:i:s)";
		
	}
}
elseif(isset($_GET['date_lower'])){
	if(validateDate($_GET['date_lower']))
		$timestamp = " timestamp > '". $_GET['date_lower']. "' ";
	else{
		$json_failure['error'][]="Date given is in wrong format.(yyyy-mm-dd H:i:s)";
		
	}
}
elseif(isset($_GET['date_upper'])){
	if(validateDate($_GET['date_upper']))
		$timestamp = " timestamp < '". $_GET['date_upper']."' ";
	else{
		$json_failure['error'][]="Date given is in wrong format.(yyyy-mm-dd H:i:s)";
		
	}
}
elseif(isset($_GET['day'])){
	if(is_numeric($_GET['day'])){
		$day=intval($_GET['day']);
		if(intval($_GET['day']) < 1)
			$day="";
		else
			$timestamp="timestamp > DATE_SUB(NOW(), INTERVAL ".$day." DAY)";
	}
	else{
		$json_failure['error'][]="Days must be a number;";
	
	}
}

if(isset($_GET['survey_id'])){
	if(intval($_GET['survey_id']) < 4 and intval($_GET['survey_id']) > 0)
		$survey_id = "survey_id = '". $_GET['survey_id']."' ";
	else{
		$json_failure['error'][]=" Given Survey ID iis not correct;";
	}
}
	
if(isset($_GET['page'])){
	if(is_numeric($_GET['page'])){
		$page=intval($_GET['page']);
		if(intval($_GET['page']) < 1)
			$page=0;
	}
	else{
		$json_failure['error'][]="Page no must be a number;";
	}
}
if (isset($_GET['columns'])){
	$columns=explode(",",$_GET['columns']);
	$columns=array_intersect($columns,$table_columns);
	$columns_list= implode(",",$columns);
}
else
	$columns_list="*";
if(isset($json_failure['error'])){
	echo json_encode($json_failure);
	exit();
}

$display=mysqli_query($con,"select count(*) as num FROM csat_survey where ".$timestamp." and ".$survey_id);
$total_pages=mysqli_fetch_array($display);
$total_pages=$total_pages['num'];
$targetpage="api.php";
$limit=100;
if($page==0)
	$page=1;
$lastpage=ceil($total_pages/$limit);

if($page>=$lastpage)
	$page=$lastpage;

if($page)
	$start=($page-1)*$limit;
else
	$start=0;


$res=mysqli_query($con,"select ".$columns_list." from csat_survey where ".$timestamp." and ".$survey_id . " limit " .$start. "," .$limit); 
//$res=mysqli_query($con,"SELECT * FROM `nps_data1` limit " . $start . " , " . $limit); 
$i=0;
$datatype=mysqli_query($con,"SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name =  'csat_survey'");

while ($nimg= mysqli_fetch_assoc($res)){
	$data[$i]=$nimg;
	$i++;							
}
while($da=mysqli_fetch_assoc($datatype))
		$datatype_list[]=$da;
header('Content-Type: application/json');
$json_res=array();
$json_res['result_ok']=true;
$json_res['total_count']=$total_pages;
$json_res['page']=$page;
$json_res['total_pages']=$lastpage;
$json_res['results_per_page']=$limit;
$json_res['Data Type']=$datatype_list;
$json_res['data']=$data;
echo json_encode($json_res);
?>
 