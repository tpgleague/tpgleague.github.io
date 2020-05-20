<?php /* Smarty version 2.6.14, created on 2012-12-01 23:08:38
         compiled from map.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'map.tpl', 20, false),)), $this); ?>
        <?php if ($this->_tpl_vars['map']['overview_path']): ?><img width="320px" height="240px" src="http://files.tpgleague.org<?php echo $this->_tpl_vars['map']['overview_path']; ?>
"><br/><br/><?php endif; ?>
    <?php if ($this->_tpl_vars['map']['filename']): ?><a href="<?php echo $this->_tpl_vars['map']['filename']; ?>
">Download The Map</a><?php else: ?><i>This is a stock map. No download is available.</i><?php endif; ?><br/>
    <?php if ($this->_tpl_vars['map']['config_path']): ?><a href="<?php echo $this->_tpl_vars['map']['config_path']; ?>
">Config Download</a><?php else: ?><i>No individual config file is available for this map.  Please download the entire config pack.</i><?php endif; ?><br/><br/>
    
    <?php if ($this->_tpl_vars['times_played'] || $this->_tpl_vars['scoringStats']): ?>
    <b>Since season <?php echo $this->_tpl_vars['earliest_season']; ?>
:</b><?php if ($this->_tpl_vars['earliest_season'] > 1): ?> <i>(previous seasons were on a different website)</i><?php endif; ?>
    <table>
    <?php if ($this->_tpl_vars['times_played']): ?>
    <?php $_from = $this->_tpl_vars['times_played']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['stage_type']):
?>
    <tr>
        <td><b><?php echo $this->_tpl_vars['stage_type']['stg_type']; ?>
 Uses:</b></td>
        <td><?php echo $this->_tpl_vars['stage_type']['used']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['scoring_stats'] && $this->_tpl_vars['scoring_stats']['avg_allies_score']): ?>
    <tr>
        <td><b>Avg Allies Score:</b></td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['scoring_stats']['avg_allies_score'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    </tr>
    <tr>
        <td><b>Avg Axis Score:</b></td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['scoring_stats']['avg_axis_score'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    </tr>
    <tr>
        <td><b>Max Allies Score:</b></td>
        <td><?php echo $this->_tpl_vars['scoring_stats']['max_allies_score']; ?>
</td>
    </tr>
    <tr>
        <td><b>Max Axis Score:</b></td>
        <td><?php echo $this->_tpl_vars['scoring_stats']['max_axis_score']; ?>
</td>
    </tr>
    <?php endif; ?>
    </table>
    
    <br />
    <br />
    <?php endif; ?>
    
    <?php if ($this->_tpl_vars['exploits']): ?>
    
    The following areas are known map exploitable areas and are illegal to use in a match.
    This is just the known areas. Other areas may still exist that you are not permitted to use.
    Areas you can only access by boosting are not displayed below, because they are illegal per rule.
    <br/><br/>
    <?php $_from = $this->_tpl_vars['exploits']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
    <img src="http://files.tpgleague.org<?php echo $this->_tpl_vars['map']['illegal_locations_path'];  echo $this->_tpl_vars['location']; ?>
">
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    
    <br />
    <br />
    