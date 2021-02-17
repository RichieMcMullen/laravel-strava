
<div style="display: inline: block">
  <img alt="GitHub release" src="https://img.shields.io/github/release/richiemcmullen/laravel-strava.svg?color=%23FF4A00">
  <img alt="GitHub watchers" src="https://img.shields.io/github/watchers/richiemcmullen/laravel-strava.svg">
  <img alt="GitHub stars" src="https://img.shields.io/github/stars/richiemcmullen/laravel-strava.svg">
  <img alt="GitHub forks" src="https://img.shields.io/github/forks/richiemcmullen/laravel-strava.svg">
  <img alt="GitHub code size in bytes" src="https://img.shields.io/github/languages/code-size/richiemcmullen/laravel-strava.svg">
  <img alt="GitHub issues" src="https://img.shields.io/github/issues-raw/richiemcmullen/laravel-strava.svg">
  <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/richiemcmullen/laravel-strava.svg">
  <img alt="Maintenance" src="https://img.shields.io/maintenance/yes/2020.svg">
</div>

# Laravel Strava Package

A laravel package to access data from the Strava API. Compatible with ```Laravel 5.0``` and above.

## Table of Contents

- [Strava Access Credentials](https://github.com/RichieMcMullen/strava#strava-access-credentials)
- [Installation](https://github.com/RichieMcMullen/strava#installation)
- [Publish Strava Config File](https://github.com/RichieMcMullen/strava#publish-strava-config-file)
- [Auto Discovery](https://github.com/RichieMcMullen/strava#auto-discovery)
  - [Provider](https://github.com/RichieMcMullen/strava#provider)
  - [Facade](https://github.com/RichieMcMullen/strava#alias--facade)
- [Usage](https://github.com/RichieMcMullen/strava#usage)
  - [Initialise Facade](https://github.com/RichieMcMullen/strava#use-strava-facade)
  - [Authenticate User](https://github.com/RichieMcMullen/strava#authenticate-user)
  - [Get Access Token](https://github.com/RichieMcMullen/strava#get-access-token)
  - [Access Token Expiry](https://github.com/RichieMcMullen/strava#access-token-expiry)
  - [Unauthenticate User](https://github.com/RichieMcMullen/strava#unauthenticate-user)
- [Available Methods](https://github.com/RichieMcMullen/strava#usage)
  - [Athlete Data](https://github.com/RichieMcMullen/strava#athelete-data)
  - [User Activities Data](https://github.com/RichieMcMullen/strava#user-activities-data)
  - [User Single Activity](https://github.com/RichieMcMullen/strava#user-single-activity)
  - [User Single Activity Stream](https://github.com/RichieMcMullen/strava#user-single-activity-stream)
  - [Activity Comments](https://github.com/RichieMcMullen/strava#activity-comments)
  - [Activity Kudos](https://github.com/RichieMcMullen/strava#activity-kudos)
  - [Activity Laps](https://github.com/RichieMcMullen/strava#activity-laps)
  - [Activity Zones](https://github.com/RichieMcMullen/strava#activity-zones)
  - [Athlete Zones](https://github.com/RichieMcMullen/strava#athlete-zones)
  - [Athlete Stats](https://github.com/RichieMcMullen/strava#athlete-stats)
  - [Club](https://github.com/RichieMcMullen/strava#club)
  - [Club Members](https://github.com/RichieMcMullen/strava#club-members)
  - [Club Activities](https://github.com/RichieMcMullen/strava#club-activities)
  - [Club Admins](https://github.com/RichieMcMullen/strava#club-admins)
  - [Athlete Clubs](https://github.com/RichieMcMullen/strava#athlete-clubs)
  - [Gear](https://github.com/RichieMcMullen/strava#gear)
  - [Route](https://github.com/RichieMcMullen/strava#route)
  - [Athlete Routes](https://github.com/RichieMcMullen/strava#athlete-routes)
  - [Segment](https://github.com/RichieMcMullen/strava#segment)
  - [Segment Effort](https://github.com/RichieMcMullen/strava#segment-effort)
  - [Starred Segments](https://github.com/RichieMcMullen/strava#starred-segments)
- [Parameter Types](https://github.com/RichieMcMullen/strava#parameter-types)
- [Caching](https://github.com/RichieMcMullen/laravel-strava#caching)
- [Useful Links](https://github.com/RichieMcMullen/laravel-strava#useful-links)

## Strava Access Credentials

In order to use this package you will need to create an app from within your strava account. [Create Strava App](https://www.strava.com/settings/api) to access your API credentials. Click here for more information on the [Strava API](https://developers.strava.com/).


## Installation

To install the package within your laravel project use the following composer command:

```shell
composer require codetoad/strava
```


## Publish Strava Config File

The `vendor:publish` commmand will publish a file named `ct_strava.php` within your laravel project config folder `config/ct_strava.php`. Edit this file with your Strava API credentials, generated from the Strava app you created.

```shell
php artisan vendor:publish --provider="CodeToad\Strava\StravaServiceProvider"
```

Published Config File Contents

```php
'client_id' => env('CT_STRAVA_CLIENT_ID', '')
'client_secret' => env('CT_STRAVA_SECRET_ID', '')
'redirect_uri' => env('CT_STRAVA_REDIRECT_URI', '')
```

Alternatively you can ignore the above publish command and add this following variables to your `.env` file. Make sure to add your Strava App credentials

```text
CT_STRAVA_CLIENT_ID=ADD-STRAVA-CLIENT-ID-HERE
CT_STRAVA_SECRET_ID=ADD-STRAVA-SECRET-HERE
CT_STRAVA_REDIRECT_URI=ADD-STRAVA-REDIRECT-URI-HERE
```


## Auto Discovery

If you're using Laravel 5.5+ you don't need to manually add the service provider or facade. This will be Auto-Discovered. For all versions of Laravel below 5.5, you must manually add the ServiceProvider & Facade to the appropriate arrays within your Laravel project `config/app.php`


#### Provider

```php
CodeToad\Strava\StravaServiceProvider::class,
```

#### Alias / Facade

```php
'Strava' => CodeToad\Strava\StravaFacade::class,
```


## Usage

#### Use Strava Facade

Add the Strava facade to the top of your controller so you can access the Strava class methods.

```php
use Strava;

class MyController extends Controller
{
  // Controller functions here...
}
```

#### Authenticate User

Call the `Strava::authenticate()` method to redirect you to Strava. If authentication is successful the user will be redirected to the `redirect_uri` that you added to the `config` file or your `.env` file. You may now also pass ```$scope``` as a parameter when authenticating. You can add or remove scopes as required. Some are required, some are optional. Details on available scopes can be seen here [Strava Authentication Scopes](https://developers.strava.com/docs/authentication/)

```php
public function stravaAuth()
{
  return Strava::authenticate($scope='read_all,profile:read_all,activity:read_all');
}
```

#### Obtain User Access Token

When returned to the redirected uri, call the `Strava::token($code)` method to generate the users Strava access token & refresh token. The tokens are generated using the `code` parameter value within the redirected uri. Be sure to store the users `access_token` & `refresh_token` in your database.

```php
public function getToken(Request $request)
{
  $token = Strava::token($request->code);

  // Store $token->access_token & $token->refresh_token in database
}
```

Example Response

```php
"token_type": "Bearer"
"expires_at": 1555165838
"expires_in": 21600 // 6 Hours
"refresh_token": "671129e56b1ce64d7e0c7e594cb6522b239464e1"
"access_token": "e105062b153da39f81a439b90b23357c741a40a0"
"athlete": ...
```

At this point you have access to the `Athlete` object that can be used to login to your website. Of course you'll need to write the logic for your login system, but the athlete name, email etc.. is contained within the object for you to verify the user against your own database data.

#### Access Token Expiry

Access tokens will now expire after 6 hours under the new flow that Strava have implemented and will need to be updated using a refresh token. In the example above you can see the response has a `refresh_token` and an `expires_at` field. When storing the user access tokens you may also want to store the `expires_at` field too. This will allow you to check when the current access token has expired.

When calling any of the Strava methods below you may want to compare the current time against the `expires_at` field in order to validate the token. If the token is expired you'll need to call the `Strava::refreshToken($refreshToken)` method in order to generate a new tokens. All you need to do is pass the users currently stored `refresh_token`, the method will then return a new set of tokens (access & refresh), update the current users tokens with the new tokens from the response. Heres an example of how that might work, using the `Strava::athlete($token)` method.

```php
use Carbon\Carbon;

public function myControllerFunction(Request $request)
{
  // Get the user
  $user = User::find($request->id);

  // Check if current token has expired
  if(strtotime(Carbon::now()) > $user->expires_at)
  {
    // Token has expired, generate new tokens using the currently stored user refresh token
    $refresh = Strava::refreshToken($user->refresh_token);

    // Update the users tokens
    User::where('id', $request->id)->update([
      'access_token' => $refresh->access_token,
      'refresh_token' => $refresh->refresh_token
    ]);

    // Call Strava Athlete Method with newly updated access token.
    $athlete = Strava::athlete($user->access_token);

    // Return $athlete array to view
    return view('strava.athlete')->with(compact('athlete'));

  }else{

    // Token has not yet expired, Call Strava Athlete Method
    $athlete = Strava::athlete($user->access_token);

    // Return $athlete array to view
    return view('strava.athlete')->with(compact('athlete'));

  }

}
```

#### Unauthenticate User

You can allow users to unauthenticate their Strava account with your Strava app. Simply allow users to call the following method, passing the access token that has been stored for their account.

```php
Strava::unauthenticate($token);
```

## Available Methods

All methods require an access token, some methods accept additional optional parameters listed below.

- Optional Parameters
  - $page (Int - default 1)
  - $perpage (Int - default 10)
  - $before (Int/Timestamp - default = most recent)
  - $after (Int/Timestamp - default = most recent)

#### Athlete Data

Returns the currently authenticated athlete.

```php
Strava::athlete($token);
```

#### User Activities Data

Returns the activities of an athlete.

```php
Strava::activities($token, $page, $perPage, $before, $after);
```

#### User Single Activity

Returns the given activity that is owned by the authenticated athlete.

```php
Strava::activity($token, $activityID);
```

#### User Single Activity Stream

Returns the given activity's streams.

```php
// $keys is a string array containing required streams
// e.g. ['latlng', 'time']
Strava::activityStream($token, $activityID, $keys = '', $keyByType = true);
```

#### Activity Comments

Returns the comments on the given activity.

```php
Strava::activityComments($token, $activityID, $page, $perPage);
```

#### Activity Kudos

Returns the athletes who kudoed an activity.

```php
Strava::activityKudos($token, $activityID, $page, $perPage);
```

#### Activity Laps

Returns the laps data of an activity.

```php
Strava::activityLaps($token, $activityID);
```

#### Activity Zones

Summit Feature Required. Returns the zones of a given activity.

```php
Strava::activityZones($token, $activityID);
```

#### Athlete Zones

Returns the the authenticated athlete's heart rate and power zones.

```php
Strava::athleteZones($token);
```

#### Athlete Stats

Returns the activity stats of an athlete.

```php
Strava::athleteStats($token, $athleteID, $page, $perPage);
```

#### Club

Returns a given club using its identifier.

```php
Strava::club($token, $clubID);
```

#### Club Members

Returns a list of the athletes who are members of a given club.

```php
Strava::clubMembers($token, $clubID, $page, $perPage);
```

#### Club Activities

Retrieve recent activities from members of a specific club. The authenticated athlete must belong to the requested club in order to hit this endpoint. Pagination is supported. Athlete profile visibility is respected for all activities.

```php
Strava::clubActivities($token, $clubID, $page, $perPage);
```

#### Club Admins

Returns a list of the administrators of a given club.

```php
Strava::clubAdmins($token, $clubID, $page, $perPage);
```

#### Athlete Clubs

Returns a list of the clubs whose membership includes the authenticated athlete.

```php
Strava::athleteClubs($token, $page, $perPage);
```

#### Gear

Returns equipment data using gear ID.

```php
Strava::gear($token, $gearID);
```

#### Route

Returns a route using its route ID.

```php
Strava::route($token, $routeID);
```

#### Athlete Routes

Returns a list of the routes created by the authenticated athlete using their athlete ID.

```php
Strava::athleteRoutes($token, $athleteID, $page, $perPage);
```

#### Segment

Returns the specified segment.

```php
Strava::segment($token, $segmentID);
```

#### Segment Effort

Returns a segment effort from an activity that is owned by the authenticated athlete.

```php
Strava::segmentEffort($token, $segmentID);
```

#### Starred Segments

List of the authenticated athlete's starred segments.

```php
Strava::starredSegments($token, $page, $perPage);
```

## Getting API Limits 
  
Strava returns information about API calls allowance and usage in response headers.

The methods listed below will return  this information upon a call which uses up the API limit (like fetching activities). Some calls like refreshing access tokens seem not to use up the API call limit, that's why you will get nulls in the resulting array.

As well when you try to get the limits at the very beginning, before any API call using up the limits , you will receive nulls. The default allowance limits are not hardcoded as different accounts may have different limits.
  
## Getting API Limits 
  
Strava returns information about API calls allowance and usage in response headers.

The methods listed below will return  this information upon a call which uses up the API limit (like fetching activities). Some calls like refreshing access tokens seem not to use up the API call limit, that's why you will get nulls in the resulting array.

As well when you try to get the limits at the very beginning, before any API call using up the limits , you will receive nulls. The default allowance limits are not hardcoded as different accounts may have different limits.
  
#### All API Limits 
Returns all limits in a multidimensional array, eg.:

```php  
[  
	['allowance']['15minutes'] => "100",  
	['allowance']['daily'] => "1000",  
	['usage']['15minutes'] => "7",  
	['usage']['daily'] => "352",  
]
```  
  
```php  
Strava::getApiLimits();
```  
#### Allocated API Limits 
Returns daily and 15-minute request limits available for the Strava account , eg.:

```php  
[  
	['15minutes'] => '100',  
	['daily'] => '1000',  
]
```  
  
```php  
Strava::getApiAllowanceLimits();
```  

#### Used API Calls
Returns number of daily and 15-minute calls used up at the Strava account , eg.:

```php  
[  
	['15minutes'] => '7',  
	['daily'] => '352',  
]
```  
  
```php  
Strava::getApiUsageLimits();
```  


## Parameter Types

```php
$token        = string
$activityID   = integer
$athleteID    = integer
$clubID       = integer
$gearID       = integer
$routeID      = integer
$segmentID    = integer
$page         = integer
$perPage      = integer
$before       = integer (timestamp)
$after        = integer (timestamp)
```

## Caching

It's highly recommended that you cache your requests made to Strava for 2 reasons.

#### (1) Rate Limiting

Strava have API Rate Limit of 100 requests every 15 minutes and 10,000 daily. If your website has high traffic you might want to consider caching your Strava response data so you don't exceed these limits.

#### (2) Website Loading Speed

Caching your Strava data will drastically improve website load times.

## Useful Links

- [Laravel Caching Documentation](https://laravel.com/docs/5.8/cache)
- [Strava Developers](https://developers.strava.com/)
- [Strava App Creation](https://www.strava.com/settings/api)
