<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect to Google Calendar</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
        }

        .btn-google-calendar {
            background-color: #4285F4;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-google-calendar:hover {
            background-color: #357ae8;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php echo "<a class='btn-google-calendar' href='$authUrl'>Connect to Google Calendar</a>" ?>
    </div>
</body>

</html>