<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin TSTH2')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#f2fbf4] text-[#173e34]">
    <header class="border-b border-[#dbe9dd] bg-white">
        <div class="mx-auto flex max-w-[1180px] items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ route('admin.plants.index') }}" class="font-['Manrope'] text-lg font-extrabold text-[#003f32]">TSTH2 <span class="font-normal text-[#789187]">Admin</span></a>
            <div class="flex items-center gap-4 text-xs font-bold">
                <a href="{{ route('home') }}" class="text-[#557067] hover:text-[#003f32]">Lihat katalog</a>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="text-[#b87900] hover:text-[#003f32]">Keluar</button></form>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-[1180px] px-5 py-8 lg:px-8">@yield('content')</main>
</body>
</html>
