<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<input type="text" id="test"><input type="button" onclick="func()">
<script src='https://code.jquery.com/jquery-2.2.3.min.js'></script>
<script>
function func(){
	json='[{"id":14328,"usdprice":"0.00","maxusdprice":"11664.34","minusdprice":"10368.30","inrprice":"0.00","maxinrprice":"646964.84","mininrprice":"575079.74"},{"id":14329,"usdprice":"1530.78","maxusdprice":"4783.68","minusdprice":"2870.21","inrprice":"85439.99","maxinrprice":"266999.55","mininrprice":"160199.84"},{"id":14330,"usdprice":"736.48","maxusdprice":"930.68","minusdprice":"620.46","inrprice":"40261.69","maxinrprice":"50878.17","mininrprice":"33919.14"},{"id":14313,"usdprice":"0.00","maxusdprice":"1227.33","minusdprice":"1090.96","inrprice":"0.00","maxinrprice":"48344.53","mininrprice":"42972.91"},{"id":14314,"usdprice":"0.00","maxusdprice":"4772.94","minusdprice":"4432.02","inrprice":"0.00","maxinrprice":"188006.11","mininrprice":"174577.27"}]';
	$.ajax({
		method:"POST",
		url:'api.php',
		data:json,
		success:function(data){
			console.log(data)
		}
	})
}
</script>
</body>
</html>