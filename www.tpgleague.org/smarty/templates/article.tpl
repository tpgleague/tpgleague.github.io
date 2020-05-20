    <a name="top"></a>
    {if $news_data && !$news_data.deleted}

    <div class="news_item">
        <h2 class="news_title">{$news_data.title|escape}</h2>

        <div class="news_body">

        {$news_data.body}
        </div>

        {if $news_data.nplid}
        <div class="news_poll">
                <p class="poll_title">{$polls[$news_data.nplid].title|escape}</p>

                {if $polls[$news_data.nplid].hidden == 'Closed'}
                    <p>The results of this poll will be available when it closes on {$polls[$news_data.nplid].close_date|easy_day} at {$polls[$news_data.nplid].close_date|easy_time}.</p>
                {else}
                    {$polls[$news_data.nplid].graph}
                {/if}
        </div>
        {/if}

        <div class="news_timestamp">Posted: {insert name='friendly_date' timestamp=$news_data.timestamp} by {$news_data.author|escape}</div>
    </div>

    <hr />
    
    {foreach item=cmt from=$cmts}
    {if !$cmt.deleted}
    <a name="comment{$cmt.cmt_id}"></a>
    <div class="comment_container">
        <div class="comment_header">{if $cmt.tpg_admin}<img src="/images/admin.png" title="TPG Admin" width="11" height="10" border="0"> {/if}<a href="{$lgname}/user/{$cmt.posted_by_uid}/">{if $cmt.handle}{$cmt.handle|escape}{else}User#{$cmt.posted_by_uid}{/if}</a> posted on {$cmt.post_date_gmt|converted_timezone}:</div>
        <blockquote class="comment_content">{$cmt.comment_text|escape|nl2br}</blockquote>
        <a href="#top"><img src="/images/top.gif" border="0"></a> &nbsp; <a href="#comment{$cmt.cmt_id}"><img src="/images/link.gif" Title="Direct link to this comment" border="0"></a> &nbsp; {if $admin_aid}<a href="article.php?newsid={$news_data.newsid}&delete={$cmt.cmt_id}"><img src="/images/trash.png" Title="Delete Post" border="0"></a>{/if}
    </div>
    <br>
    {/if}
    {foreachelse}
    <p>There are no comments on this news article yet.</p>
    {/foreach}
    
    {if $loggedin}
        {if $abuse_lock}
           <br><p><b>You have been banned from posting comments.</b></p>
        {elseif $news_data.comments_locked}
           <br><p><b>Commenting for this news article has been disabled.</b></p> 
        {elseif !$mainhandle}
           <br><p><b>You must have a handle to make a post. Visit the <a href="http://www.tpgleague.org/edit.account.php?actedit=details">account details</a> page to add a handle. Posts without handles will not display.</b></p> 
        {else}
            <div class="comment_area" style="margin-top: 4em">

                <b>Comment as {$mainhandle|escape}</b> <i>(1000 characters max. Change your posting name on the <a href="http://www.tpgleague.org/edit.account.php?actedit=details">account details</a> page)</i>:<br/>
                <form action="article.php?newsid={$news_data.newsid}" method="post" id="comment_form">
                {if $comment_form.errors}
                <p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
                {/if}
                
                {quickform_fieldset form=$comment_form id='fieldset_optional' class='qffieldset' fields='comments, captcha, captcha_code'}
                
                <p>{$comment_form.submit.html}</p>
                </form>

            </div> 
        {/if}
    {else}
        <br><p><b>You must be logged in to post comments.</b></p>
    {/if}

    {else}
    <div class="news_item">The article id provided is either invalid or the article has been removed.</div>
    {/if}
