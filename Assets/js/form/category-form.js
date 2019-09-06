$(document).ready(function()
{
	$("#category-form").validate(
		{
			rules:
			{
				'category-name':
				{
					required: true
        	    	,maxlength: 35
        	 	}
        	 	, 'category-color': 
        	 	{
        	    	required: true
        	 	}
			}
			, messages:
			{
				'category-name':
				{
					required: "Le nom est obligatoire"
					, maxlength: jQuery.validator.format("Le nom doit contenir moins de {0} caractères.")
				}
				, 'category-color':
				{
					required: "La couleur est obligatoire"
				}
			}
			, errorPlacement: function(error, element) 
			{
      			error.insertAfter($(element).parent());
  			}
  			, highlight: function(element, errorClass)
  			{
  				$(element).addClass(errorClass);
  				$(element).siblings(".form__input-container__icon").addClass(errorClass);
  			}
  			, unhighlight: function(element, errorClass)
  			{
  				$(element).removeClass(errorClass);
  				$(element).siblings(".form__input-container__icon").removeClass(errorClass);
  			}
		}
	);
});