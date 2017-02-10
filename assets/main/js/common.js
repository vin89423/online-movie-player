/*
 * Copyright 2015 Vin Wong @ vinexs.com
 *
 * All rights reserved.
 */

(function(jQuery){

	jQuery.extend({
		url: (function(){
			var data = {};
			var $meta = $('meta[name=data-url]');
			if ($meta.length > 0) {
				var content = $meta.attr('content').split(',');
				for (var i=0; i < content.length; i++) {
					var variable = content[i].trim().split('=');
					data[variable[0]] = variable[1];
				}
			}
			return data;
		})()
	});

})(jQuery);

String.prototype.hashCode = function() {
	var hash = 0, i, chr, len;
	if (this.length === 0) {
		return hash;
	}
	for (i = 0, len = this.length; i < len; i++) {
		chr   = this.charCodeAt(i);
		hash  = ((hash << 5) - hash) + chr;
		hash |= 0;
	}
	return hash;
};

THEATER = {
	list: [],
	player: null,
	langText: {
		zh: '繁體中文',
		zs: '简体中文',
		en: 'English'
	},
	play: function(movie){
		var movieStorage = $('body').attr('data-movie-path');
		if (!movieStorage) {
			return;
		}
		if (THEATER.player != null) {
			THEATER.stop();
		}
		$btnPlay = $('#preview button[data-action=play]').hide();
		$btnStop = $('#preview button[data-action=stop]').show();

		var $threater = $('#theater');
		var $video = $('<video />').attr({
			'id': 'player',
			'width': '100%',
			'controls': true
		});
		$video.append('<source src="'+ $.url.activity + movieStorage + movie.filename +'" type="video/mp4">');
		for (var lang in movie.subtitle) {
			var $track = $('<track />').attr({
				src: $.url.activity + movieStorage + movie.subtitle[lang],
				kind: 'subtitles',
				srclang: lang,
				label: isset(THEATER.langText[lang]) ? THEATER.langText[lang] : 'Code: '+ lang
			});
			$video.append($track);
		}
		$video.find('track').eq(0).attr('default', true);
		$threater.append($video);
		THEATER.player = document.getElementById('player');
		THEATER.player.play();
	},
	pause: function(){
		if (THEATER.player != null) {
			THEATER.player.pause();
		}
	},
	stop: function(){
		if (THEATER.player != null) {
			THEATER.player = null;
			$('#theater').html('');
			$btnPlay = $('#preview button[data-action=play]').show();
			$btnStop = $('#preview button[data-action=stop]').hide();
		}
	},
	goTo: function(sec) {
		if (THEATER.player != null) {
			THEATER.player.currentTime = sec;
		}
	},
	getList: function(){
		var $list = $('#list');
		var $category = $('#category');
		$list.html('');
		$.ajax({
			url: $.url.activity + 'movie_list'+ ($category.attr('data-name') ? '/' + $category.attr('data-name') : '') +'?' + $.now(),
			dataType: 'json',
			success: function(json) {
				if (json.status != 'OK') {
					return;
				}
				if (json.data.category != null) {
					for (var name in json.data.category) {
						var category = json.data.category[name];
						var $item = $('<a />').addClass('category').attr('href', name);
						$item.append($('<span />').html('<i class="fa fa-list"></i> ' + category.name));
						$list.append($item);
					}
				}

				THEATER.list = json.data.movies;
				for(var i = 0; i < THEATER.list.length; i++) {
					var movie = THEATER.list[i];
					var $item = $('<div />').addClass('item').attr('data-index', i);
					$item.append($('<span />').text(movie.name));
					$list.append($item);
				}
				$list.find('.item').click(function(e){
					e.preventDefault();
					var $item = $(this);
					THEATER.previewMovie(parseInt($item.attr('data-index')));
				});

				THEATER.resetListHeight();
			}
		});
	},
	resetListHeight: function() {
		$('.movie-list').height($('.full-screen').outerHeight() - $('.preview-box').outerHeight() - 5);
	},
	previewMovie: function(index) {
		var movie = THEATER.list[index];
		var url = $.url.activity + 'local_preview.php?' + $.now();
		var localEpisode = true;
		if (movie.name && movie.year) {
			localEpisode = false;
			url = 'http://www.omdbapi.com/?t='+ encodeURIComponent(movie.name) +'&y='+ movie.year +'&plot=full&r=json';
		}
		if (movie.id) {
			localEpisode = false;
			url = 'http://www.omdbapi.com/?i='+ movie.id +'&plot=full&r=json';
		}
		$.ajax({
			url: url,
			dataType: 'json',
			timeout: 3500,
			beforeSend: function(){
				$('.movie-list').parents('.row').find('.loading-layer').show();
			},
			success: function(json) {
				THEATER.initalPreviewBox(json, movie, localEpisode);
			},
			error: function() {
				THEATER.initalPreviewBox({
					'Response': "False",
				}, movie, localEpisode);
			}
		});
	},
	initalPreviewBox: function(json, movie, localEpisode) {
		$('.movie-list').parents('.row').find('.loading-layer').hide();
		var html = '<div class="row">';
			html+= '	<div class="col-xs-12">';
			html+= '		<div class="title">'+ ((json.Response == "True") ? json.Title : movie.name) +'</div>';
			html+= '	</div>'
			html+= '	<div class="col-xs-'+ ((movie.poster || json.Response == "True") ? 7 : 12) +'">';
			if (json.Response == "True") {
				html+= '	<div><strong>Country</strong>: '+ json.Country +'</div>';
				html+= '	<div><strong>Released</strong>: '+ json.Released +'</div>';
				html+= '	<div><strong>Duration</strong>: '+ json.Runtime +'</div>';
			}
			html+= '		<div class="ctrl">';
			if (localEpisode && movie.link) {
				html+= '			<a class="btn btn-block btn-primary" href="'+ $.url.activity + movie.link +'">';
				html+= '				<i class="fa fa-exchange"></i> Access';
				html+= '			</a>';
			} else {
				html+= '			<button class="btn btn-block btn-primary" data-action="play">';
				html+= '				<i class="fa fa-play"></i> Play';
				html+= '			</button>';
				html+= '			<button class="btn btn-block btn-danger" data-action="stop" style="display: none;">';
				html+= '				<i class="fa fa-stop"></i> Stop';
				html+= '			</button>';
				html+= '			<button class="btn btn-block btn-success" data-action="save-time" style="display: none;">';
				html+= '				<i class="fa fa-save"></i> Save Time';
				html+= '			</button>';
				html+= '			<button class="btn btn-block btn-info" data-action="continue-time" style="display: none;">';
				html+= '				<i class="fa fa-step-forward"></i> Continue from save';
				html+= '			</button>';
			}
			html+= '		</div>';
			html+= '	</div>';
			if (movie.poster || json.Response == "True") {
				html+= '<div class="col-xs-5">';
				html+= '	<img src="'+ $.url.activity +'poster?url='+ encodeURIComponent(movie.poster || json.Poster) +'" style="width: 100%;">';
				html+= '</div>';
			}
			html+= '</div>';
		var $preview = $('.preview-box');
		$preview.html(html).show();
		THEATER.resetListHeight();

		if (!movie.filename) {
			return;
		}

		var hashCode = movie.filename.hashCode();
		$preview.find('button[data-action=play]').click(function(e){
			e.preventDefault();
			THEATER.play(movie);
			setTimeout(function(){
				$preview.find('button[data-action=save-time]').show();
				if ($.cookie.get('data'+ hashCode) != null) {
					$preview.find('button[data-action=continue-time]').show();
				}
				THEATER.resetListHeight();
			}, 1000);
		});
		$preview.find('button[data-action=stop]').click(function(e){
			e.preventDefault();
			THEATER.stop();
		});
		$preview.find('button[data-action=save-time]').click(function(e){
			if (THEATER.player == null) {
				return;
			}
			THEATER.pause();
			$.cookie.add('data'+ hashCode, THEATER.player.currentTime, {path: $.url.activity, expires: Infinity});
			var $btn = $(this);
			$btn.html('<i class="fa fa-check"></i> Time Saved').attr('disabled', true);
			setTimeout(function(){
				$btn.removeAttr('disabled');
			}, 5000);
		});
		$preview.find('button[data-action=continue-time]').click(function(e){
			if (THEATER.player == null) {
				THEATER.play(movie);
				return;
			}
			var skipToSec = 0;
			if ((skipToSec = $.cookie.get('data'+ hashCode)) != null) {
				THEATER.goTo(skipToSec);
			}
		});

		THEATER.resetListHeight();
	}
};
