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

    private $api_limits = [];
    private const HEADER_API_ALLOWANCE = 'X-RateLimit-Limit';
    private const HEADER_API_USAGE = 'X-RateLimit-Usage';


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
    public function authenticate($scope='read_all,profile:read_all,activity:read_all')
    {
        return redirect('https://www.strava.com/oauth/authorize?client_id='. $this->client_id .'&response_type=code&redirect_uri='. $this->redirect_uri . '&scope=' . $scope . '&state=strava');
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
    public function activities($token, $page = 1, $perPage = 10, $before = null, $after = null)
    {
        $url = $this->strava_uri . '/athlete/activities?page=' . $page . '&per_page=' . $perPage;

        if ($after !== null) {
            $url .= '&after=' . $after;
        }

        if ($before !== null) {
            $url .= '&before=' . $before;
        }

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
    # Strava Create Activity
    #
    public function createActivity($token, $data = [])
    {
        $url = $this->strava_uri . '/activities';
        $config = $this->bearer($token);
        $config = array_merge($config, [
            'form_params' => $data
        ]);
        $res = $this->post($url, $config);
        return $res;
    }


    #
    # Update Strava Single Activity
    #
    public function updateActivityById($token, $activityID, array $updateableActivity)
    {
        $url = $this->strava_uri . '/activities/'. $activityID;
        $config = array_merge($this->bearer($token), ['form_params' => $updateableActivity]);
        $res = $this->put($url, $config, $updateableActivity);
        return $res;
    }


    #
    # Strava Single Activity Stream
    #
    public function activityStream($token, $activityID, $keys = '', $keyByType = true)
    {
        if ($keys != '')
            $keys = join(",", $keys);

        $url = $this->strava_uri . '/activities/'. $activityID .'/streams?keys='. $keys .'&key_by_type'. $keyByType;
        $config = $this->bearer($token);
        $res = $this->get($url, $config);
        return $res;
    }


    #
    # Strava Single Activity Comments
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
    # Strava Activity Segment Stream
    #
    public function segmentStream($token, $segmentID, $keys = '', $keyByType = true)
    {
        if ($keys != '')
            $keys = join(",", $keys);

        $url = $this->strava_uri . '/segments/'. $segmentID .'/streams?keys='. $keys .'&key_by_type'. $keyByType;
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
    # Strava Segments explore
    #
    public function exploreSegments($token, $bounds, $activity_type)
    {
        $url = $this->strava_uri . '/segments/explore?bounds='. $bounds .'&activity_type=' . $activity_type;
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
        $this->parseApiLimits($res->getHeader(self::HEADER_API_ALLOWANCE), $res->getHeader(self::HEADER_API_USAGE));
        $result = json_decode($res->getBody()->getContents());
        return $result;
    }


    #
    # Strava PUT
    #
    public function put($url, $config)
    {
        $res = $this->client->put($url, $config);
        $this->parseApiLimits($res->getHeader(self::HEADER_API_ALLOWANCE), $res->getHeader(self::HEADER_API_USAGE));
        $result = json_decode($res->getBody()->getContents());
        return $result;
    }


    #
    # Strava GET
    #
    public function get($url, $config)
    {
        $res = $this->client->get( $url, $config );
        $this->parseApiLimits($res->getHeader(self::HEADER_API_ALLOWANCE), $res->getHeader(self::HEADER_API_USAGE));
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


    #
    # Return API limits
    #
    public function getApiLimits()
    {
        return $this->api_limits;
    }

    public function getApiAllowanceLimits()
    {
        return $this->api_limits['allowance'];
    }

    public function getApiUsageLimits()
    {
        return $this->api_limits['usage'];
    }

    private function parseApiLimits($allowance, $usage)
    {
        if (isset($allowance[0])) {
            $allowance = explode(',', $allowance[0]);

            $this->api_limits['allowance']['15minutes'] = isset($allowance[0]) ? trim($allowance[0]) : null;
            $this->api_limits['allowance']['daily'] = isset($allowance[1]) ? trim($allowance[1]) : null;
        } else {
            $this->api_limits['allowance']['15minutes'] = null;
            $this->api_limits['allowance']['daily'] = null;
        }

        if (isset($usage[0])) {
            $usage = explode(',', $usage[0]);
            $this->api_limits['usage']['15minutes'] = isset($usage[0]) ? trim($usage[0]) : null;
            $this->api_limits['usage']['daily'] = isset($usage[1]) ? trim($usage[1]) : null;
        } else {
            $this->api_limits['usage']['15minutes'] = null;
            $this->api_limits['usage']['daily'] = null;
        }
    }
}
