# Google Calendar Event
A google calendar event app built using core PHP.


## Prerequisites

- PHP 8.x or later
- Composer
- Google API Client Library for PHP
- A Google Cloud project with the Google Calendar API enabled
- `credentials.json` file downloaded from the Google Cloud Console
## Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/nepkto/google-calendar
   cd google-calenda
2. **Install dependencies:**
    ```bash
    composer install
3. **Add your Google API credentials:**

4. **Copy your credentials.json file into the root directory of the project.**
5. **Update the redirect URI /src/Config/config.php**
6. **Set up your web server:**
    Ensure your server's document root is set to the google-calendar directory.
   ```bash
   php -S localhost:port public/index.php