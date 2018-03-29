# Google Calendar integration plugin

This plugin adds Google Calendar features to the [OctoberCMS](http://octobercms.com).

# Abandoned

This is abandoned. You should fork it and make it your own, if you like.

## Configuring

Google Calendar API uses the OAuth security. In order to use the plugin you need create a Google API application.

1. Go to the [Google API Console](https://cloud.google.com/console/project) and create a new project.
1. On the project page go to the **APIs & auth / APIs** section and enable Calendar API.
1. Go to the **APIs & auth / Credentials** section on the project page and click the Create New Client ID button. In the popup window select the **Service account** option. The private key will be downloaded to your computer automatically.
1. Copy the email address (xxxx@developer.gserviceaccount.com) from the OAuth / **Service Account** section and add it to your *each Calendar* you want to be able to access from OctoberCMS. Selecting Read-only access is fine.
1. In the OctoberCMS back-end go to the System / Settings page and click the Google Calendar link.
1. Enter the Google API Project name, Google API Client ID, the generated email address and add the downloaded private key to the Google Calendar settings form. The API Client ID and the generated email address should be copied from the **Service Account section**.
1. Enter how far back you wish for syncing to go, and how far forward (in years). 
1. If you want to delete old events after they are no longer being synced, please make sure that "Delete Old Events" is switched on.
1. Test everything works by running:
       - php artisan kilfeddercalendar:updatecalendars
       - php artisan kilfeddercalendar:updateevents

## One more thing 

It is very important to note that a lot of the OAuth stuff has been blatantly and unashamedly pinched from the rainlab.googleanalytics plugin. Also the documentation above was largely pinched from their work.
So far as I'm concerned you can take what you like from this plugin, but you will want to attribute the oAuth stuff to rainlab.
