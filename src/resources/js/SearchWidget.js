(function($) {


Craft.Twitter_SearchWidget = Garnish.Base.extend(
{
    init: function(widgetId)
    {
        this.$widget = $('#widget'+widgetId);
        this.$tweets = $('.tweet', this.$widget);

        this.addListener(this.$tweets, 'click', 'handleTweetClick');
    },

    handleTweetClick: function(ev)
    {
    	var $tweet = $(ev.currentTarget),
    		$target = $(ev.target),
    		tweetUrl = $tweet.data('tweet-url');

    	if($target.prop('tagName') != 'A' && $target.parent('a').length == 0)
    	{
	    	if (Garnish.isCtrlKeyPressed(ev))
	    	{
	    		window.open( tweetUrl );
	    	}
	    	else
	    	{
	    		window.location.href = tweetUrl;
	    	}
    	}
    }
});


})(jQuery);
