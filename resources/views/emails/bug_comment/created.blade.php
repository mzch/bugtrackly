<x-mail::message>

@if($dataMail['new_comment'])
**{{$dataMail['note_author']->full_name}}** a ajouté une note à ce bug.
@else
**{{$dataMail['note_author']->full_name}}** vient de modifier le bug.
@endif

@if($dataMail['new_comment'])
Note :<br>
<div class="markdown">
{!! Str::markdown($dataMail['new_comment']->content, [
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]) !!}
</div>
@endif

---
@if($dataMail['new_comment'])
- Noté crée le {{\Carbon\Carbon::parse($dataMail['new_comment']->created_at)->translatedFormat('j F Y à H\hi')}}
@else
- Bug modifié le {{\Carbon\Carbon::parse($bug->updated_at)->translatedFormat('j F Y à H\hi')}}
@endif
@if($dataMail['status'])
- Statut changé de **{{$dataMail['status']['old']['label']}}**  à **{{$dataMail['status']['new']['label']}}**
@endif
@if($dataMail['priority'])
- Priorité changée de **{{$dataMail['priority']['old']['label']}}**  à **{{$dataMail['priority']['new']['label']}}**
@endif
@if($dataMail['assigned_user'])
- Assignée à : **{{$dataMail['assigned_user']['new']->full_name}}**
@endif
---

<x-mail::button :url="route('projects.bug.show', ['project' => $project->slug, 'bug'=>$bug->id])">
    Voir le bug
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
