$(document).ready(function() 
{
	var imgPreview = $("#image-preview");
	
	var inputFile = $("#image-local-chooser");
	var imgUrl = $("#image-url");

	inputFile.change(function() { PreviewFileChoice(this); });

	imgUrl.on('input', function() 
		{
			ClearImgPreview();
			SetImgPreviewSrc($(this).val());
		});

	// Source : https://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded
	function PreviewFileChoice(input)
	{
		if (input.files && input.files[0]) 
		{
    		var reader = new FileReader();

    		reader.onload = function(e) 
    		{
    			SetImgPreviewSrc(e.target.result);
    		}

    		reader.readAsDataURL(input.files[0]);
  		} 
	}

	function SetImgPreviewSrc(src)
	{
		imgPreview.attr('src', src);
	}

	function ClearImgPreview()
	{
		SetImgPreviewSrc("");
	}
});