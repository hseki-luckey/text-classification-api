<?php
	$display = false;

	if(isset($_POST['event_name'])){
		$data = array(
			'apikey' => 'APIキー',
			'text' => $_POST['event_name'],
			'model_id' => 'モデルID'
		);
		$url_query = http_build_query($data);
		$result = file_get_contents("https://api.a3rt.recruit-tech.co.jp/text_classification/v1/classify?{$url_query}");
		$target_labels = json_decode($result, true);
		if($target_labels && $target_labels['status'] == 0) $display = true;
	}

	if($display){
		$chart_label = array();
		foreach($target_labels['classes'] as $label){
			switch ($label['label']){
				case '街コン':
					$chart_label['machicon'] = $label['probability'];
					break;
				case 'プチ街コン':
					$chart_label['petit'] = $label['probability'];
					break;
				case '恋活パーティー':
					$chart_label['koi'] = $label['probability'];
					break;
				case '婚活パーティー':
					$chart_label['koncats'] = $label['probability'];
					break;
				case '自分磨き':
					$chart_label['jibun'] = $label['probability'];
					break;
			}
		}
	}
?>
<!DOCTYPE html>
<meta charset="UTF-8">
<title>イベント名で判別！簡単お名前チェッカー</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<style type="text/css">
html {
	background: rgb(122, 206, 224);
}
.search_box{
	padding: 20px 20px 30px 20px;
	margin: 30px;
	background-color: #FFF;
	border-radius: 20px;
	border: 3px solid;
}
dl.search1{
	position:relative;
	background-color:#fff;
	border:1px solid #aaa;
	border-radius:6px;
}
dl.search1 dt{
	padding:2px;
}
dl.search1 dt input{
	width:70%;
	height:30px;
	line-height:30px;
	background:none;
	border:none;
}
dl.search1 dd{
	position:absolute;
	top:0px;
	right:0px;
	width:30%;
}
dl.search1 dd button{
	display:block;
	background:#2b71c8;
	width:100%;
	height:36px;
	line-height:36px;
	border:none;
	border-radius: 0 6px 6px 0;
}
dl.search1 dd button:hover {
	background:#4e91e4;
}
dl.search1 dd button span{
	display:block;
	color:#FFF;
}
.event_name {
	text-align: center;
	margin-top: 20px;
	margin-bottom: 15px;
}
canvas {
	margin-bottom: 20px;
}
table.type03 {
	width: 100%;
	border-collapse: collapse;
	text-align: left;
	line-height: 1.5;
	border-top: 1px solid #ccc;
	border-left: 3px solid #369;
}
table.type03 th {
	width: 50%;
	padding: 10px;
	font-weight: bold;
	vertical-align: top;
	color: #153d73;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;

}
table.type03 td {
	width: 349px;
	padding: 10px;
	vertical-align: top;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
}
</style>

<div class="search_box">
<form action="" name="search1" method="post">
	<dl class="search1">
		<dt><input type="text" name="event_name" value="" placeholder="イベント名を入力..." /></dt>
		<dd><button><span>チェック！</span></button></dd>
	</dl>
</form>

<?php if($display): ?>
<div class="event_name">
	★★ イベント名 ★★<br />
	<?php echo $_POST['event_name']; ?>
</div>
<canvas id="eventChart"　width="200" height="200"></canvas>

<table class="type03">
	<tr>
		<th scope="row">街コン</th>
		<td><?php echo $chart_label['machicon']; ?></td>
	</tr>
	<tr>
		<th scope="row">プチ街コン</th>
		<td><?php echo $chart_label['petit']; ?></td>
	</tr>
	<tr>
		<th scope="row">恋活パーティー</th>
		<td><?php echo $chart_label['koi']; ?></td>
	</tr>
	<tr>
		<th scope="row">婚活パーティー</th>
		<td><?php echo $chart_label['koncats']; ?></td>
	</tr>
	<tr>
		<th scope="row">自分磨き</th>
		<td><?php echo $chart_label['jibun']; ?></td>
	</tr>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script>
var ctx = document.getElementById("eventChart").getContext("2d");
var eventChart = new Chart(ctx, {
	type: "pie",
	data: {
		labels: ["街コン", "プチ街コン", "恋活パーティー", "婚活パーティー", "自分磨き"],
		datasets: [{
			backgroundColor: ["#59493f", "#7e6b5a", "#9b8675", "#b59e8d", "#d6beae"],
			hoverBackgroundColor: ["#68554a", "#8d7865", "#a69384", "#bfab9c", "#dfccbf"],
			data: [
				<?php echo $chart_label['machicon']; ?>,
				<?php echo $chart_label['petit']; ?>,
				<?php echo $chart_label['koi']; ?>,
				<?php echo $chart_label['koncats']; ?>,
				<?php echo $chart_label['jibun']; ?>
			]
		}]
	},
	options: {
		legend: {
			display: false
		}
	}
});
</script>
<?php endif; ?>
</div>
</html>