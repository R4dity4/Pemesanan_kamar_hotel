<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instagaram</title>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: cursive, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            /* padding: 20px; */
            font-size: 40px;
        }
        .title-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin: 24px 0;
        }
        .insta-icon {
            width: 56px;
            height: 56px;
            flex: 0 0 56px;
            display: block;
        }
        .title-row h1 {
            margin: 0;
            font-size: 40px;
        }
    </style>
</head>
<body>
    <center>
        <div class="title-row">
            <svg class="insta-icon" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="instaTitle">
                <title id="instaTitle">Instagram</title>
                <defs>
                    <linearGradient id="instaGrad" x1="0" x2="1" y1="0" y2="1">
                        <stop offset="0%" stop-color="#f58529"/>
                        <stop offset="50%" stop-color="#dd2a7b"/>
                        <stop offset="100%" stop-color="#8134af"/>
                    </linearGradient>
                </defs>
                <rect width="512" height="512" rx="96" fill="url(#instaGrad)"/>
                <rect x="96" y="96" width="320" height="320" rx="80" fill="none" stroke="#fff" stroke-width="32"/>
                <circle cx="256" cy="256" r="80" fill="none" stroke="#fff" stroke-width="32"/>
                <circle cx="358" cy="154" r="20" fill="#fff"/>
            </svg>
            <h1>INI INSTAGARAM 😨</h1>
        </div>
        <br>
        <img src="{{ asset('images/guthib.PNG') }}" alt="Instagaram" style="width: 1000px; height: auto;">
    </center>
</body>
</html>
