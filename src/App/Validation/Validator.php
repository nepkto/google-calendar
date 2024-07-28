<?php

namespace App\Validation;

class Validator
{
    private $errors = [];

    /**
     * Validate Calendar Event
     *
     * Validates the provided calendar event data.
     *
     * @param array $event An associative array containing event data.
     * @return bool Returns true if the event is valid, false otherwise.
     */
    public function validateCalendarEvent($event)
    {
        $this->errors = []; // Reset errors

        $this->validateRequiredFields($event);
        $this->validateDateTimeFormat($event, 'start_datetime');
        $this->validateDateTimeFormat($event, 'end_datetime');
        $this->validateStartBeforeEnd($event);

        return empty($this->errors);
    }

    /**
     * Get Validation Errors
     *
     * @return array An array of validation error messages.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Validate Required Fields
     *
     * @param array $event An associative array containing event data.
     */
    private function validateRequiredFields($event)
    {
        if (empty($event['summary'])) {
            $this->errors[] = "The event summary is required.";
        }

        if (empty($event['description'])) {
            $this->errors[] = "The event description is required.";
        }

        if (empty($event['start_datetime'])) {
            $this->errors[] = "The event start time is required.";
        }

        if (empty($event['end_datetime'])) {
            $this->errors[] = "The event end time is required.";
        }
    }

    /**
     * Validate Date-Time Format
     *
     * @param array $event An associative array containing event data.
     * @param string $field The field name to validate ('start' or 'end').
     * @param string $format The expected date-time format. Default is 'Y-m-d\TH:i:s'.
     */
    private function validateDateTimeFormat($event, $field, $format = 'Y-m-d\TH:i')
    {
        if (!empty($event[$field]) && !$this->isValidDateTime($event[$field], $format)) {
            $this->errors[] = "The event $field time is not a valid date-time format.";
        }
    }

    /**
     * Validate Start Before End
     *
     * @param array $event An associative array containing event data.
     */
    private function validateStartBeforeEnd($event)
    {
        if (!empty($event['start_datetime']) && !empty($event['end_datetime']) && strtotime($event['start_datetime']) >= strtotime($event['end_datetime'])) {
            $this->errors[] = "The event start time must be before the end time.";
        }
    }

    /**
     * Check if Date-Time is Valid
     *
     * @param string $dateTime The date-time string to validate.
     * @param string $format The expected date-time format.
     * @return bool Returns true if the date-time format is valid, false otherwise.
     */
    private function isValidDateTime($dateTime, $format)
    {
        $d = \DateTime::createFromFormat($format, $dateTime);
        return $d && $d->format($format) === $dateTime;
    }
}
