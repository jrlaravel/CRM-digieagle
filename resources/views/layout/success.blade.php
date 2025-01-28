<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
  <style>
    body {
      text-align: center;
      padding: 40px 0;
      background: #EBF0F5;
      margin-top: 10%;

    }
    h1 {
      color: #88B04B;
      font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
      font-weight: 900;
      font-size: 40px;
      margin-bottom: 10px;
    }
    p {
      color: #404F5E;
      font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
      font-size: 20px;
      margin: 0;
    }
    i {
      color: #9ABC66;
      font-size: 100px;
      line-height: 200px;
      margin-left: -15px;
      display: inline-block;
      transform: scale(0); /* Start with the checkmark hidden */
      animation: pop-in 0.5s ease-out forwards, check-draw 0.5s ease-out 0.5s;
    }
    .card {
      background: white;
      padding: 60px;
      border-radius: 4px;
      box-shadow: 0 2px 3px #C8D0D8;
      display: inline-block;
    }
    @keyframes pop-in {
      0% {
        transform: scale(0);
      }
      70% {
        transform: scale(1.2);
      }
      100% {
        transform: scale(1);
      }
    }
    @keyframes check-draw {
      0% {
        content: '';
      }
      100% {
        content: '✓';
      }
    }

    @media screen and (min-width: 400px) and (max-width: 600px) {
      body {
        margin-top: 30%;
      }
      .card {
        padding: 40px; /* Adjust the card padding */
        margin-top: 66%;
      }

      h1 {
        font-size: 30px; /* Reduce font size */
      }

      p {
        font-size: 16px; /* Adjust paragraph font size */
      }

      i {
        font-size: 80px; /* Adjust checkmark size */
        line-height: 160px;
      }
    }
  </style>
  <body>
    <div class="card">
      <div
        style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
        <i class="checkmark">✓</i>
      </div>
      <h1>Thank You!</h1>
      <p>
        We appreciate your interest in our organization.<br />
        Your application has been successfully submitted, and our team will review it shortly.<br />
        You will be contacted if your profile matches our requirements.
      </p>
    </div>
  </body>
</html>
