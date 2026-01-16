@props([
    'forgotPasswordRoute' => null,
    'registerRoute' => null
])

<div class="flex items-center justify-between">
    <label for="remember" class="flex items-center cursor-pointer">
        <input wire:model="remember" type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500/20 focus:ring-offset-0">
        <span class="ml-2 text-sm text-slate-400">Se souvenir de moi</span>
    </label>

    @if ($forgotPasswordRoute)
        <a href="{{ $forgotPasswordRoute }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition">
            Mot de passe oublié ?
        </a>
    @endif
</div>

@if ($registerRoute)
    <p class="text-center text-sm text-slate-400 mt-4">
        Pas de compte ?
        <a href="{{ $registerRoute }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition">
            Créer un compte
        </a>
    </p>
@endif
