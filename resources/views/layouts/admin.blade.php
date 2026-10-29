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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-[#f2fbf4] text-[#173e34]">
    <header class="border-b border-[#dbe9dd] bg-white">
        <div class="mx-auto flex max-w-[1180px] items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ route('admin.plants.index') }}" class="font-['Manrope'] text-lg font-extrabold text-[#003f32]">TSTH2 <span class="font-normal text-[#789187]">Admin</span></a>
            <div class="flex items-center gap-4 text-xs font-bold">
                <!-- Tombol Kelola Kategori Ditambahkan di Sini -->
                <a href="{{ route('admin.categories.index') }}" class="text-[#557067] hover:text-[#003f32]">Kelola Kategori</a>
                
                <a href="{{ route('home') }}" class="text-[#557067] hover:text-[#003f32]">Lihat katalog</a>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="text-[#b87900] hover:text-[#003f32]">Keluar</button></form>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-[1180px] px-5 py-8 lg:px-8">@yield('content')</main>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#003f32',
                    timer: 3200,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Periksa kembali form',
                    text: @json(implode("\n", $errors->all())),
                    confirmButtonColor: '#003f32',
                });
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form[data-swal-validation]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    const requiredField = [...form.querySelectorAll('[required]:not(:disabled)')]
                        .find((field) => !field.value.trim());

                    if (!requiredField) return;

                    event.preventDefault();
                    const label = requiredField.closest('label')?.firstChild?.textContent?.trim() || 'kolom ini';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form belum lengkap',
                        text: `${label.replace('*', '').trim()} wajib diisi.`,
                        confirmButtonColor: '#003f32',
                    }).then(() => requiredField.focus());
                });
            });

            document.querySelectorAll('form[data-delete-form]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const title = form.dataset.deleteTitle || 'Hapus data?';
                    const text = form.dataset.deleteText || 'Data yang sudah dihapus tidak dapat dikembalikan.';
                    Swal.fire({
                        icon: 'warning',
                        title,
                        text,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#b04d3e',
                        cancelButtonColor: '#718178',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</body>
</html>