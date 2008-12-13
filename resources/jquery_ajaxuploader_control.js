$(document).ready(function() {
	//var __PAGE_ID = (typeof __PAGE_ID == 'undefined') ? '' : __PAGE_ID;
	
	$('.tx-ajaxupload-upload-trigger2').each(function() {
		$(this).upload({
			name: 'tx_ajaxupload[fileToUpload]',
			method: 'post',
			enctype: 'multipart/form-data',
			action: '/index.php',
			params: {
				'eID': 'ajaxupload',
				'tx_ajaxupload[ajaxAction]': 'saveFile',
				'tx_ajaxupload[previewImageId]': 'previewImage_'+$(this).attr('id').replace('uploader_', ''),
				'tx_ajaxupload[uploaderId]': $(this).attr('id').replace('uploader_', ''),
				'id': __PAGE_ID
			},
			uploaderId: $(this).attr('id').replace('uploader_', ''),
			onSubmit: function() {
			},
			onComplete: function(response) {
				// console.log(response);
				response = eval('('+response+')');
				// console.log(response);
				if (response.status == 'success') {
					self.location.reload();
				}
			}
		});
	});
});
