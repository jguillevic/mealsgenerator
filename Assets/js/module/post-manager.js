$(document).ready(function()
{
	$(".delete-post-button").click(function(event)
	{
		var postId = $(this).attr("data-post-id");

		var path = "/post/delete?id=" + postId;

		var result = confirm("Confirmez-vous la suppression ?");
		if (result == true)
		{
			$.get(path, function()
				{
				$("#post-" + postId).fadeOut(600, function() { $(this).remove(); });
				});
		}
	});
});