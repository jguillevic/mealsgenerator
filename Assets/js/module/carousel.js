$(document).ready(function()
{
	var canChange = true;
	var currentCarouselItemIndex = -1;
	var imgAnimationDelay = 200;

	var carousel = $(".carousel");

	var carouselItems = $(".carousel__item");
	var carouselItemsInfos = $(".carousel__item__info");

	var carouselButtonPrevious = $(".carousel__button--previous");
	var carouselButtonNext = $(".carousel__button--next");

	carouselButtonPrevious.click(movePreviousOnClick);
	carouselButtonNext.click(moveNextOnClick);

	buildNavigator();

	var carouselNavigatorDots = $(".carousel__navigator__dot");

	carouselNavigatorDots.click(moveFromDotOnClick);

	moveNext();

	// Démarrage d'une boucle sur les images.
	var imgLoopInterval = 10000;
	var intervalId = setInterval(moveNext, imgLoopInterval);

	function movePreviousOnClick()
	{
		// Arrêt de la boucle.
		clearInterval(intervalId);

		movePrevious();

		intervalId = setInterval(moveNext, imgLoopInterval);
	}

	function movePrevious()
	{
		var index = currentCarouselItemIndex;

		if (index <= 0)
		{
			index = $(carouselItems).length - 1;
		}
		else
		{
			index--;
		}

		moveToIndex(index);
	}

	function moveNextOnClick()
	{
		// Arrêt de la boucle.
		clearInterval(intervalId);

		moveNext();

		intervalId = setInterval(moveNext, imgLoopInterval);
	}

	function moveNext()
	{
		var index = currentCarouselItemIndex;

		if (index >= $(carouselItems).length - 1)
		{
			index = 0;
		}
		else
		{
			index++;
		}

		moveToIndex(index);
	}

	function setCanChangeTrue()
	{
		canChange = true;
	}

	function buildNavigator()
	{
		$(carousel).append("<section class=\"carousel__navigator\">");

		var carouselNavigator = $(".carousel__navigator");

		var content = "<ul><li>";

		for (var i = 0; i < $(carouselItems).length; i++)
		{
			content += "<span class=\"carousel__navigator__dot\" data-index=\"" + i + "\"></span>";
		}

		content += "</li></ul>";

		$(carouselNavigator).append(content);
	}

	function moveFromDotOnClick()
	{
		// Arrêt de la boucle.
		clearInterval(intervalId);

		moveFromDot($(this));
		
		intervalId = setInterval(moveNext, imgLoopInterval);
	}

	function moveFromDot(dot)
	{
		var index = $(dot).attr("data-index");

		moveToIndex(index);
	}

	function moveToIndex(index)
	{
		if (index < 0 || index >= $(carouselItems).length)
		{
			throw "Mauvaise valeur d'index -> " + index;
		}

		if (index == currentCarouselItemIndex)
		{
			return;
		}

		if (canChange)
		{
			canChange = false;

			var currentCarouselItemInfo = carouselItemsInfos[currentCarouselItemIndex];
			$(currentCarouselItemInfo).fadeOut(imgAnimationDelay);
	
			var currentCarouselItem = carouselItems[currentCarouselItemIndex];
			$(currentCarouselItem).delay(imgAnimationDelay).fadeOut(imgAnimationDelay);
	
			var currentCarouselNavigatorDot = carouselNavigatorDots[currentCarouselItemIndex];
			$(currentCarouselNavigatorDot).removeClass("carousel__navigator__dot--current");

			currentCarouselItemIndex = index;
			
			currentCarouselItem = carouselItems[currentCarouselItemIndex];
			$(currentCarouselItem).delay(2 * imgAnimationDelay).fadeIn(imgAnimationDelay);
	
			currentCarouselItemInfo = carouselItemsInfos[currentCarouselItemIndex];
			$(currentCarouselItemInfo).delay(3 * imgAnimationDelay).fadeIn(imgAnimationDelay, setCanChangeTrue);

			var currentCarouselNavigatorDot = carouselNavigatorDots[currentCarouselItemIndex];
			$(currentCarouselNavigatorDot).addClass("carousel__navigator__dot--current");
		}
	}
});