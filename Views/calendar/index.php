<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Listing</title>
    <?php
    include_once('include.php');
    ?>
</head>

<body>

    <div class="container mt-5">
        <div id="sessionMessage" class="alert alert-success d-none" role="alert"></div>
        <?php \App\Services\FlashMessage::display(); ?>
        <h2>Events</h2>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a class="btn btn-primary" href="event/create">
                <i class="fas fa-plus"></i> Create Event
            </a>
            <form action="/disconnect" method="POST">
                <button class="btn btn-secondary" href="event/create">
                    <i class="fas fa-signout"></i> Logout
                </button>
            </form>

        </div>
        <div id="loaderContainer" class="text-center">
            <div class="loader"></div>
        </div>
        <div id="events-list" style="display:none"></div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="eventName" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="eventName" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="eventDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Create Event</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/events-list')
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    displayEvents(data);
                } catch (error) {
                    document.getElementById('events-list').innerHTML = `<div class="alert alert-danger">Error parsing event data.</div>`;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('events-list').innerHTML = `<div class="alert alert-danger">An error occurred while fetching events.</div>`;
            })
            .finally(() => {
                document.getElementById('loaderContainer').style.display = 'none';
                document.getElementById('events-list').style.display = 'block';
            });

    });

    function displayEvents(eventsData) {
        console.log('eventsData: ', eventsData);
        const eventsList = document.getElementById('events-list');


        if (!Array.isArray(eventsData) || eventsData.length === 0) {
            eventsList.innerHTML = `<div class="alert alert-info">No events found.</div>`;
            return;
        }

        let html = '<div class="list-group">';
        eventsData.forEach(event => {
            const startDate = new Date(event.start);
            const endDate = new Date(event.end);
            html += `
                    <div class="list-group-item" data-event-id="${event.id || 0}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">${event.summary}</h5>
                                 <small>Start: ${startDate.toLocaleString()}</small><br>
                                 <small>End: ${endDate.toLocaleString()}</small>
                            </div>
                            <button class="btn btn-danger btn-sm delete-event"  data-id="${event.id || 0}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `
        });
        html += '</div>';
        eventsList.innerHTML = html;
    }

    async function deleteEvent(id) {
        try {
            if (confirm("Are You Sure")) {
                document.getElementById('loaderContainer').style.display = 'block';
                const response = await fetch(`/event/${id}`, {
                    method: 'DELETE',
                }).then((res) => {
                    if (!res) {
                        throw new Error('Failed to delete event');
                    }
                    const eventElement = document.querySelector(`.list-group-item[data-event-id="${id}"]`);
                    if (eventElement) {
                        eventElement.remove();


                    }
                }).finally(() => {
                    document.getElementById('loaderContainer').style.display = 'none';
                    showSessionMessage('Delete Successful', 'success');
                });

            }

        } catch (error) {
            alert('Failed to delete event. Please try again.');
        }
    }
    // Event listener for delete buttons
    document.getElementById('events-list').addEventListener('click', function(e) {
        if (e.target.closest('.delete-event')) {
            const id = e.target.closest('.delete-event').dataset.id;
            deleteEvent(id);
        }
    });

    function showSessionMessage(message, type = 'success') {
        const sessionMessage = document.getElementById('sessionMessage');
        sessionMessage.textContent = message;
        sessionMessage.className = `alert alert-${type}`;
        sessionMessage.style.display = 'block';
        setTimeout(() => {
            sessionMessage.style.display = 'none';
        }, 3000);
    }
</script>

</html>