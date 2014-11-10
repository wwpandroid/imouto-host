<?php
include 'vendor/autoload.php';

use lv\file\Path;
use lv\file\text\SplFile;

$path = new Path();
$hosts = array(
	array(
		'path' => 'imouto.host',
		'desc' => '推荐使用，包含了：google服务、facebook、Twitter、YouTube、dropbox等服务'
	), 
	array(
		'path' => 'netsh-ipv4',
		'desc' => '推荐使用，包含了常用的网站hosts'
	), 
	array(
		'path' => 'netsh-ipv6',
		'desc' => '推荐使用，适合支持IPV6的朋友，包含了常用的网站hosts'
	), 
	array(
		'path' => 'Hosts-a%CC%88',
		'desc' => '基于imouto.host，增加了PS、Xbox、任天堂、Android等服务'
	), 
	array(
		'path' => 'Simple+U+Hosts',
		'desc' => '2014年8月20日更新，备用选择'
	)
);

foreach ($hosts as $key => $dir) 
{
	$str = $path->parents()->open(urldecode($dir['path']).'\hosts')->get();
	$hosts[$key]['data'] = (new SplFile($str))->fread();

	$path->parents();
}

?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<title>hosts get</title>
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css" />
	<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.map"></script>
	<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="bower_components/zeroclipboard/dist/ZeroClipboard.min.js"></script>
	<style type="text/css">
	#box {
		margin: 20px;
	}

	.ctrl-btn p {
		text-align: center;
	}

	.ctrl-btn span {
		display: none;
	}

	.ctrl-btn font {
		color: #f00;
	}

	#myTabContent {
		padding-top: 14px;
	}
	</style>
</head>
<body>
	<div id="box" class="panel panel-default">
		<div class="panel-body">
			<h2>选择hosts</h2>
			<ul id="myTab" class="nav nav-tabs" role="tablist">
				<?php foreach ($hosts as $key => $host) { ?>
				<li role="presentation"<?php echo $key ? '' : ' class="active"'; ?>>
					<?php printf('<a href="#tab-link-%s" id="tab-btn-%1$s" role="tab" data-toggle="tab" aria-controls="tab-link-%1$s"%s>%s</a>', $key, $key ? '' : ' aria-expanded="true"', urldecode($host['path'])); ?>
				</li>
				<?php } ?>
			</ul>

			<div id="myTabContent" class="tab-content">
				<?php foreach ($hosts as $key => $host) { ?>
				<?php printf('<div role="tabpanel" class="tab-pane fade%s" id="tab-link-%s" aria-labelledby="tab-btn-%2$s">', $key ? '' : ' in active', $key); ?>
					<p><?php echo $host['desc'] ?></p>
					<div class="ctrl-btn">
						<p><?php printf('<button type="button" id="copy-%s" class="btn btn-success btn-lg"  onclick="select_field(\'host-%1$s\')">选择并复制当前所有hosts</button>', $key); ?></p>
						<p><?php printf('<span class="tips-%s"><font>hosts 复制成功</font>，若您当前的浏览器默认不支持复制当前内容，请手动操作”<font>ctrl + c</font>“进行复制</span>', $key); ?></p>
					</div>
					<p>
						<?php printf('<textarea id="host-%s" name="host-%1$s" class="form-control" rows="15">%s</textarea>', $key, isset($host['data']) ? $host['data'] : ''); ?>
					</p>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	function select_field(id){
		document.getElementById(id).focus();
		document.getElementById(id).select();
	}

	//设置路径
	ZeroClipboard.config( { moviePath: 'bower_components/zeroclipboard/dist/ZeroClipboard.swf' } );
	function copy(num) {
		//create client
		var client=new ZeroClipboard($('#copy-' + num));

		client.on("ready", function(readyEvent) {
			client.on("copy", function(event) {
				var clipboard = event.clipboardData;
				var copyText = $('#host-' + num).val();
				var tips = $('.tips-' + num).show();

				clipboard.setData("text/plain", copyText); // 将内容添加到剪切板
				setTimeout(function() {
					tips.hide();
				}, 5000);
			});
		});
	}

	$(function() {
		$('#myTabContent button').each(function(i) {
			copy(i);
		});
	});
    </script>
</body>
</html>