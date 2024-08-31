<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card</title>
    <style>
    
        @font-face {
        font-family: 'Apska';
        src: url('fonts/Aspekta-500.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
        }

        body {
            font-family: 'Apska', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            max-width: 450px;
            width: 100%;
        }
        .cake-img {
            width: 280px;
            height: 200px;
            margin: 0 auto;
            margin-top: 2%;
        }
        h1 {
            font-size: 50px;
            font-weight: 800;
            margin: 20px 0 10px;
            color: #000000;
            width: 461px;
        }
        .wish {
            font-size: 18px;
            font-weight: 400;
            color: #000000;
            margin: 0 0 20px;
            width: 439px;
            line-height: 24px;
            padding-left: 10px;
        }
        .quote-card {
            padding: 20px;
            border-radius: 12px;
            box-shadow:0 0 10px rgba(0, 0, 0, 0.1);
            margin: 40px 0;
            font-size: 14px;
            color: #555555;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .quote-card img {
            width: 13%;
            height: 13%;
            margin-right: 10px;
        }
        .quote-card p {
           font-size: 14px;
           font-weight: 500;
           line-height: 20px;
           margin-top: 10px;
           width: 313px;
           margin-left: 11%;
           
        }

    </style>
</head>
<body>
    <div class="container">
        <img src="{{ $message->embed(public_path('mail/logo.png')) }}" width="34%" alt=""><br>
        <img src="{{ $message->embed(public_path('mail/image-cake.png')) }}" width="" alt="Birthday Cake" class="cake-img">
        <h1>Happy Birthday,<br> {{$user->first_name}} {{$user->last_name}} </h1>
        <p class="wish">We want to make this day special, so we thought the best gift is the opportunity to choose what you truly want.</p>
        
        <div class="quote-card">
            <img src="{{ $message->embed(public_path('mail/cupcake_image.png')) }}" alt="Cupcake Icon">
            <h3>In Two Days...</h3>
        </div>

    </div>
</body>
</html>