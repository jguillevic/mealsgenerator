$(document).ready(function()
{
	$("#user-login-form").validate(
		{
			rules:
			{
				'user-username':
				{
					required: true
        	    	,maxlength: 35
        	 	}
        	 	, 'user-password': 
        	 	{
        	    	required: true
        	    	,maxlength: 100
        	 	}
			}
			, messages:
			{
				'user-username':
				{
					required: "L'identifiant est obligatoire"
					, maxlength: jQuery.validator.format("L'identifiant doit contenir moins de {0} caractères.")
				}
				, 'user-password':
				{
					required: "Le mot de passe est obligatoire"
					, maxlength: jQuery.validator.format("Le mot de passe doit contenir moins de {0} caractères.")
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