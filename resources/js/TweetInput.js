/**
 * Tweet field input class
 */
TweetInput = Garnish.Base.extend({

	$input: null,
	$spinner: null,
	$preview: null,

	lookupTweetTimeout: null,

	init: function(inputId)
	{
		this.$input = $('#'+inputId);
		this.$spinner = this.$input.next();
		this.$preview = this.$spinner.next();

		this.addListener(this.$input, 'textchange', 'lookupTweet');
	},

	lookupTweet: function()
	{
		var val = this.$input.val();

		var tweetId = false;
		var idMatch = val.match(/^(\d+)$/);

		if(idMatch)
		{
			tweetId = idMatch[1];
		}

		if (!idMatch)
		{
			idMatch = val.match(/\/status(es)?\/(\d+)\/?$/);

			if(idMatch)
			{
				tweetId = idMatch[2];
			}
		}

		if (tweetId)
		{
			this.$spinner.removeClass('hidden');

			Craft.postActionRequest('twitter/lookupTweet', { id: tweetId }, $.proxy(function(response, textStatus)
			{
				this.$spinner.addClass('hidden');

				if (textStatus == 'success')
				{
					if (response)
					{
						if (!this.$preview.length)
						{
							this.$preview = $('<div class="tweet"/>').insertAfter(this.$spinner);
						}
						else
						{
							this.$preview.show();
						}

						var tweetUrl = 'http://twitter.com/'+response.user.screen_name+'/status/'+response.id_str;
						var userProfileUrl = 'http://twitter.com/'+response.user.screen_name;

						var profileImageUrl = response.user.profile_image_url_https.replace("_normal.", "_bigger.");

						this.$preview.html(
							'<div class="tweet-image"> ' +
								'<a href="'+userProfileUrl+'"><img src="'+profileImageUrl+'"></a>'+
							'</div> ' +
							'<div class="tweet-user">' +
								'<a class="tweet-user-name" href="'+userProfileUrl+'"><strong>'+response.user.name+'</strong></a> ' +
								'<a class="tweet-user-screenname light" href="'+userProfileUrl+'">@'+response.user.screen_name+'</a>' +
							'</div>' +
							'<div class="tweet-text">' +
								response.text +
				                '<ul class="tweet-actions light">' +
				                    '<li><a href="'+ tweetUrl +'">Permalink</a></li>' +
				                '</ul>' +
							'</div>'
						);
					}
					else
					{
						this.$preview.hide();
					}
				}
			}, this));
		}
		else
		{
			this.$preview.hide();
		}
	}
})