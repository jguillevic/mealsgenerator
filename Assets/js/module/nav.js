$(document).ready(function()
{
	$(".nav__burger").click(function()
	{
		$(".nav__overlay").toggleClass("nav__overlay--open");
	});

	$(".nav__overlay__close").click(function()
	{
		$(".nav__overlay").toggleClass("nav__overlay--open");
	});

	$(".nav__overlay__menu-subitem-content").slideUp();

	var currentMenuItem = null;

	$(".nav__overlay__menu-item-drop").click(function()
	{
		if ($(this).hasClass("nav__overlay__menu-item-drop--down"))
		{
			if (currentMenuItem !== null)
			{
				var menuSubitemContent = $(currentMenuItem).parent().find(".nav__overlay__menu-subitem-content");
				menuSubitemContent.slideUp();

				$(currentMenuItem).removeClass("nav__overlay__menu-item-drop--up");
				$(currentMenuItem).addClass("nav__overlay__menu-item-drop--down");
			}

			$(this).removeClass("nav__overlay__menu-item-drop--down");
			$(this).addClass("nav__overlay__menu-item-drop--up");

			var menuSubitemContent = $(this).parent().find(".nav__overlay__menu-subitem-content");
			menuSubitemContent.slideDown();

			currentMenuItem = $(this);
		}
		else
		{
			$(this).removeClass("nav__overlay__menu-item-drop--up");
			$(this).addClass("nav__overlay__menu-item-drop--down");

			var menuSubitemContent = $(this).parent().find(".nav__overlay__menu-subitem-content");
			menuSubitemContent.slideUp();

			currentMenuItem = null;
		}
	});
});