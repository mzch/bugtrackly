<x-mail::message>

@if($dataMail['new_comment'])
{{__('emails.bug.updated.add_note', ['user'=>$dataMail['note_author']->full_name])}}
@else
{{__('emails.bug.updated.update_bug', ['user'=>$dataMail['note_author']->full_name])}}
@endif

---
- {{__('emails.bug.updated.bug')}} **{{$bug->title}}**
@if($dataMail['new_comment'])
- {{__('emails.bug.updated.date_note', ['date' => \Carbon\Carbon::parse($dataMail['new_comment']->created_at)->translatedFormat(__('emails.bug.updated.date_format'))])}}
@else
- {{__('emails.bug.updated.date_bug', ['date' => \Carbon\Carbon::parse($bug->updated_at)->translatedFormat(__('emails.bug.updated.date_format'))])}}
@endif
@if($dataMail['status'])
- {{__('emails.bug.updated.change_status', ['old' => $dataMail['status']['old']['label'], 'new' => $dataMail['status']['new']['label']])}}
@endif
@if($dataMail['priority'])
- {{__('emails.bug.updated.change_priotity', ['old' => $dataMail['priority']['old']['label'], 'new' => $dataMail['priority']['new']['label']])}}
@endif
@if($dataMail['assigned_user'])
@if($dataMail['assigned_user']['new'] !== null)
- {{__('emails.bug.created.assigned_to')}} **{{$dataMail['assigned_user']['new']->full_name}}**
@else
- {{__('emails.bug.created.assigned_to')}} **{{__('emails.bug.updated.no_assigned')}}**
@endif
@endif
@if($dataMail['files'] && count($dataMail['files']) > 0)
@if(count($dataMail['files']) > 1)
- {{count($dataMail['files'])}} {{__('emails.bug.created.attachedFiles')}}
@else
- 1 {{__('emails.bug.created.attachedFile')}}
@endif
@foreach($dataMail['files'] as $file)
    - {{$file}}
@endforeach
@endif
---

@if($dataMail['new_comment'])
<div class="markdown">
{!! Str::markdown($dataMail['new_comment']->content, [
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]) !!}
</div>
@endif



<x-mail::button :url="route('projects.bug.show', ['project' => $project->slug, 'bug'=>$bug->id])">
    {{__('emails.bug.created.show_bug')}}
</x-mail::button>

{{__('emails.general.politeness')}}<br>
{{ config('app.name') }}
</x-mail::message>
