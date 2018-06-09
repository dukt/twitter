/**
 * Tweet field input class
 */
TweetInput = Garnish.Base.extend({

    $input: null,
    $spinner: null,
    $preview: null,

    lookupTweetTimeout: null,

    init: function(inputId) {
        this.$input = $('#' + inputId);
        this.$spinner = this.$input.parent().find('.spinner');
        this.$preview = this.$spinner.parent().find('.preview');

        this.addListener(this.$input, 'textchange', 'lookupTweet');

        if (this.$preview.hasClass('hidden') && this.$input.val()) {
             this.lookupTweet();
        }
    },

    lookupTweet: function() {
        this.$spinner.removeClass('hidden');
        this.$preview.addClass('hidden');
        this.$preview.html('');

        var val = this.$input.val();

        var tweetId = false;
        var idMatch = val.match(/^(\d+)$/);

        if (idMatch) {
            tweetId = idMatch[1];
        }

        if (!idMatch) {
            idMatch = val.match(/\/status(es)?\/(\d+)\/?$/);

            if (idMatch) {
                tweetId = idMatch[2];
            }
        }

        if (tweetId) {
            Craft.postActionRequest('twitter/api/tweet-field-preview', {id: tweetId}, $.proxy(function(response, textStatus) {
                if (textStatus == 'success') {
                    if (!response.error) {
                        this.$preview.html(response.html);
                    } else {
                        this.$preview.html('<p class="error">' + response.error + '</p>');
                    }
                } else {
                    this.$preview.html('<p class="error">An unknown error occured.</p>');
                }

                this.$spinner.addClass('hidden');
                this.$preview.removeClass('hidden');
            }, this));
        } else {
            this.$spinner.addClass('hidden');
        }
    }
})