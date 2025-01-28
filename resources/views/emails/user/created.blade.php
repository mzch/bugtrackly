<x-mail::message>
# {{ __('emails.welcome.title', ['name' => $notifiable->full_name]) }},

{{__('emails.welcome.welcome_mg', ['app_name' => config('app.name')])}}

{{__('emails.welcome.text')}}
- **{{__('emails.welcome.email')}}** : {{ $notifiable->email }}
- **{{__('emails.welcome.password')}}** : `{!! $password !!}`

{{__('emails.welcome.advice')}}

<x-mail::button :url="route('login')">
{{__('emails.welcome.connect_label')}}
</x-mail::button>

{{__('emails.general.politeness')}}<br>
{{ config('app.name') }}
</x-mail::message>
