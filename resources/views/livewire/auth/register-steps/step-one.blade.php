<div>
    <form wire:submit.prevent="nextStep" class="space-y-4">
        <x-auth.input
            wire:model="name"
            type="text"
            name="name"
            label="Nom complet"
            placeholder="Jean Dupont"
            icon="user"
            autocomplete="name"
            autofocus
            :error="$errors->first('name')"
        />

        <x-auth.input
            wire:model="email"
            type="email"
            name="email"
            label="Adresse e-mail"
            placeholder="vous@exemple.com"
            icon="email"
            autocomplete="email"
            :error="$errors->first('email')"
        />

        <x-auth.input
            wire:model.live="password"
            type="password"
            name="password"
            label="Mot de passe"
            placeholder="Minimum 8 caractères"
            icon="lock"
            :showPasswordToggle="true"
            autocomplete="new-password"
            :error="$errors->first('password')"
        />

        <x-auth.input
            wire:model.live="password_confirmation"
            type="password"
            name="password_confirmation"
            label="Confirmer le mot de passe"
            placeholder="Répétez votre mot de passe"
            icon="lock"
            :showPasswordToggle="true"
            autocomplete="new-password"
            :error="$errors->first('password_confirmation')"
        />

        <x-auth.submit-button
            text="Continuer"
            loadingText="Validation..."
            loadingTarget="nextStep"
        />
    </form>
</div>
