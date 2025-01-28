<x-mail::message>
# {{ __('emails.welcome.title', ['name' => $notifiable->full_name]) }},

{{ __('emails.account.msg') }}
- **{{__('emails.welcome.email')}}** : {{ $notifiable->email }}
- **{{__('emails.welcome.password')}}** : `{!! $password !!}`

<x-mail::button :url="route('login')">
{{__('emails.welcome.connect_label')}}
</x-mail::button>

{{__('emails.general.politeness')}}<br>
{{ config('app.name') }}
</x-mail::message>
