<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>File Upload</title>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
		<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
		<style type="text/css">
			img {
				height: 100%;
				width: 40%;
			}

			body {
				background-image: url('bg1.jpg');
			}

			h2 {
				color: #FFFFFF;
				text-align: left; 
			}
		</style>
    </head>
	
	<body>
	
		<div class="row" style="margin-top: 3%; text-align: center; padding: 3%;">
			<div class="col-md-3"></div>
			<div class="col-md-2">
				<img src="{{asset('logo.png')}}">
			</div>
			<div class="col-md-4">
				<h2>Upload CSV File:</h2>
			</div>
			<div class="col-md-3"></div>
		</div>
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-block">
						<form id="upload-form" action="{{url('upload')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="file-loading">
								<input id="file" name="file" type="file" class="file" accept=".csv|.xls|.xlsx" data-preview-file-type="any">
							</div>
							<div id="errorBlock" class="help-block">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>	

		<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/fileinput.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				toastr.options = {
					"closeButton" : true,
					"progressBar" : true,
					'extendedTimeOut': 0,
					'timeOut': 0,
					'tapToDismiss': false,
					// 'optionsOverride' = 'positionclass:toast-bottom-full-width',
					// 'positionClass' "toast-bottom-full-width",
					'showEasing':'swing',
					'iconClass':'toast-error',
					'titleClass':'toast-title',
	  				'messageClass':'toast-message'
				}

				$.fn.fileinputBsVersion = "3.3.7"; 
				$("#file").fileinput({
					showUpload : false,
					// uploadUrl : "{{url('serialize')}}",
					allowedFileExtensions : ["csv", "xls", "xlsx"],
					elErrorContainer : "#errorBlock", 
					previewFileType :'any',
					previewClass: "bg-light"
				});	

				$('.file-drop-zone-title').text("Drag and Drop file here...");

				$('#upload-form').on('submit', function(event) {				
					var valid = ["csv", "xls", "xlsx"];
					var ext = $('#file').val().split('.').pop().toLowerCase();
			        if ($.inArray(ext, valid) == -1) {
			        	event.preventDefault();
			            toastr.error("Invalid format. Please upload csv or excel.", "ERROR!");
			            $('.fileinput-remove-button').click();
			        }
				});

				@if(Session::has('error'))				
					toastr.error("{{ session('error') }}", "ERROR!");
				@endif	
			});
		</script>
	</body>
</html>