<x-mail::message>
# Bonjour {{$notifiable->full_name}},

Votre mot de passe a été modifié par un administrateur :
vous pouvez désormais vous connecter à la plateforme à l'aide des identifiants suivants :
- **Adresse email** : {{ $notifiable->email }}
- **Mot de passe** : `{!! $password !!}`

<x-mail::button :url="route('login')">
Je me connecte
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
