<?php namespace Kilfedder\GoogleCalendar\Console;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Http_Batch;
use Google_Service_Calendar;
use Google_Service_Calendar_CalendarList;
use Illuminate\Console\Command;
use Kilfedder\GoogleCalendar\Models\Settings;
use October\Rain\Exception\ApplicationException;

abstract class UpdateBase extends Command
{

    protected $settings;
    protected $client;
    protected $service;
    protected $batch;

    function __construct()
    {
        parent::__construct();
        $this->setupClient();

    }

    private function setupClient()
    {
        $this->settings = Settings::instance();
        if (!strlen($this->settings->project_name)) {
            throw new ApplicationException('Google Analytics API access is not configured. Please configure it on the System / Settings / Google Analytics page.');
        }

        if (!$this->settings->gapi_key) {
            throw new ApplicationException('Google Analytics API private key is not uploaded. Please configure Google Analytics access on the System / Settings / Google Analytics page.');
        }
        $this->client = new Google_Client();
        $this->client->setApplicationName($this->settings->project_name);

        $credentials =
            new Google_Auth_AssertionCredentials(
                $this->settings->app_email,
                [Google_Service_Calendar::CALENDAR_READONLY],
                $this->settings->gapi_key->getContents()
            );
        $this->client->setAssertionCredentials($credentials);

        $this->client->setClientId($this->settings->client_id);
        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion($credentials);
        }
        $this->batch = new Google_Http_Batch($this->client);
        $this->service = new Google_Service_Calendar($this->client);
    }
}