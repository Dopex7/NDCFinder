<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network</title>

    @vite('resources/css/app.css')
</head>
<body>
    
        <header>
        <nav>
        <h1>TENTON</h1>
        <a href="">Register</a>
        <a href="">Log In</a>
        </nav>
        </header>

        <main class="container">
        {{ $slot }}
        </main>

</body>
</html>