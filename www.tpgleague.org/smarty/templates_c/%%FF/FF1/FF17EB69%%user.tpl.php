<?php /* Smarty version 2.6.14, created on 2012-11-30 00:13:02
         compiled from user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'user.tpl', 10, false),array('modifier', 'truncate', 'user.tpl', 10, false),array('modifier', 'converted_timezone', 'user.tpl', 21, false),array('modifier', 'replace', 'user.tpl', 33, false),array('modifier', 'nl2br', 'user.tpl', 39, false),array('modifier', 'date_format', 'user.tpl', 66, false),)), $this); ?>
<?php if ($this->_tpl_vars['user_data']['deleted']): ?>
    <b>This user's account has been deleted.</b>
<?php else: ?>
    <table style="width: 610px; marign: 0; padding: 0;" border="0">
    <tr>
    <td align="left">
        <table>        
        <tr>
            <td><b>Name:</b></td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['user_data']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php if ($this->_tpl_vars['user_data']['hide_lastname']):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_data']['lastname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, ".") : smarty_modifier_truncate($_tmp, 2, ".")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=$this->_tpl_vars['user_data']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?></td>
        </tr>
        <?php if ($this->_tpl_vars['user_data']['country']): ?>
        <tr>
            <td><b>Location:</b></td>
            <td><?php if ($this->_tpl_vars['user_data']['ccode']): ?><img src="/images/flags/<?php echo $this->_tpl_vars['user_data']['ccode']; ?>
.png" width="16" height="11" alt="<?php echo $this->_tpl_vars['user_data']['ccode']; ?>
" title="<?php echo $this->_tpl_vars['user_data']['country']; ?>
" /> <?php endif; ?>
            <?php if ($this->_tpl_vars['user_data']['city']):  echo ((is_array($_tmp=$this->_tpl_vars['user_data']['city'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif;  if ($this->_tpl_vars['user_data']['city'] && $this->_tpl_vars['user_data']['state']): ?>,&nbsp;<?php elseif (! $this->_tpl_vars['user_data']['city'] && ! $this->_tpl_vars['user_data']['state']):  echo $this->_tpl_vars['user_data']['country'];  endif;  if ($this->_tpl_vars['user_data']['state']):  echo ((is_array($_tmp=$this->_tpl_vars['user_data']['state'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td><b>Joined TPG:</b></td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['user_data']['join_date'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
        </tr>
        </table>
    </td>
    <td align="right">
        <?php if ($this->_tpl_vars['user_data']['user_avatar_url'] && ! $this->_tpl_vars['user_data']['abuse_lock']): ?><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['user_data']['user_avatar_url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" width="100px" height="56px"><?php endif; ?>
    </td>
    </tr>
    </table>

    <?php if ($this->_tpl_vars['user_data']['steam_profile_url']): ?>
    <br>
    <a href="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_data']['steam_profile_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '@', 'at') : smarty_modifier_replace($_tmp, '@', 'at')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/images/steam.png" title="Steam Community Profile" border="0"></a>
    <?php endif; ?>
    
    <?php if ($this->_tpl_vars['user_data']['user_comments'] && ! $this->_tpl_vars['user_data']['abuse_lock']): ?>
    <br><br>
    <b>User Comments:</b>
    <p><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_data']['user_comments'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
    <?php endif; ?>    
    
    <?php if ($this->_tpl_vars['roster_data']): ?>
    <br><br>
    
    <b>Current Teams:</b><br><br>
    <table class="tpg_results">
    <thead>

    <tr>
        <th>League</th>
        <th>Team Name</th>
        <th>Tag</th>
        <th>Handle</th>
        <th>Game ID</th>
        <th>Joined</th>
    </tr>
    </thead>
    <tbody>
        <?php $_from = $this->_tpl_vars['roster_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['member'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['member']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['member']):
        $this->_foreach['member']['iteration']++;
?>
        <tr>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td><a href="http://www.tpgleague.org/<?php echo ((is_array($_tmp=$this->_tpl_vars['member']['leagues_lgname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
/team/<?php echo $this->_tpl_vars['member']['teams_tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['teams_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
            <td><a href="http://www.tpgleague.org/<?php echo ((is_array($_tmp=$this->_tpl_vars['member']['leagues_lgname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
/team/<?php echo $this->_tpl_vars['member']['teams_tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['teams_tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
            <td><?php echo $this->_tpl_vars['member']['rosters_gid']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_join_date_gmt'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D") : smarty_modifier_date_format($_tmp, "%D")); ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
    <br />
    <?php endif; ?>
    
    <?php if ($this->_tpl_vars['game_ids_data']): ?>
    <br><br>
    
    <b>Game IDs Used:</b><?php if (count ( $this->_tpl_vars['game_ids_data'] ) > 1): ?> (Note: This is every ID the user has ever entered into the system.  It is possible some of these might have been entered in error)<?php endif; ?><br><br>

    <?php $_from = $this->_tpl_vars['game_ids_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['gameids'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gameids']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['gameid']):
        $this->_foreach['gameids']['iteration']++;
?>
    <a class="gidlink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/membersearch/?search=<?php echo ((is_array($_tmp=$this->_tpl_vars['gameid']['gid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&rosters_gid=on"><?php echo ((is_array($_tmp=$this->_tpl_vars['gameid']['gid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><br>
    <?php endforeach; endif; unset($_from); ?>

    <br />
    <?php endif; ?>
    
<?php endif; ?>
<br><br>