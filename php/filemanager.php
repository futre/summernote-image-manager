<?php
$site_url = 'http://localhost/summernote/upload/'; //edit path
$directory = "../upload/"; //edit path
$images = glob($directory.'*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
?>
<style>
.thumb > span:after {
     content: "\f00e"; 
	font-family: FontAwesome;
    position: absolute;
	font-size:22px;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: all .6s;
    -webkit-transition: all .6s;
    background: rgba(0,0,0,0.7);
    color: #FFF;
    text-align: center;
    padding:45px 0;
}
.thumb > span:hover:after {
    opacity:1;
}
.thumb img {
	width:100%
}
.thumb {
	border:1px solid #aaa;
	padding:5px;
	margin-bottom:15px;
	position:relative;
	box-shadow: 0 1px 3px rgba(0,0,0,0.9);
	overflow:hidden	;
	height:120px
}
.thumb img {
    -webkit-transition: all .6s ease; /* Safari and Chrome */
  	-moz-transition: all .6s ease; /* Firefox */
  	-o-transition: all .6s ease; /* IE 9 */
  	-ms-transition: all .6s ease; /* Opera */
  	transition: all .6s ease;
}
.thumb:hover img {
    -webkit-transform:scale(1.25); /* Safari and Chrome */
    -moz-transform:scale(1.25); /* Firefox */
    -ms-transform:scale(1.25); /* IE 9 */
    -o-transform:scale(1.25); /* Opera */
     transform:scale(1.25);
}
body.modal-open { overflow: hidden!important; }
</style>

<div class="modal-dialog modal-lg"   style="overflow:initial">
	<div class="modal-content">
		<div class="btn-info modal-header">
		<h4 class="modal-title"><i class="fa fa-image"></i>&nbsp;&nbsp;Image manager
		<button type="button" data-toggle="tooltip" title="" id="button-upload" class="btn btn-primary pull-right"><i class="fa fa-upload"></i>&nbsp;&nbsp;UPLOAD IMAGE</button>
		</h4>
		</div>
		<div class="modal-body"  style="height:400px;overflow-y:auto">
        <?php
		$i=0;		
		foreach ($images as $image) { 
		$image = basename($image);
		$image = $site_url.$image
		?>
		<div id="image_<?php echo $i ?>" style="margin:5px;float:left;width:155px;height:145px;">
        <div class="thumb" data-image="<?php echo $image; ?>"><span><img class="pop" style="" src="<?php echo $image; ?>" /></span></div>
		<div style="margin:-10px 0 10px 0" class="pull-right">
		<a data-toggle="tooltip" class="delete-image" data-image_id="<?php echo $i ?>" data-image="<?php echo basename($image) ?>" href="javascript:;" title="Delete image"><i class="fa fa-trash-o fa-lg"></i></a>
		&nbsp;&nbsp;<a data-toggle="tooltip" class="insert-image" data-image="<?php echo $image ?>" title="insert image" href="javascript:;"><i class="fa fa-sign-in fa-lg"></i></a>
		</div>
		</div>
		<?php
		$i++;
		} ?>
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button></div>
	</div>
</div>

<!-- show image popup -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <img src="" id="imagepreview" style="width:100%;" >
      </div>
      <p style="text-align:right;padding-right:20px">
        <button type="button" class="btn btn-default close-modal">Close</button>
      </p>
    </div>
  </div>
</div>

<!-- delete image popup -->
<div class="modal fade" id="imagemodaldelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
	<div class="btn-warning modal-header">
		<h4 class="modal-title"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;Delete Image</h4>
		</div>
      <div class="modal-body">
        Are you sure you want to delete the image?
      </div>
      <p style="text-align:right;padding-right:20px">
        <button type="button" id="delete_image" class="btn btn-primary close-modal">Yes</button>&nbsp;<button type="button" class="btn btn-default close-modal">No</button>
      </p>
    </div>
  </div>
</div>
<script>
$(function(){
	$('[data-toggle="tooltip"]').tooltip();
})

$('.insert-image').on('click', function() {
	var image = $(this).data('image');
	$('#summernote').summernote('editor.insertImage', image);
	$('#modal-image').modal('hide');
})

$(".thumb").on("click", function() {
   $('#imagepreview').attr('src', $(this).data('image')); 
   $('#imagemodal').modal('show'); 
});

$(".close-modal").on("click", function() {
   $('#imagepreview').attr('src', ''); 
   $('#imagemodal').modal('hide'); 
   $('#imagemodaldelete').modal('hide');
});

var image_to_delete;
var image_id;

$(".delete-image").on("click",function() {
	$('#imagemodaldelete').modal('show');
	image_to_delete = $(this).data('image');
	image_id = $(this).data('image_id');
})

$('#delete_image').on('click', function() {
		$.ajax({  
			type: "POST", 
			data:({'image':image_to_delete}),
			url: "php/delete.php",
			success: function(data){
					$("#image_"+image_id).fadeOut()
					$('#imagemodaldelete').modal('hide');
                }
		})	

})	

$('#button-upload').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');

	$('#form-upload input[name="file"]').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name="file"]').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'php/save.php',
				type: 'post',
				data: new FormData($('#form-upload')[0]),
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-upload').html('<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;&nbsp;UPLOADING');
					$('#button-upload').prop('disabled', true);
				},
				complete: function() {
					$('#button-upload').html('<i class="fa fa-upload"></i>&nbsp;&nbsp;UPLOAD IMAGE');
					$('#button-upload').prop('disabled', false);
				},
				success: function(json) {
					$('#summernote').summernote('editor.insertImage', json['content']);
					$('#modal-image').modal('hide');
				},

				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});


</script>
