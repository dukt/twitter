# Twitter <small>_for Craft CMS_</small>

Connect a Twitter account to your Craft system and perform authenticated request to Twitter API

- [Usage](#usage)
- [Feedback](#feedback)


<a id="usage"></a>
## Usage

Once Twitter plugin is setup, you can fetch data from Twitter API in your templates :

	{% set tweets = craft.twitter.get('statuses/user_timeline') %}

	{% if tweets %}

		<h2>Recent Tweets</h2>

		<ul>
			{% for tweet in tweets %}
				<li>{{tweet.text}}</li>
			{% endfor %}
		</ul>

		<hr />
	{% endif %}


<a id="feedback"></a>
## Feedback

**Please provide feedback!** We want this plugin to make fit your needs as much as possible.
Please [get in touch](mailto:hello@dukt.net), and point out what you do and don't like. **No issue is too small.**

This plugin is actively maintained by [Benjamin David](https://github.com/benjamindavid), from [Dukt](http://dukt.net/).

Dukt © 2013 - All rights reserved