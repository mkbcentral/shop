<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layouts.partials.head')
<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30">
    <div class="min-h-screen">
        <!-- Main Content with Two Columns -->
        {{ $slot }}
    </div>
</body>

</html>
