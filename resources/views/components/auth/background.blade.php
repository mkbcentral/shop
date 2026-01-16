{{--
    Auth Background Component

    Usage: <x-auth.background />

    Provides the animated gradient background with blobs and grid pattern
    used on authentication pages (login, register, forgot-password, etc.)
--}}

<div class="absolute inset-0 overflow-hidden pointer-events-none">
    {{-- Gradient orbs --}}
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-gradient-to-br from-indigo-500/30 via-purple-600/20 to-pink-500/10 rounded-full blur-[100px] animate-blob"></div>
    <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-gradient-to-br from-cyan-500/20 via-blue-600/15 to-indigo-600/10 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>

    {{-- Grid pattern --}}
    <div class="absolute inset-0 bg-[linear-gradient(rgba(99,102,241,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(99,102,241,0.03)_1px,transparent_1px)] bg-[size:50px_50px] [mask-image:radial-gradient(ellipse_80%_60%_at_50%_0%,#000_70%,transparent_110%)]"></div>
</div>

<style>
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.05); }
    }
    .animate-blob { animation: blob 15s ease-in-out infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
</style>
