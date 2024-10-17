<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (isset($title)) ? $title." |" : ""  }} {{config("app.name")}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.laravel = @json([
          "url" => asset('/'),
          "_token" => csrf_token(),
          "user" => [
            "name" => \Auth::user()->name
          ]
        ])
    </script>
</head>
<body>
    <div id="app">
        <pos-component></pos-component>
    </div>
</body>
</html>
