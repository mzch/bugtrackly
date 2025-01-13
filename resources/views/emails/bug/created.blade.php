<x-mail::message>
Un bug a été soumis.

---
- Rapportée par: **{{$bug->user->full_name}}**
- Priorité : **{{$priority['label']}}**
- Status : **{{$status['label']}}**
@if($bug->assigned_user_id)
- Assignée à : **{{$bug->assigned_user->full_name}}**
@endif
---
Titre :  **{{$bug->title}}**

Description :<br>
<div class="markdown">
    {!! Str::markdown($bugComment->content, [
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]) !!}
</div>
<x-mail::button :url="route('projects.bug.show', ['project' => $project->slug, 'bug'=>$bug->id])">
    Voir le bug
</x-mail::button>
....

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
