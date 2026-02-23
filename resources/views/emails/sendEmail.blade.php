<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $mail_details['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
        }

        p {
            line-height: 1.6;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome, {{ $mail_details['userData']->name }}!</h1>
        <p>{{ $mail_details['body'] }}</p>
        <p>If you have any questions, feel free to reply to this email. We're here to help!</p>
        <p>Best regards,<br>The Team</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} It Development Education & Research. All rights reserved.
    </div>
</body>

</html>