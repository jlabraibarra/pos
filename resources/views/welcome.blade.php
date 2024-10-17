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
          "_token" => csrf_token()
        ])
    </script>
</head>
<body>
    <div id="app" style="background-image: url('{{ asset('images/background_login.jpg') }}'); background-repeat: no-repeat;background-size: cover;">
        <auth-component></auth-component>
    </div>
</body>
</html>
