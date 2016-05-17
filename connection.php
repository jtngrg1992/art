<?php
   // connect to mongodb
	$c=new MongoClient("jlabs.co:27017", array("username" => "artSales", "password" => "Jl@B$!@#","db"=>"artists"));
	$db=$c->artists;
	$coll=$db->paintings;
    $coll2=$db->artists;
	
?>
