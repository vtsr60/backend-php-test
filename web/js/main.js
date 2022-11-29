$(function () {
	$('[data-toggle="tooltip"]').tooltip();
	$('button[data-loading-text]').on('click', function () {
		$(this).button($(this).data('loading-text'));
	})
})