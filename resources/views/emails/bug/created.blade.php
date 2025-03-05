<x-mail::message>
{{__('emails.bug.created.title')}}

---
- {{__('emails.bug.created.reported_by')}} **{{$bug->user->full_name}}**
- {{__('emails.bug.created.priority')}} **{{$priority['label']}}**
@if($bug->ticket_category_id)
- {{__('emails.bug.ticket_cat_to')}} **{{$bug->ticket_category->name}}**
@endif
- {{__('emails.bug.created.status')}} **{{$status['label']}}**
@if($bug->assigned_user_id)
- {{__('emails.bug.created.assigned_to')}} **{{$bug->assigned_user->full_name}}**
@endif
@if($files && count($files) > 0)
@if(count($files) > 1)
- {{count($files)}} {{__('emails.bug.created.attachedFiles')}}
@else
- 1 {{__('emails.bug.created.attachedFile')}}
@endif
@foreach($files as $file)
    - {{$file}}
@endforeach
@endif
---
{{__('emails.bug.created.bug_title')}} :  **{{$bug->title}}**
<div class="markdown">
    {!! Str::markdown($bugComment->content, [
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]) !!}
</div>
<x-mail::button :url="route('projects.bug.show', ['project' => $project->slug, 'bug'=>$bug->id])">
    {{__('emails.bug.created.show_bug')}}
</x-mail::button>

{{__('emails.general.politeness')}}<br>
{{ config('app.name') }}
</x-mail::message>
