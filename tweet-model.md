# Tweet Model

The tweet model is responsible for storing the tweet ID and exposing the tweet’s data through model functions.

The `Twitter_TweetModel` model has the following attributes and methods:

## Attributes

### remoteId
The ID of the tweet.

### data
The tweet’s API response data.

## Methods

### getRemoteId()
Returns the tweet’s ID.

### getText()
Returns the tweet's text.

### getUserId()
Returns the tweet’s author user ID.

### getUserName()
Returns the tweet's author user name.

### getUserProfileUrl()
Returns the tweet's author user profile URL.

### getUserProfileRemoteImageUrl()
Returns the tweet's author user profile remote image URL.

### getUserProfileRemoteImageSecureUrl()
Returns the tweet's author user profile remote image secure URL.

### getUserScreenName()
Returns the tweet's author user screen name.

### getCreatedAt()
Returns the creation date of the tweet.

### getUserProfileImageUrl($size = null)
Returns the tweet's author user profile image resource url.

### getUrl()
Returns the URL of the tweet.

