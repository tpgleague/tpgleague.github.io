
	{if $news_data}

	{foreach item=news from=$news_data name=news}
	<div class="news_item">
		{* News ID: {$news.newsid}*}
		<h2 class="news_title">{$news.title|escape}</h2>

		<div class="news_body">
		{* {eval var=$news.body} *}
		{$news.body}
		</div>

		{if $news.nplid}
		<div class="news_poll">
			{if $polls[$news.nplid].type == 'options'}

				<form method="post" action="{$smarty.server.REQUEST_URI}" class="form_poll">
					<p class="poll_title">{$polls[$news.nplid].title|escape}</p>
					<input type="hidden" name="poll_number" value="{$news.nplid}" />
					<table class="table_poll">
					{foreach from=$polls[$news.nplid].options key='nplchid' item='name'}
						<tr>
							<td><input type="radio" name="poll_choice" value="{$nplchid}" class="radio" /></td>
							<td style="text-align: left;">{$name|escape}</td>
						</tr>
					{/foreach}
					</table>

					{if !empty($polls[$news.nplid].error)}
						{$polls[$news.nplid].error}
					{else}
						<input type="submit" value="Vote" />
					{/if}
					{if !$polls[$news.nplid].closed}
						<br />Poll closes {$polls[$news.nplid].close_date|easy_day} at {$polls[$news.nplid].close_date|easy_time}.
					{/if}
				</form>

			{else}

				<p class="poll_title">{$polls[$news.nplid].title|escape}</p>

				{if $polls[$news.nplid].hidden == 'Closed'}
					<p>The results of this poll will be available when it closes on {$polls[$news.nplid].close_date|easy_day} at {$polls[$news.nplid].close_date|easy_time}.</p>
				{else}
					{$polls[$news.nplid].graph}
				{/if}

			{/if}

		</div>
		{/if}


		<div class="news_timestamp">Posted: {insert name='friendly_date' timestamp=$news.timestamp} by {$news.author|escape} - <a class="tpglinkalt" href="{$lgname}/article/{$news.newsid}/">comments({$news.number_of_comments})</a></div>
	</div>


	{if !$smarty.foreach.news.last}
	<hr />
	{/if}

	{/foreach}

	{if $news_articles_total > $news_articles_per_page}
		<div style="width: 100%;">
		{if $smarty.const.PAGE > 1}
			<p style="width: auto; float: left;"><a href="{$lgname}/news/page/{$smarty.const.PAGE-1}/" style="text-decoration: none;">&laquo; Newer Articles</a></p>{/if}
		{if $news_articles_max_pages > 1 && $smarty.const.PAGE < $news_articles_max_pages}
			<p style="width: 50%; float: right; text-align: right;"><a href="{$lgname}/news/page/{$smarty.const.PAGE+1}/" style="text-decoration: none;">Older Articles &raquo;</a></p>{/if}
		</div>
	{/if}

	{else}
	<div class="news_item">There are no news articles posted in this league.</div>
	{/if}
