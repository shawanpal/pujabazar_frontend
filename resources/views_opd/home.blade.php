<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .button {
                display: inline-block;
                text-align: center;
                vertical-align: middle;
                padding: 12px 24px;
                border: 1px solid #a12727;
                border-radius: 8px;
                background: #ff4a4a;
                background: -webkit-gradient(linear, left top, left bottom, from(#ff4a4a), to(#992727));
                background: -moz-linear-gradient(top, #ff4a4a, #992727);
                background: linear-gradient(to bottom, #ff4a4a, #992727);
                text-shadow: #591717 2px 2px 1px;
                font: normal normal normal 20px arial;
                color: #ffffff;
                text-decoration: none;
            }
            .button:hover,
            .button:focus {
                background: #ff5959;
                background: -webkit-gradient(linear, left top, left bottom, from(#ff5959), to(#b62f2f));
                background: -moz-linear-gradient(top, #ff5959, #b62f2f);
                background: linear-gradient(to bottom, #ff5959, #b62f2f);
                color: #ffffff;
                text-decoration: none;
            }
            .button:active {
                background: #982727;
                background: -webkit-gradient(linear, left top, left bottom, from(#982727), to(#982727));
                background: -moz-linear-gradient(top, #982727, #982727);
                background: linear-gradient(to bottom, #982727, #982727);
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">


            <div class="content">
                <div class="title m-b-md">
                    We Are Almost There!
                </div>

                <a class="button" href="{{ route('home') }}">Welcome To Puja Bazar</a>

                <div class="links">

                </div>
            </div>
        </div>
    </body>
</html>
