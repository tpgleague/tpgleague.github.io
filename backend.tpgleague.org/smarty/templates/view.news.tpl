
<h1>{$league_title}</h1>


<table class="clean">
	<tr>
		<th>&nbsp;</th>
		<th>Post Date</th>
		<th>Title</th>
		<th>Admin</th>
		<th>Deleted</th>
		<th>Poll</th>
        <th>Comments Locked?</th>
	</tr>
{foreach from=$news_posts item=news}
	<tr{if $news.deleted} style="text-decoration: line-through;"{/if}>
		<td><a href="/edit.news.php?newsid={$news.newsid}">Edit</a></td>
		<td nowrap="nowrap">{$news.create_date_gmt|converted_timezone}</td>
		<td>{$news.title}</td>
		<td>{$news.admin_name}</td>
		<td>{if $news.deleted}Deleted{/if}</td>
		<td>{if $news.nplid}<a href="/view.poll.results.php?nplid={$news.nplid}">{$news.poll_title|escape}</a>{/if}</td>
        <td>{if ($news.comments_locked)}Yes{else}No{/if}</td>
	</tr>
{foreachelse}
<tr><td>&nbsp;</td><td colspan="4">No news posts in this league</td></tr>
{/foreach}
</table>
