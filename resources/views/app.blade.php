<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
        <style>
            .bg-light {
                background-color: #eae9e9 !important;
            }
        </style>
        <title>{{env('APP_NAME')}}</title>
    </head>
    <body>
        <div id="app">
            <app></app>
        </div>
        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    </body>
</html>
