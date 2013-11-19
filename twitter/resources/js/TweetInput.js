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

		var idMatch = val.match(/^(\d+)$/);

		if (!idMatch)
		{
			idMatch = val.match(/\/status\/(\d+)\/?$/);
		}

		if (idMatch)
		{
			this.$spinner.removeClass('hidden');

			Craft.postActionRequest('twitter/lookupTweet', { id: idMatch[1] }, $.proxy(function(response, textStatus)
			{
				this.$spinner.addClass('hidden');

				if (textStatus == 'success')
				{
					if (response)
					{
						if (!this.$preview.length)
						{
							this.$preview = $('<div class="tweet-preview"/>').insertAfter(this.$spinner);
						}
						else
						{
							this.$preview.show();
						}

						if (document.location.protocol == 'https:')
						{
							var profileImageUrl = response.user.profile_image_url_https;
						}
						else
						{
							var profileImageUrl = response.user.profile_image_url;
						}

						this.$preview.html(
							'<div class="tweet-image" style="background-image: url('+profileImageUrl+');" /> ' +
							'<div class="tweet-user">' +
								'<span class="tweet-user-name">'+response.user.name+'</span> ' +
								'<a class="tweet-user-screenname" href="http://twitter.com/'+response.user.screen_name+'" target="_blank">@'+response.user.screen_name+'</a>' +
							'</div>' +
							'<div class="tweet-text">' +
								response.text +
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