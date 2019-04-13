<?php

# CodeToad
# Richie McMullen
# 2019

namespace CodeToad\Strava;

class Strava
{

    private $strava_uri = 'https://www.strava.com/api/v3';
    private $client;
    private $client_id;
    private $client_secret;
    private $redirect_uri;


    #
    # Constructor
    #
    public function __construct($CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI, $GUZZLE_CLIENT)
    {
        $this->client = $GUZZLE_CLIENT; # Guzzle Client
        $this->client_id = $CLIENT_ID; # Strava Client ID
        $this->client_secret = $CLIENT_SECRET; # Strava Secrect
        $this->redirect_uri = $REDIRECT_URI; # Strava Redirect URi
    }


    #
    # Strava Authenticate
    #
    public function authenticate()
    {
      return redirect('https://www.strava.com/oauth/authorize?client_id='. $this->client_id .'&response_type=code&redirect_uri='. $this->redirect_uri . '&scope=read_all,profile:read_all,activity:read_all&state=strava');
    }


    #
    # Strava Unauthenticate
    #
    public function unauthenticate($token)
    {
      $url = 'https://www.strava.com/oauth/deauthorize';
      $config = [
          'form_params' => [
              'access_token' => $token
          ]
      ];
      $res = $this->post($url, $config);
      return $res;
    }


    #
    # Strava Token
    #
    public function token($code)
    {
        $url = 'https://www.strava.com/oauth/token';
        $config = [
          'form_params' => [
              'client_id' => $this->client_id,
              'client_secret' => $this->client_secret,
              'code' => $code,
              'grant_type' => 'authorization_code'
          ]
        ];
        $res = $this->post($url, $config);
        return $res;
    }


    #
    # Strava Refresh Token
    #
    public function refreshToken($refreshToken)
    {
        $url = 'https://www.strava.com/oauth/token';
        $config = [
          'form_params' => [
              'client_id' => $this->client_id,
              'client_secret' => $this->client_secret,
              'refresh_token' => $refreshToken,
              'grant_type' => 'refresh_token'
          ]
        ];
        $res = $this->post($url, $config);
        return $res;
    }


    #
    # Strava Athlete
    #
    public function athlete($token)
    {
        $url = $this->strava_uri . '/athlete';
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava User Activities
    #
    public function activities($token, $perPage = 10)
    {
        $url = $this->strava_uri . '/athlete/activities?per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Single Activity
    #
    public function activity($token, $activityID)
    {
        $url = $this->strava_uri . '/activities/'. $activityID .'?include_all_efforts=true';
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Single Activity
    #
    public function activityComments($token, $activityID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/activities/'. $activityID .'/comments?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Single Activity Kudos
    #
    public function activityKudos($token, $activityID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/activities/'. $activityID .'/kudos?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Single Activity Laps
    #
    public function activityLaps($token, $activityID)
    {
        $url = $this->strava_uri . '/activities/'. $activityID .'/laps';
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Single Activity Zones
    #
    public function activityZones($token, $activityID)
    {
        $url = $this->strava_uri . '/activities/'. $activityID .'/zones';
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Athlete Zones (Heart Rate & Power)
    #
    public function athleteZones($token)
    {
        $url = $this->strava_uri . '/athlete/zones';
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Athlete Stats
    #
    public function athleteStats($token, $athleteID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/athletes/'. $athleteID .'/stats?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Get Club
    #
    public function club($token, $clubID)
    {
        $url = $this->strava_uri . '/clubs/'. $clubID;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Club Members
    #
    public function clubMembers($token, $clubID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/clubs/'. $clubID .'/members?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Club Activities
    #
    public function clubActivities($token, $clubID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/clubs/'. $clubID .'/activities?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Club Admins
    #
    public function clubAdmins($token, $clubID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/clubs/'. $clubID .'/admins?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Athelete Clubs
    #
    public function athleteClubs($token, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/athlete/clubs?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Gear/Bike Data
    #
    public function gear($token, $gearID)
    {
        $url = $this->strava_uri . '/gear/' . $gearID;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Get Route
    #
    public function route($token, $routeID)
    {
        $url = $this->strava_uri . '/routes/' . $routeID;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Get Athlete Routes
    #
    public function athleteRoutes($token, $athleteID, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/athletes/' . $athleteID . '/routes?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Get Segment
    #
    public function segment($token, $segmentID)
    {
        $url = $this->strava_uri . '/segments/' . $segmentID;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Activity Segment Effort
    #
    public function segmentEffort($token, $segmentID)
    {
        $url = $this->strava_uri . '/segment_efforts/' . $segmentID;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava List Starred Segments
    #
    public function starredSegments($token, $page = 1, $perPage = 10)
    {
        $url = $this->strava_uri . '/segments/starred?page='. $page .'&per_page=' . $perPage;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava POST
    #
    public function post($url, $config)
    {
        $res = $this->client->post( $url, $config );
        $result = json_decode($res->getBody()->getContents());
        return $result;
    }


    #
    # Strava GET
    #
    public function get($url, $config)
    {
        $res = $this->client->get( $url, $config );
        $result = json_decode($res->getBody()->getContents());
        return $result;
    }


    #
    # Strava Authorization Bearer
    #
    private function bearer($token)
    {
      $config = [
        'headers' => [
            'Authorization' => 'Bearer '.$token.''
        ],
      ];
      return $config;
    }


}
