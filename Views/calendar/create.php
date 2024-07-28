<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Google Calendar Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php 
            \App\Services\FlashMessage::display(); 
            \App\Services\FlashMessage::displayValidation()
        ?>
        <h2>Create Google Calendar Event</h2>
        <form action="/event" method="post" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="event-summary">Event Summary:</label>
                <input type="text" class="form-control" id="event-summary" name="summary" required>
                <div class="invalid-feedback">
                    Please provide a summary for the event.
                </div>
            </div>
            <div class="form-group">
                <label for="event-description">Event Description:</label>
                <input type="text" class="form-control" id="event-description" name="description" required>
                <div class="invalid-feedback">
                    Please provide a description for the event.
                </div>
            </div>
            <div class="form-group">
                <label for="start-datetime">Start Date and Time:</label>
                <input type="datetime-local" class="form-control" id="start-datetime" name="start_datetime" required>
                <div class="invalid-feedback">
                    Please provide a start date and time.
                </div>
            </div>
            <div class="form-group">
                <label for="end-datetime">End Date and Time:</label>
                <input type="datetime-local" class="form-control" id="end-datetime" name="end_datetime" required>
                <div class="invalid-feedback">
                    Please provide an end date and time.
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Event</button>
            <a href="/event" class="btn btn-secondary">Back</a>
        </form>
    </div>
    <script>
        (function() {
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
