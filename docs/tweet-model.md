# Tweet Model

The `Tweet` model is responsible for storing the tweet ID and exposing the tweet’s data through model functions.

It has the following properties and methods:

## Properties
- **`createdAt`** – The date the tweet was created at. 
- **`data`** – Raw tweet data. 
- **`text`** – The tweet’s text. 
- **`remoteId`** – The tweet’s ID. 
- **`remoteUserId`** – The tweet’s author user ID. 
- **`username`** – The author user’s username. 
- **`userProfileRemoteImageSecureUrl`** – The author user’s profile remote image secure URL. 
- **`userProfileRemoteImageUrl`** – The author user’s profile remote image URL. 
- **`userScreenName`** – The author user’s screen name. 

## Methods

### `getUrl()`
Returns the URL of the tweet.

### `getUserProfileImageUrl(size)`
Returns the tweet's author user profile image resource URL.

### `getUserProfileUrl()`
Returns the tweet's author user profile URL.
