<div>


{foreach from=$organizations item='org'}
<br />{$org.name|escape} (<a href="/org.cp.php?orgid={$org.orgid}">edit</a>)
{foreachelse}
<p>You are not the owner of any organizations.</p>
{/foreach}

</div>