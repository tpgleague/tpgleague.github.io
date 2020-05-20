<?php

class updateRecord
{
    public $formData = array();
    public $uid;
    public $table;
    public $tablePk;
    public $tablePkId;

    function __construct($table, $tablePk, $tablePkId=NULL)
    {
        $this->uid = UID;
        $this->table = $table;
        $this->tablePk = $tablePk;
        $this->tablePkId = $tablePkId;
    }

    function addData($field, $value=NULL)
    {
        if (is_array($field)) {
            foreach ($field as $key => $data) {
                $this->formData[$key] = $data;
            }
        } else {
            $this->formData[$field] = $value;
        }
    }

    function setUID($uid)
    {
        $this->uid = $uid;
    }

    function updateData()
    {
        global $db;

        if (empty($this->tablePkId)) $this->tablePkId = $this->formData[$this->tablePk];
        if (!checkNumber($this->tablePkId)) return FALSE;

        if (isset($this->formData['submit'])) unset($this->formData['submit']);

        $sql = 'SELECT ' . sqlSelect(array_keys($this->formData)) . ' FROM ' . $db->quoteIdentifier($this->table) . ' WHERE ' . $db->quoteIdentifier($this->tablePk) . ' = ' . $db->quoteSmart($this->tablePkId);
        $oldData =& $db->getRow($sql);

        foreach ($this->formData as $field => $value) {
            if (($value != $oldData[$field]) && ($field != 'submit')) {
                $newData[$field] = $value;
            }
        }

        if (!isset($newData)) return FALSE;

        foreach ($newData as $field => $value) {
            if ($field == 'password') {
                $value = '[secret]';
                $oldData['password'] = '[secret]';
            }
            if (!in_array($field, array('rid', 'modify_date_gmt', 'create_date_gmt', 'timestamp_gmt', 'email_validation_key'))) {
                $logData[] = array($this->table,
                                   $this->tablePk,
                                   $this->tablePkId,
                                   $field,
                                   $oldData[$field],
                                   $value,
                                   $this->uid,
                                   'update'
                                  );
            }
        }

        if (!empty($newData)) {
            $res =& $db->autoExecute($this->table, $newData, DB_AUTOQUERY_UPDATE, $db->quoteIdentifier($this->tablePk) . ' = ' . $db->quoteSmart($this->tablePkId));
            if (!empty($logData)) {
                $sth = $db->prepare('INSERT INTO users_action_log (tablename, tablePk, tablePkId, field, from_value, to_value, uid, `type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', DB_AUTOQUERY_INSERT);
                $res =& $db->executeMultiple($sth, $logData);
            }
        }

        return TRUE;
    }

}

class InsertRecord
{
    public $uid;
    public $table;
    public $tablePk;
    public $tablePkId;
    public $lastInsertId;

    function insertData($table, $formData)
    {
        global $db;
        $this->uid = UID;
        $this->table = $table;

        $this->tablePk =& $db->getOne('SHOW COLUMNS FROM ' . $db->quoteIdentifier($this->table));

        $res = $db->autoExecute($this->table, $formData, DB_AUTOQUERY_INSERT);
        $this->tablePkId =& $db->getOne('SELECT LAST_INSERT_ID()');

        if (checkNumber($this->tablePkId)) {
            $insertionArray = array(
                                    $this->table,
                                    $this->tablePk,
                                    $this->tablePkId,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $this->uid,
                                    'insert'
                                   );
            $sth = $db->prepare('INSERT INTO users_action_log (tablename, tablePk, tablePkId, field, from_value, to_value, uid, `type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', DB_AUTOQUERY_INSERT);
            $res =& $db->execute($sth, $insertionArray);
            $db->freePrepared($sth);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function lastInsertId()
    {
        return $this->tablePkId;
    }
}