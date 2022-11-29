$(function () {
	$('[data-toggle="tooltip"]').tooltip();
	$('button[data-loading-text]').on('click', function () {
		$(this).button($(this).data('loading-text'));
	});
	$('button[data-confirmation]').on('click', function () {
		return window.confirm($(this).data('confirmation'));
	});
	$('button[data-disabled]').on('click', function () {
		return false;
	})
})