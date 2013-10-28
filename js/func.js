jQuery(function ($) {
	var pause = 4000;
	var interval = null;
	var listing = $('div.listing');
	var posting = $('form.posting');
	
	function setLoadInterval() {
		clearInterval(interval);
		
		interval = setInterval(loadPosts, pause);
	}
	
	function loadPosts() {
		var time = listing.data('time');
		
		$.post(window.location.href, { stream_js_action: 'loadPosts', time: time }, function (data, status, XHR) {
			if (status == 'success') {
				if (data.type = 'listing') {
					listing.data('time', data.time);
					listing.prepend($(data.markup).find('div.hentry'));
					listing.find('div.hentry:not(.visible)').slideUp(1).slideDown().addClass('visible');
				}
			}
			
			setLoadInterval();
		}, 'json');
	}
	
	setLoadInterval();
	
	listing.find('div.hentry').addClass('visible');
	
	posting.submit(function (e) {
		if (posting.data('disabled') !== true) {
			e.preventDefault();
			$this = $(this);
			
			$.ajax({
				cache: false,
				url: window.location.href,
				type: $this.attr('method'),
				data: $this.serialize(),
				success: function (data, status, XHR) {
					posting.data('disabled', false);
					posting.find('[disabled]').removeAttr('disabled');
					
					$this.find('textarea, input[type="text"]').val('').first().focus();
				}
			});
			
			posting.data('disabled', true);
			posting.find('textarea, input').attr('disabled', 'disabled');
		}
	}).keydown(function (e) {
		if (e.keyCode == 13 && e.ctrlKey) {
			$(this).submit();
		}
	});
});