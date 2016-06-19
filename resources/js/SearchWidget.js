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
    	var $tweet = $(ev.currentTarget);
    	var tweetUrl = $tweet.data('tweet-url');

    	if(ev.target.tagName != 'A')
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
