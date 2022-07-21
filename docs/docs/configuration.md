# Configuration
Twitter supports several configuration settings. You can override their values in your `config/twitter.php` file.
    
## cacheDuration
The amount of time cache should last. [See documentation for DateInterval](http://www.php.net/manual/en/dateinterval.construct.php).
     
     'cacheDuration' => 'PT10M'

## enableCache
Whether request to APIs should be cached or not
     
     'enableCache' => true

## oauthConsumerKey    
The OAuth consumer key.
     
     'oauthConsumerKey' => 'xxxxxxxxxx'

## oauthConsumerSecret    
The OAuth consumer secret.
     
     'oauthConsumerSecret' => 'xxxxxxxxxxxxxxxxxxxx'

## searchWidgetExtraQuery    
The search widget’s extra query.
     
     'searchWidgetExtraQuery => '-filter:retweets'
