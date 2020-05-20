<?php

class gidRoster
{
    public $gidType;
    public $gidName;
    public $gidRequired;
    public $form;
    public $rid;
    public $lid;

    function __construct(&$formName, $lid, $rid=NULL)
    {
        global $db;
        $this->form =& $formName;

        $sql = 'SELECT gid_type, gid_name FROM leagues WHERE lid = ? LIMIT 1';
        $gidInfo =& $db->getRow($sql, array($lid));

        $this->lid = $lid;
        $this->gidType = $gidInfo['gid_type'];
        $this->gidName = $gidInfo['gid_name'] ? $gidInfo['gid_name'] : 'Game ID';

        if ($gidInfo['gid_type'] != 'None') $this->gidRequired = TRUE;
        else $this->gidRequired = FALSE;

        if ($rid) $this->rid = '_'.$rid;
    }

    function gidForm()
    {
        switch ($this->gidType):
            case 'STEAMID':
                $this->form->addElement('text', 'gid'.$this->rid, $this->gidName, array('maxlength' => 18, 'size' => 18));
                $this->form->setDefaults(array('gid'.$this->rid => 'STEAM_'));
                if ($this->gidRequired) {
                    $this->form->addRule('gid'.$this->rid, $this->gidName . ' is required.', 'required');
                }
                $this->form->addRule('gid'.$this->rid, 'This is not a valid '. $this->gidName .'.', 'regex', '/^STEAM_[01]:[01]:[123456789][0123456789]{1,7}$/i');
                $this->form->applyFilter('gid'.$this->rid, 'strtoupper');
                $this->form->addElement('static', 'note_gid'.$rid, 'Format: STEAM_X:X:XXXXXXXX');
                return TRUE;
            case 'Numeric':
                $this->form->addElement('text', 'gid'.$this->rid, $this->gidName);
                if ($this->gidRequired) {
                    $this->form->addRule('gid'.$this->rid, $this->gidName . ' is required.', 'required');
                }
                $this->form->addRule('gid'.$this->rid, 'This is not a valid '. $this->gidName .'.', 'numeric');
                return TRUE;
            case 'PBGUID':
                $this->form->addElement('text', 'gid'.$this->rid, $this->gidName);
                if ($this->gidRequired) {
                    $this->form->addRule('gid'.$this->rid, $this->gidName . ' is required.', 'required');
                }
                $this->form->addRule('gid'.$this->rid, 'This is not a valid '. $this->gidName .'.', 'regex', '/^[0-9a-f]{8}$/i');
                $this->form->applyFilter('gid'.$this->rid, 'strtolower');
                $this->form->addElement('static', 'note_gid'.$rid, 'Last 8 characters of your PunkBuster GUID');
                return TRUE;
            default:
                return FALSE;
        endswitch;
    }

    function gidRequired()
    {
        return $this->gidRequired;
    }

    function gidInUse($gid=NULL) {
        if ($this->gidRequired && !empty($gid)) {
            global $db;
            $sql = 'SELECT TRUE FROM rosters INNER JOIN teams USING (tid) WHERE teams.lid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" AND rosters.gid = ? LIMIT 1';
            $gidCheck =& $db->getOne($sql, array($this->lid, $gid));

            if ($gidCheck) {
                $this->form->setElementError('gid'.$this->rid, $this->gidName .' already in use in this league.');
                return TRUE;
            }
        }
        return FALSE;
    }
}
