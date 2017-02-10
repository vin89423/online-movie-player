<!DOCTYPE html>
<!--{ Copyright 2017 Vin Wong @ vinexs.com }-->
<html><!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>Online Movie Player - Powered by Vinexs</title>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    <link class="respond" type="text/css" rel="stylesheet"
        href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" class="respond" href="<?php echo $URL_RSC; ?>css/common.min.css"/>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="<?php echo $URL_ASSETS; ?>jextender/1.0.8/jExtender.min.js"></script>
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo $URL_RSC; ?>img/favicon.ico"/>
    <link type="image/x-icon" rel="icon" href="<?php echo $URL_RSC; ?>img/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="data-url"
          content="root=<?php echo $URL_ROOT; ?>, activity=<?php echo $URL_ACTIVITY; ?>, repos=<?php echo $URL_REPOS; ?>, rsc=<?php echo $URL_RSC; ?>"/>
</head>
<body data-movie-path="movies/<?php echo empty($category_name) ? '' : $category_name.'/'; ?>">
<nav class="navbar navbar-fixed-top navbar-player">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="<?php echo $URL_ACTIVITY; ?>" class="navbar-brand">
                <i class="fa fa-play-circle"></i>
                <span>Online Movie Player</span>
            </a>
            <?php
            if (!empty($category)) {
                ?>
                <div class="nav-path-title">
                    <i class="fa fa-angle-right"></i>
                    <span id="category" data-name="<?php echo $category_name; ?>" >
                        <?php echo $category['name']; ?>
                    </span>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</nav>
<div class="container-fluid full-screen">
	<div class="row" style="height: 100%;">
		<div class="col-xs-4" style="height: 100%;">
			<div class="preview-box"></div>
            <div class="movie-list">
    			<div id="list" style="overflow-x: hidden; overflow-y: auto;"></div>
            </div>
            <div class="loading-layer" style="display: none;">
                <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
            </div>
		</div>
		<div class="col-xs-8" style="background-color: #222222; height: 100%;">
			<div id="theater"></div>
		</div>
	</div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
$(function(){
    $('.full-screen').height($(window).height() - $('.navbar').outerHeight());

    $.getResource(['<?php echo $URL_RSC; ?>js/common.js'], function(){
        THEATER.getList();
    });
});
</script>
</body>
</html>
