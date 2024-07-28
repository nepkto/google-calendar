<?php
session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$errors = [];

try {
    $client->setAuthConfig('client_secret.json');
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setRedirectUri('http://localhost:8000/callback.php');

    $service = new Google_Service_Calendar($client);

    if (isset($_GET['disconnect'])) {
        unset($_SESSION['access_token']);
        header('Location: index.php');
        exit;
    }

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
    } elseif (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $token;
    } else {
        $authUrl = $client->createAuthUrl();
        echo "<a href='$authUrl'>Connect to Google Calendar</a>";
        exit;
    }

    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        $_SESSION['access_token'] = $client->getAccessToken();
    }

    // List events
    function listEvents($service) {
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (empty($events)) {
            return "No upcoming events found.";
        } else {
            $output = "Upcoming events:\n";
            foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $output .= sprintf("%s (%s)\n", $event->getSummary(), $start);
            }
            return $output;
        }
    }

    // Create event
    function createEvent($service, $summary, $description, $startDateTime, $endDateTime) {
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $summary,
            'description' => $description,
            'start' => array(
                'dateTime' => $startDateTime,
                'timeZone' => 'America/New_York',
            ),
            'end' => array(
                'dateTime' => $endDateTime,
                'timeZone' => 'America/New_York',
            ),
        ));

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        return sprintf('Event created: %s', $event->htmlLink);
    }

    // Delete event
    function deleteEvent($service, $eventId) {
        $calendarId = 'primary';
        $service->events->delete($calendarId, $eventId);
        return "Event deleted.";
    }

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['create_event'])) {
            $result = createEvent($service, $_POST['summary'], $_POST['description'], $_POST['start_datetime'], $_POST['end_datetime']);
            $success_message = $result;
        } elseif (isset($_POST['delete_event'])) {
            $result = deleteEvent($service, $_POST['event_id']);
            $success_message = $result;
        }
    }

} catch (Google_Service_Exception $e) {
    $errors[] = json_decode($e->getMessage())->error->message;
} catch (Google_Exception $e) {
    $errors[] = $e->getMessage();
} catch (Exception $e) {
    $errors[] = "An unexpected error occurred: " . $e->getMessage();
}

// Display UI
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar Integration</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        h1, h2 { color: #333; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="datetime-local"] { width: 100%; padding: 8px; margin-bottom: 10px; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 10px; }
        .error { color: red; background-color: #FEE; padding: 10px; border: 1px solid red; margin-bottom: 20px; }
        .success { color: green; background-color: #EFE; padding: 10px; border: 1px solid green; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Google Calendar Integration</h1>
    
    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul></div>';
    }

    if (isset($success_message)) {
        echo '<div class="success">' . $success_message . '</div>';
    }
    ?>

    <h2>Create Event</h2>
    <form method="post">
        <input type="text" name="summary" placeholder="Event Summary" required>
        <input type="text" name="description" placeholder="Event Description">
        <input type="datetime-local" name="start_datetime" required>
        <input type="datetime-local" name="end_datetime" required>
        <input type="submit" name="create_event" value="Create Event">
    </form>

    <h2>Delete Event</h2>
    <form method="post">
        <input type="text" name="event_id" placeholder="Event ID" required>
        <input type="submit" name="delete_event" value="Delete Event">
    </form>

    <h2>Upcoming Events</h2>
    <pre><?php
    if (isset($service)) {
        echo listEvents($service);
    }
    ?></pre>

    <h2>Disconnect Account</h2>
    <a href="?disconnect=1">Disconnect from Google Calendar</a>
</body>
</html>