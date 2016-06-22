
$(document).ready(function() {

	$('.summernote').summernote({
		height: 400,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'clear']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'image', 'video']],
			['view', ['fullscreen', 'codeview']]
		],
		buttons: {
   			image: function() {
				var ui = $.summernote.ui;
				var button = ui.button({
					contents: '<i class="fa fa-image" />',
					tooltip: "Image manager",
					click: function () {
						$('#modal-image').remove();
						$.ajax({
							url: 'php/filemanager.php',
							dataType: 'html',
							success: function(html) {
								$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
								$('#modal-image').modal('show');
							}
						});						
					}
				});
				return button.render();
			}
		}
	});
});


