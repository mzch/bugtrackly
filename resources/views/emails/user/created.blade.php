<x-mail::message>
# Bonjour {{$notifiable->full_name}},

Bienvenue sur **BugTrackly** !

Un compte utilisateur a été créé pour vous afin de faciliter la gestion de vos projets et le suivi de vos bugs.
Vous pouvez désormais vous connecter à la plateforme à l'aide des identifiants suivants :
- **Adresse email** : {{ $notifiable->email }}
- **Mot de passe** : `{{ $password }}`

Nous vous recommandons fortement de changer votre mot de passe lors de votre première connexion pour
garantir la sécurité de votre compte.

<x-mail::button :url="route('login')">
Je me connecte
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
