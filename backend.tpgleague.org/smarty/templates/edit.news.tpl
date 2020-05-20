{if $success}
<div style="color: blue;">>News successfully edited.</div>
{/if}

<div>To ensure consistency between posts/leagues, ALWAYS use the default font and color, except when to emphasize the occassional word or two.</div>
<form {$edit_news_form.attributes}>
{$edit_news_form.hidden}


{quickform_fieldset form=$edit_news_form id='fieldset_news_league' class='qffieldset' fields='title, body, admin_name, lid, deleted, comments_locked, submit' legend='Edit News Post'}
</form>

<br />

<div>
	Enter inserts a new paragraph. Shift+Enter inserts a single line break.
</div>