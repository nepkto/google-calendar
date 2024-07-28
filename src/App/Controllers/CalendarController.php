<?php

namespace App\Controllers;

use Exception;
use App\Validation\Validator;
use App\Services\FlashMessage;
use App\Services\GoogleCalendarService;

class CalendarController
{
    private $calendarService;

    /**
     * Constructor
     */

    public function __construct()
    {

        if (empty($_SESSION['access_token'])) {
            header("Location: /");
            exit();
        }
        $this->calendarService = new GoogleCalendarService();
    }

    /**
     *  Calender View
     *  @return Void Loads the view and terminates the script.
     */
    public function index()
    {

        require __DIR__ . '/../../../Views/calendar/index.php';
        exit();
    }

    /**
     * List of events
     * @return void Outputs a JSON response and terminates the script.
     */

    public function eventLists()
    {
        try {
            $events = $this->calendarService->listEvents();
            $eventsList = [];

            foreach ($events->getItems() as $event) {
                $eventsList[] = [
                    'id' => $event->getId(),
                    'summary' => $event->getSummary(),
                    'start' => $event->getStart()->getDateTime(),
                    'end' => $event->getEnd()->getDateTime()
                ];
            }


            echo json_encode($eventsList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Google_Service_Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    /**
     *  Calender Event Create
     *  @return Void Loads the create view and terminates the script.
     */

    public function create()
    {
        require __DIR__ . '/../../../Views/calendar/create.php';
        exit();
    }

    /**
     *  Calender  Even store
     *  @return Void Loads the create view and terminates the script.
     */
    public function store()
    {
        $validator = new Validator();
        $isValid = $validator->validateCalendarEvent($_POST);

        if (!$isValid) {
            FlashMessage::set('Validdation Error', 'danger', $validator->getErrors());
            header('Location: /event/create');
            exit();
        }

        try {
            $this->calendarService->createEvent(
                $_POST['summary'],
                $_POST['description'],
                $_POST['start_datetime'] . ':00',
                $_POST['end_datetime'] . ':00'
            );

            FlashMessage::set('Data stored successfully!', 'success');
            header('Location: /event');
        } catch (Exception $e) {
            FlashMessage::set('Failed to store data!', 'danger');
            header('Location: /event/create');
        }
    }


    /**
     * Event Delete
     * @param id
     * @return void Outputs a JSON response and terminates the script.
     */
    public function delete($id)
    {
        try {
            $this->calendarService->deleteEvent($id);
            http_response_code(200);
            echo json_encode(['success' => 'Successfully Deleted']);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(['error' => $ex->getMessage()]);
        }
    }
}
