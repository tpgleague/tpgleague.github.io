<?php /* Smarty version 2.6.14, created on 2012-11-17 23:56:15
         compiled from article.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'article.tpl', 5, false),array('modifier', 'easy_day', 'article.tpl', 17, false),array('modifier', 'easy_time', 'article.tpl', 17, false),array('modifier', 'converted_timezone', 'article.tpl', 33, false),array('modifier', 'nl2br', 'article.tpl', 34, false),array('insert', 'friendly_date', 'article.tpl', 24, false),array('function', 'quickform_fieldset', 'article.tpl', 59, false),)), $this); ?>
    <a name="top"></a>
    <?php if ($this->_tpl_vars['news_data'] && ! $this->_tpl_vars['news_data']['deleted']): ?>

    <div class="news_item">
        <h2 class="news_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['news_data']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h2>

        <div class="news_body">

        <?php echo $this->_tpl_vars['news_data']['body']; ?>

        </div>

        <?php if ($this->_tpl_vars['news_data']['nplid']): ?>
        <div class="news_poll">
                <p class="poll_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news_data']['nplid']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>

                <?php if ($this->_tpl_vars['polls'][$this->_tpl_vars['news_data']['nplid']]['hidden'] == 'Closed'): ?>
                    <p>The results of this poll will be available when it closes on <?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news_data']['nplid']]['close_date'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 at <?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news_data']['nplid']]['close_date'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
.</p>
                <?php else: ?>
                    <?php echo $this->_tpl_vars['polls'][$this->_tpl_vars['news_data']['nplid']]['graph']; ?>

                <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="news_timestamp">Posted: <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'friendly_date', 'timestamp' => $this->_tpl_vars['news_data']['timestamp'])), $this); ?>
 by <?php echo ((is_array($_tmp=$this->_tpl_vars['news_data']['author'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
    </div>

    <hr />
    
    <?php $_from = $this->_tpl_vars['cmts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cmt']):
?>
    <?php if (! $this->_tpl_vars['cmt']['deleted']): ?>
    <a name="comment<?php echo $this->_tpl_vars['cmt']['cmt_id']; ?>
"></a>
    <div class="comment_container">
        <div class="comment_header"><?php if ($this->_tpl_vars['cmt']['tpg_admin']): ?><img src="/images/admin.png" title="TPG Admin" width="11" height="10" border="0"> <?php endif; ?><a href="http://www.tpgleague.org<?php echo $this->_tpl_vars['lgname']; ?>
/user/<?php echo $this->_tpl_vars['cmt']['posted_by_uid']; ?>
/"><?php if ($this->_tpl_vars['cmt']['handle']):  echo ((is_array($_tmp=$this->_tpl_vars['cmt']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else: ?>User#<?php echo $this->_tpl_vars['cmt']['posted_by_uid'];  endif; ?></a> posted on <?php echo ((is_array($_tmp=$this->_tpl_vars['cmt']['post_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
:</div>
        <blockquote class="comment_content"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['cmt']['comment_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</blockquote>
        <a href="#top"><img src="/images/top.gif" border="0"></a> &nbsp; <a href="#comment<?php echo $this->_tpl_vars['cmt']['cmt_id']; ?>
"><img src="/images/link.gif" Title="Direct link to this comment" border="0"></a> &nbsp; <?php if ($this->_tpl_vars['admin_aid']): ?><a href="article.php?newsid=<?php echo $this->_tpl_vars['news_data']['newsid']; ?>
&delete=<?php echo $this->_tpl_vars['cmt']['cmt_id']; ?>
"><img src="/images/trash.png" Title="Delete Post" border="0"></a><?php endif; ?>
    </div>
    <br>
    <?php endif; ?>
    <?php endforeach; else: ?>
    <p>There are no comments on this news article yet.</p>
    <?php endif; unset($_from); ?>
    
    <?php if ($this->_tpl_vars['loggedin']): ?>
        <?php if ($this->_tpl_vars['abuse_lock']): ?>
           <br><p><b>You have been banned from posting comments.</b></p>
        <?php elseif ($this->_tpl_vars['news_data']['comments_locked']): ?>
           <br><p><b>Commenting for this news article has been disabled.</b></p> 
        <?php elseif (! $this->_tpl_vars['mainhandle']): ?>
           <br><p><b>You must have a handle to make a post. Visit the <a href="http://www.tpgleague.org/edit.account.php?actedit=details">account details</a> page to add a handle. Posts without handles will not display.</b></p> 
        <?php else: ?>
            <div class="comment_area" style="margin-top: 4em">

                <b>Comment as <?php echo ((is_array($_tmp=$this->_tpl_vars['mainhandle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</b> <i>(1000 characters max. Change your posting name on the <a href="http://www.tpgleague.org/edit.account.php?actedit=details">account details</a> page)</i>:<br/>
                <form action="article.php?newsid=<?php echo $this->_tpl_vars['news_data']['newsid']; ?>
" method="post" id="comment_form">
                <?php if ($this->_tpl_vars['comment_form']['errors']): ?>
                <p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
                <?php endif; ?>
                
                <?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['comment_form'],'id' => 'fieldset_optional','class' => 'qffieldset','fields' => 'comments, captcha, captcha_code'), $this);?>

                
                <p><?php echo $this->_tpl_vars['comment_form']['submit']['html']; ?>
</p>
                </form>

            </div> 
        <?php endif; ?>
    <?php else: ?>
        <br><p><b>You must be logged in to post comments.</b></p>
    <?php endif; ?>

    <?php else: ?>
    <div class="news_item">The article id provided is either invalid or the article has been removed.</div>
    <?php endif; ?>