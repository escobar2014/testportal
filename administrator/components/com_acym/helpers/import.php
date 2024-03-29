<?php
/**
 * @package	AcyMailing for Joomla
 * @version	6.1.5
 * @author	acyba.com
 * @copyright	(C) 2009-2019 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
?><?php

class acymimportHelper
{
    var $importUserInLists = array();
    var $totalInserted = 0;
    var $totalTry = 0;
    var $totalValid = 0;
    var $allSubid = array();
    var $db;
    var $dispatcher;
    var $forceconfirm = false;
    var $charsetConvert;
    var $generatename = true;
    var $overwrite = false;
    var $importblocked = false;
    var $removeSep = 0;
    var $dispresults = true;

    var $tablename = '';
    var $equFields = array();
    var $dbwhere = array(); //handle where on import via filter to only import new users for example

    var $subscribedUsers = array();


    public function __construct()
    {
        acym_increasePerf();

        global $acymCmsUserVars;
        $this->cmsUserVars = $acymCmsUserVars;
    }


    function file()
    {
        $importFile = acym_getVar('array', 'import_file', array(), 'files');

        if (empty($importFile['name'])) {
            acym_enqueueNotification(acym_translation('ACYM_PLEASE_BROWSE_FILE_IMPORT'), 'error', 7000);

            return false;
        }

        $extension = strtolower(acym_fileGetExt($importFile['name']));
        $config = acym_config();

        if (!preg_match('#^(csv)$#Ui', $extension) || preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)$#Ui', $importFile['name'])) {
            acym_enqueueNotification(acym_translation_sprintf('ACCEPTED_TYPE', acym_escape($extension), $config->get('allowed_files')), 'error');

            return false;
        }

        $fileError = $importFile['error'];
        if ($fileError > 0) {
            switch ($fileError) {
                case 1:
                case 2:
                    acym_enqueueNotification(acym_translation('ACYM_UPLOADED_FILE_EXCEED_MAX_FILESIZE_PHP'), 'error');

                    return false;
                case 3:
                    acym_enqueueNotification(acym_translation('ACYM_FILE_UPLOADED_PARTIALLY'), 'error');

                    return false;
                case 4:
                    acym_enqueueNotification(acym_translation('ACYM_NO_FILE_WAS_UPLOADED'), 'error');

                    return false;
                default:
                    acym_enqueueNotification(acym_translation_sprintf('ACYM_UNKNOWN_ERROR_UPLOADING_FILE', $fileError), 'error');

                    return false;
            }
        }

        $uploadPath = $this->_createUploadFolder();

        $attachment = new stdClass();
        $attachment->filename = uniqid('import_').'.csv';
        acym_setVar('filename', $attachment->filename);

        $attachment->size = $importFile['size'];

        if (!acym_uploadFile($importFile['tmp_name'], $uploadPath.$attachment->filename)) {
            if (!move_uploaded_file($importFile['tmp_name'], $uploadPath.$attachment->filename)) {
                acym_enqueueNotification(acym_translation_sprintf('ACYM_FAIL_UPLOAD', '<b><i>'.acym_escape($importFile['tmp_name']).'</i></b>', '<b><i>'.acym_escape($uploadPath.$attachment->filename).'</i></b>'), 'error');
            }
        }

        return true;
    }

    function textarea()
    {
        $content = acym_getVar('string', 'acym__users__import__from_text__textarea');
        $path = $this->_createUploadFolder();
        $filename = uniqid('import_').'.csv';

        acym_writeFile($path.$filename, $content);
        acym_setVar('filename', $filename);

        return true;
    }

    function cms()
    {
        $query = 'UPDATE IGNORE '.$this->cmsUserVars->table.' as b, #__acym_user as a SET a.email = b.'.$this->cmsUserVars->email.', a.name = b.'.$this->cmsUserVars->name.', a.active = 1 - b.'.$this->cmsUserVars->blocked.' WHERE a.cms_id = b.'.$this->cmsUserVars->id.' AND a.cms_id IS NOT NULL';
        $nbUpdated = acym_query($query);

        $query = 'UPDATE IGNORE '.$this->cmsUserVars->table.' as b, #__acym_user as a SET a.cms_id = b.'.$this->cmsUserVars->id.' WHERE a.email = b.'.$this->cmsUserVars->email;
        $affected = acym_query($query);
        $nbUpdated += intval($affected);

        acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_UPDATE', $nbUpdated), 'success', 7000);

        $query = 'SELECT a.id FROM #__acym_user as a LEFT JOIN '.$this->cmsUserVars->table.' as b on a.cms_id = b.'.$this->cmsUserVars->id.' WHERE b.'.$this->cmsUserVars->id.' IS NULL AND a.cms_id > 0';
        $deletedSubid = acym_loadResultArray($query);

        $query = 'SELECT a.id FROM #__acym_user as a LEFT JOIN '.$this->cmsUserVars->table.' as b on a.email = b.'.$this->cmsUserVars->email.' WHERE b.'.$this->cmsUserVars->id.' IS NULL AND a.cms_id > 0';
        $deletedSubid = array_merge(acym_loadResultArray($query), $deletedSubid);

        if (!empty($deletedSubid)) {
            $userClass = acym_get('class.user');
            $deletedUsers = $userClass->delete($deletedSubid);
            acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_DELETE', $deletedUsers), 'success', 7000);
        }


        $time = time();
        $query = 'INSERT IGNORE INTO #__acym_user (`name`,`email`,`creation_date`,`active`,`cms_id`, `source`) SELECT `'.$this->cmsUserVars->name.'`,`'.$this->cmsUserVars->email.'`,`'.$this->cmsUserVars->registered.'`,1 - '.$this->cmsUserVars->blocked.',`'.$this->cmsUserVars->id.'`,\'import_'.$time.'\' FROM '.$this->cmsUserVars->table;
        $insertedUsers = acym_query($query);

        acym_query('UPDATE #__acym_configuration SET `value` = '.intval($time).' WHERE `name` = \'last_import\'');

        acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_NEW', $insertedUsers), 'success', 7000);

        $lists = $this->getImportedLists();
        $listsSubscribe = array();
        if (!empty($lists)) {
            foreach ($lists as $listid => $val) {
                if (!empty($val)) {
                    $listsSubscribe[] = intval($listid);
                }
            }
        }

        if (empty($listsSubscribe)) {
            return true;
        }

        $query = 'INSERT IGNORE INTO #__acym_user_has_list (`user_id`,`list_id`,`status`,`subscription_date`) ';
        $query .= 'SELECT user.`id`, list.`id`, 1, '.acym_escapeDB(date('Y-m-d H:i:s', time())).' FROM #__acym_list AS list, #__acym_user AS user WHERE list.`id` IN ('.implode(',', $listsSubscribe).') AND user.`cms_id` > 0';
        $nbsubscribed = acym_query($query);
        acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_SUBSCRIPTION', $nbsubscribed), 5000);


        return true;
    }

    function database()
    {
        $this->forceconfirm = acym_getVar('int', 'import_confirmed_database');

        $table = trim(acym_getVar('string', 'tablename'));
        $time = time();

        if (empty($table)) {
            acym_enqueueNotification(acym_translation('ACYM_SPECIFYTABLE'), 'warning', 5000);

            return false;
        }

        $fields = acym_getColumns($table, false, false);
        if (empty($fields)) {
            acym_enqueueNotification(acym_translation('ACYM_SPECIFYTABLE'), 'warning', 5000);

            return false;
        }

        $equivalentFields = acym_getVar('array', 'fields', array());

        if (empty($equivalentFields['email'])) {
            acym_enqueueNotification(acym_translation('ACYM_SPECIFYFIELDEMAIL'), 'warning', 5000);

            return false;
        }

        $select = array();
        foreach ($equivalentFields as $acyField => $tableField) {
            $tableField = trim($tableField);
            if (empty($tableField)) {
                continue;
            }
            if (!in_array($tableField, $fields)) {
                acym_enqueueNotification(acym_translation_sprintf('ACYM_SPECIFYFIELD', $tableField, implode(' <br> ', $fields)), 'warning', 5000);

                return false;
            }
            $select['`'.acym_secureDBColumn($acyField).'`'] = acym_secureDBColumn($tableField);
        }

        if (empty($select['`creation_date`'])) {
            $select['`creation_date`'] = acym_escapeDB(acym_date('now', 'Y-m-d H:i:s'));
        }

        if ($this->forceconfirm && empty($select['`confirmed`'])) {
            $select['`confirmed`'] = 1;
        }

        $select['`source`'] = acym_escapeDB("import_".$time);

        $query = 'INSERT IGNORE INTO #__acym_user ('.implode(' , ', array_keys($select)).') SELECT '.implode(' , ', $select).' FROM '.acym_secureDBColumn($table).' WHERE '.acym_secureDBColumn($select['`email`']).' LIKE "%@%"';
        if (!empty($this->dbwhere)) {
            $query .= ' AND ( '.implode(' ) AND (', $this->dbwhere).' )';
        }

        $affectedRows = acym_query($query);

        acym_query('UPDATE #__acym_configuration SET `value` = '.intval($time).' WHERE `name` = "last_import"');

        acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_NEW', $affectedRows), "success", 5000);

        $lists = $this->getImportedLists();
        $listsSubscribe = array();
        if (!empty($lists)) {
            foreach ($lists as $listid => $val) {
                if (!empty($val)) {
                    $listsSubscribe[] = intval($listid);
                }
            }
        }

        if (empty($listsSubscribe)) {
            return true;
        }

        $query = 'INSERT IGNORE INTO #__acym_user_has_list (`user_id`,`list_id`,`status`,`subscription_date`) ';
        $query .= 'SELECT user.`id`, list.`id`, 1, '.acym_escapeDB(date('Y-m-d H:i:s', time())).' FROM #__acym_list AS list, #__acym_user AS user WHERE list.`id` IN ('.implode(',', $listsSubscribe).') AND user.`source` LIKE "%'.$time.'%"';
        $nbsubscribed = acym_query($query);
        acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_SUBSCRIPTION', $nbsubscribed), 5000);

        return true;
    }

    private function _createUploadFolder()
    {
        $folderPath = acym_cleanPath(ACYM_ROOT.trim(html_entity_decode(str_replace('/', DS, ACYM_MEDIA_FOLDER).DS.'import'))).DS;
        if (!is_dir($folderPath)) {
            acym_createDir($folderPath, true, true);
        }

        if (!is_writable($folderPath)) {
            @chmod($folderPath, '0755');
            if (!is_writable($folderPath)) {
                acym_enqueueNotification(acym_translation_sprintf('ACYM_WRITABLE_FOLDER', $folderPath), 'warning');
            }
        }

        return $folderPath;
    }

    function finalizeImport()
    {
        $filename = strtolower(acym_getVar('cmd', 'filename'));
        $extension = '.'.acym_fileGetExt($filename);
        $this->forceconfirm = acym_getVar('int', 'import_confirmed_generic');
        $this->generatename = acym_getVar('int', 'import_generate_generic');
        $this->overwrite = acym_getVar('int', 'import_overwrite_generic');
        $filename = str_replace(array('.', ' '), '_', substr($filename, 0, strpos($filename, $extension))).$extension;
        $uploadPath = ACYM_MEDIA.'import'.DS.$filename;

        if (!file_exists($uploadPath)) {
            acym_enqueueNotification(acym_translation('ACYM_UPLOADED_FILE_NOT_FOUND').' '.$uploadPath, 'error');

            return false;
        }

        $importColumns = acym_getVar('string', 'import_columns');
        if (empty($importColumns)) {
            acym_enqueueNotification(acym_translation('ACYM_COLUMNS_NOT_FOUND'), 'error');

            return false;
        }

        $contentFile = file_get_contents($uploadPath);

        if (acym_getVar('cmd', 'acyencoding', '') != '') {
            $encodingHelper = acym_get('helper.encoding');
            $contentFile = $encodingHelper->change($contentFile, acym_getVar('cmd', 'acyencoding'), 'UTF-8');
        }

        $cutContent = str_replace(array("\r\n", "\r"), "\n", $contentFile);
        $allLines = explode("\n", $cutContent);

        $listSeparators = array("\t", ';', ',');
        $separator = ',';
        foreach ($listSeparators as $sep) {
            if (strpos($allLines[0], $sep) !== false) {
                $separator = $sep;
                break;
            }
        }

        if (!empty($listsId)) {
            $allLines[0] .= $sep.'';
        }

        $importColumns = str_replace(',', $separator, $importColumns);

        if (strpos($allLines[0], '@')) {
            $contentFile = $importColumns."\n".$contentFile;
        } else {
            $allLines[0] = $importColumns;
            $contentFile = implode("\n", $allLines);
        }

        $this->_handleContent($contentFile);

        unlink($uploadPath);
        $this->_cleanImportFolder();
    }

    public function _handleContent(&$contentFile)
    {
        $success = true;
        $timestamp = time();

        $contentFile = str_replace(array("\r\n", "\r"), "\n", $contentFile);
        $importLines = explode("\n", $contentFile);

        $i = 0;
        $this->header = '';
        $this->allSubid = array();
        while (empty($this->header) && $i < 10) {
            $this->header = trim($importLines[$i]);
            $i++;
        }

        if (strpos($this->header, '@') && !strpos($this->header, ',') && !strpos($this->header, ';') && !strpos($this->header, "\t")) {
            $this->header = 'email';
            $i--;
        }

        if (!$this->_autoDetectHeader()) {
            acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_HEADER', acym_escape($this->header)), 'error');
            acym_enqueueNotification(acym_translation('ACYM_IMPORT_EMAIL'), 'error');

            return false;
        }

        $numberColumns = count($this->columns);

        $encodingHelper = acym_get('helper.encoding');

        $importUsers = array();

        $errorLines = array();

        $errorMessageInvalidEmails = "";

        $userClass = acym_get('class.user');

        $countUsersBeforeImport = $userClass->getCountTotalUsers();

        $listClass = acym_get('class.list');
        $allLists = $listClass->getAll('name');

        while (isset($importLines[$i])) {
            if (strpos($importLines[$i], '"') !== false) {
                $data = array();
                $j = $i + 1;
                $position = -1;

                while ($j < ($i + 30)) {

                    $quoteOpened = substr($importLines[$i], $position + 1, 1) == '"';

                    if ($quoteOpened) {
                        $nextQuotePosition = strpos($importLines[$i], '"', $position + 2);
                        while ($nextQuotePosition !== false && $nextQuotePosition + 1 != strlen($importLines[$i]) && substr($importLines[$i], $nextQuotePosition + 1, 1) != $this->separator) {
                            $nextQuotePosition = strpos($importLines[$i], '"', $nextQuotePosition + 1);
                        }
                        if ($nextQuotePosition === false) {
                            if (!isset($importLines[$j])) {
                                break;
                            }

                            $importLines[$i] .= "\n".$importLines[$j];
                            $importLines[$i] = rtrim($importLines[$i], $this->separator);
                            unset($importLines[$j]);
                            $j++;
                            continue;
                        } else {

                            if (strlen($importLines[$i]) - 1 == $nextQuotePosition) {
                                $data[] = substr($importLines[$i], $position + 1);
                                break;
                            }
                            $data[] = substr($importLines[$i], $position + 1, $nextQuotePosition + 1 - ($position + 1));
                            $position = $nextQuotePosition + 1;
                        }
                    } else {
                        $nextSeparatorPosition = strpos($importLines[$i], $this->separator, $position + 1);
                        if ($nextSeparatorPosition === false) {
                            $data[] = substr($importLines[$i], $position + 1);
                            break;
                        } else { // If found the next separator, add the value in $data and change the position
                            $data[] = substr($importLines[$i], $position + 1, $nextSeparatorPosition - ($position + 1));
                            $position = $nextSeparatorPosition;
                        }
                    }
                }

                $importLines = array_merge($importLines);
            } else {
                $data = explode($this->separator, rtrim(trim($importLines[$i]), $this->separator));
            }

            if (!empty($this->removeSep)) {
                for ($b = $numberColumns + $this->removeSep - 1; $b >= $numberColumns; $b--) {
                    if (isset($data[$b]) && (strlen($data[$b]) == 0 || $data[$b] == ' ')) {
                        unset($data[$b]);
                    }
                }
            }

            $i++;
            if (empty($importLines[$i - 1])) {
                continue;
            }

            $this->totalTry++;
            if (count($data) > $numberColumns) {
                $copy = $data;
                foreach ($copy as $oneelem => $oneval) {
                    if (!empty($oneval[0]) && $oneval[0] == '"' && $oneval[strlen($oneval) - 1] != '"' && isset($copy[$oneelem + 1]) && $copy[$oneelem + 1][strlen($copy[$oneelem + 1]) - 1] == '"') {
                        $data[$oneelem] = $copy[$oneelem].$this->separator.$copy[$oneelem + 1];
                        unset($data[$oneelem + 1]);
                    }
                }
                $data = array_values($data);
            }

            if (count($data) < $numberColumns) {
                for ($a = count($data); $a < $numberColumns; $a++) {
                    $data[$a] = '';
                }
            }

            if (count($data) != $numberColumns) {
                $success = false;
                static $errorcount = 0;
                if (empty($errorcount)) {
                    acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_ARGUMENTS', $numberColumns), 'warning');
                }
                $errorcount++;

                if ($this->totalTry == 1) {
                    return false;
                }
                if (empty($errorLines)) {
                    $errorLines[] = 'error,'.$importLines[0];
                }
                $errorLines[] = acym_translation('ACYM_IMPORT_ERROR_WRONG_NUMBER_ARGUMENTS').','.$importLines[$i - 1];
                continue;
            }

            $newUser = new stdClass();
            $newUser->customfields = array();

            $emailKey = array_search('email', $this->columns);
            $newUser->email = trim(strip_tags($data[$emailKey]), '\'" ');
            if (!empty($newUser->email)) {
                $newUser->email = acym_punycode($newUser->email);
            }
            $newUser->email = trim(str_replace(array(' ', "\t"), '', $encodingHelper->change($newUser->email, 'UTF-8', 'ISO-8859-1')));


            if (!acym_validEmail($newUser->email)) {
                $success = false;
                static $errorcountfail = 0;
                if ($errorcountfail == 0) {
                    acym_enqueueNotification(acym_translation('ACYM_ADDRESSES_INVALID'), 'warning');
                }
                $errorcountfail++;
                if (empty($errorLines)) {
                    $errorLines[] = 'error,'.$importLines[0];
                }
                $errorLines[] = acym_translation('ACYM_INVALID_EMAIL_ADDRESS').','.$importLines[$i - 1];
                continue;
            }

            foreach ($data as $num => $value) {
                if ($num == $emailKey) continue;

                $field = $this->columns[$num];

                if ($field == 1) continue;

                if ($field == 'listids') {
                    $liststosub = explode('-', trim($value, '\'" 	'));
                    foreach ($liststosub as $onelistid) {
                        $this->importUserInLists[intval(trim($onelistid))][] = acym_escapeDB($newUser->email);
                    }
                    continue;
                }

                if ($field == 'listname') {
                    $liststosub = explode('-', trim($value, '\'" 	'));
                    foreach ($liststosub as $onelistName) {
                        if (empty($onelistName)) {
                            continue;
                        }
                        $onelistName = trim($onelistName);
                        if (empty($allLists[$onelistName])) {
                            $newList = new stdClass();
                            $newList->name = $onelistName;
                            $newList->active = 1;
                            $colors = array('#3366ff', '#7240A4', '#7A157D', '#157D69', '#ECE649');
                            $newList->color = $colors[rand(0, count($colors) - 1)];
                            $listid = $listClass->save($newList);
                            $newList->listid = $listid;
                            $allLists[$onelistName] = $newList;
                        }
                        $this->importUserInLists[intval($allLists[$onelistName]->id)][] = acym_escapeDB($newUser->email);
                    }
                    continue;
                }

                if (strpos($field, 'cf_') === 0) {
                    $newUser->customfields[substr($field, 3)] = trim(strip_tags($value), '\'" 	');
                    continue;
                }

                if ($value == 'null') {
                    $newUser->$field = '';
                } else {
                    $newUser->$field = trim(strip_tags($value), '\'" 	');
                }
            }


            $importUsers[] = $newUser;
            $this->totalValid++;

            if ($this->totalValid % 50 == 0) {
                $this->_insertUsers($importUsers, $timestamp);
                $importUsers = array();
            }
        }

        if (!empty($errorLines)) {
            $filename = strtolower(acym_getVar('cmd', 'filename', ''));
            if (!empty($filename)) {
                $extension = '.'.acym_fileGetExt($filename);
                $filename = str_replace(array('.', ' '), '_', substr($filename, 0, strpos($filename, $extension))).$extension;
                $errorFile = implode("\n", $errorLines);
                acym_writeFile(ACYM_MEDIA.'import'.DS.'error_'.$filename, $errorFile);
                acym_enqueueNotification('<a target="_blank" href="'.acym_completeLink('users&task=downloadImport').'&filename=error_'.preg_replace('#\.[^.]*$#', '', $filename).'&'.acym_noTemplate().'" >'.acym_translation('ACYM_DOWNLOAD_IMPORT_ERRORS').'</a>', 'notice');
            }
        }

        $this->_insertUsers($importUsers, $timestamp);

        $countUsersAfterImport = $userClass->getCountTotalUsers();
        $this->totalInserted = $countUsersAfterImport - $countUsersBeforeImport;

        if ($this->dispresults) {
            acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_REPORT', $this->totalTry, $this->totalInserted, $this->totalTry - $this->totalValid, $this->totalValid - $this->totalInserted));
        }

        $this->_subscribeUsers();

        return $success;
    }

    function _insertUsers($users, $timestamp)
    {
        if (empty($users)) {
            return true;
        }

        $importedCols = array_keys(get_object_vars($users[0]));
        unset($importedCols[array_search('customfields', $importedCols)]);
        if ($this->forceconfirm) $importedCols[] = 'confirmed';

        foreach ($users as $a => $oneUser) {
            $this->_checkData($users[$a], $timestamp);
        }

        $columns = reset($users);
        $colNames = array_keys(get_object_vars($columns));
        unset($colNames[array_search('customfields', $colNames)]);

        if (!in_array('key', $colNames)) $colNames[] = 'key';

        foreach ($colNames as $oneColumn) {
            acym_secureDBColumn($oneColumn);
        }

        $queryInsertUsers = 'INSERT'.($this->overwrite ? '' : ' IGNORE').' INTO #__acym_user (`'.implode('`,`', $colNames).'`) VALUES (';
        $values = array();
        $customFieldsvalues = array();
        $allemails = array();
        foreach ($users as $a => $oneUser) {
            $value = array();

            acym_trigger('onAcymBeforeUserImport', array(&$oneUser));
            foreach ($oneUser as $map => $oneValue) {
                if ($map == 'customfields') continue;

                $oneValue = htmlspecialchars_decode($oneValue, ENT_QUOTES);

                if ($map == 'active' && !empty($this->importblocked) && $this->importblocked == true) {
                    $value[] = 0;
                } else {
                    if ($map != 'id') {
                        $oneValue = acym_escapeDB($oneValue);
                        if ($map == 'email') $allemails[] = $oneValue;
                    }else{
                        $oneValue = intval($oneValue);
                    }

                    $value[] = $oneValue;
                }
            }

            if (!isset($oneUser->key)) $value[] = acym_escapeDB(acym_generateKey(14));
            $values[] = implode(',', $value);

            if (!empty($oneUser->customfields)) $customFieldsvalues[$oneUser->email] = $oneUser->customfields;
        }

        $queryInsertUsers .= implode('),(', $values).')';

        if ($this->overwrite) {
            $queryInsertUsers .= ' ON DUPLICATE KEY UPDATE ';
            foreach ($importedCols as &$oneColumn) {
                acym_secureDBColumn($oneColumn);
                if ($oneColumn == 'key') {
                    $oneColumn = '`'.$oneColumn.'` = `'.$oneColumn.'`';
                } else {
                    $oneColumn = '`'.$oneColumn.'` = VALUES(`'.$oneColumn.'`)';
                }
            }
            $queryInsertUsers .= implode(',', $importedCols);
        }

        acym_query($queryInsertUsers);

        acym_query('UPDATE #__acym_configuration SET `value` = '.intval($timestamp).' WHERE `name` = \'last_import\'');

        $importedUsers = acym_loadObjectList('SELECT id, email FROM #__acym_user WHERE email IN ('.implode(',', $allemails).')', 'id');

        if (!empty($customFieldsvalues)) {
            $insertValues = array();
            foreach ($importedUsers as $one) {
                if (empty($customFieldsvalues[$one->email])) continue;

                foreach ($customFieldsvalues[$one->email] as $fieldId => $value) {
                    $value = htmlspecialchars_decode($value, ENT_QUOTES);
                    $insertValues[] = '('.intval($one->id).','.intval($fieldId).','.acym_escapeDB($value).')';
                }
            }

            if (!empty($insertValues)) {
                $queryInsertCustomFields = 'INSERT'.($this->overwrite ? '' : ' IGNORE').' INTO #__acym_user_has_field (`user_id`, `field_id`, `value`) VALUES '.implode(',', $insertValues);
                if ($this->overwrite) $queryInsertCustomFields .= ' ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)';
                acym_query($queryInsertCustomFields);
            }
        }


        $this->allSubid = array_merge($this->allSubid, array_keys($importedUsers));

        return true;
    }

    function _checkData(&$user, $timestamp)
    {
        if (empty($user->creation_date)) {
            $user->creation_date = time();
        }

        if (is_numeric($user->creation_date)) {
            $user->creation_date = date('Y-m-d H:i:s', $user->creation_date);
        }

        if (!isset($user->active) || strlen($user->active) == 0) {
            $user->active = 1;
        }

        if ((!isset($user->confirmed) || strlen($user->confirmed) == 0) && $this->forceconfirm) {
            $user->confirmed = 1;
        }

        if (empty($user->source)) {
            $user->source = 'import_'.$timestamp;
        }

        if (empty($user->name) && $this->generatename) {
            $user->name = ucwords(trim(str_replace(array('.', '_', '-', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0), ' ', substr($user->email, 0, strpos($user->email, '@')))));
        }
    }

    function _autoDetectHeader()
    {
        $this->separator = ',';

        $this->header = str_replace("\xEF\xBB\xBF", "", $this->header);

        $listSeparators = array("\t", ';', ',');
        foreach ($listSeparators as $sep) {
            if (strpos($this->header, $sep) !== false) {
                $this->separator = $sep;
                break;
            }
        }


        $this->columns = explode($this->separator, $this->header);

        for ($i = count($this->columns) - 1; $i >= 0; $i--) {
            if (strlen($this->columns[$i]) == 0) {
                unset($this->columns[$i]);
                $this->removeSep++;
            }
        }

        $columns = acym_getColumns('user');
        foreach ($columns as $i => $oneColumn) {
            $columns[$i] = strtolower($oneColumn);
        }

        foreach ($this->columns as $i => $oneColumn) {
            $this->columns[$i] = strtolower(trim($oneColumn, '\'" '));
            if (in_array($this->columns[$i], array('listids', 'listname'))) continue;
            if (strpos($this->columns[$i], 'cf_') === 0) continue;

            if (!in_array($this->columns[$i], $columns) && $this->columns[$i] != 1) {
                acym_enqueueNotification(acym_translation_sprintf('ACYM_IMPORT_ERROR_FIELD', '<b>'.acym_escape($this->columns[$i]).'</b>', '<b>'.implode('</b> | <b>', array_diff($columns, array('id', 'cms_id'))).'</b>'), 'error');

                return false;
            }
        }

        if (!in_array('email', $this->columns)) {
            return false;
        }

        return true;
    }

    public function _cleanImportFolder()
    {

        $files = acym_getFiles(ACYM_MEDIA.'import', '.', false, true, array());
        foreach ($files as $oneFile) {
            if (acym_fileGetExt($oneFile) != 'csv') {
                continue;
            }
            if (filectime($oneFile) < time() - 86400) {
                unlink($oneFile);
            }
        }
    }

    function getImportedLists()
    {
        $listClass = acym_get('class.list');
        $listsId = json_decode(acym_getVar('string', 'lists_selected'));
        $newListName = acym_getVar('string', 'new_list');

        if (empty($listsId) && empty($newListName)) {
            return false;
        }

        $lists = array();

        if (!empty($newListName)) {
            $newList = new stdClass();
            $newList->name = $newListName;
            $newList->active = 1;
            $colors = '#'.substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            $newList->color = $colors;
            $listid = $listClass->save($newList);
            $lists[$listid] = 1;
        }

        if (!empty($listsId)) {
            foreach ($listsId as $id) {
                $lists[$id] = 1;
            }
        }

        if (!empty($lists)) {
            return $lists;
        } else {
            return false;
        }
    }

    function _subscribeUsers()
    {


        if (empty($this->allSubid)) {
            return true;
        }

        $subdate = date('Y-m-d H:i:s', time());

        $listClass = acym_get('class.list');
        $lists = $this->getImportedLists();

        if (!empty($this->importUserInLists)) {
            foreach ($this->importUserInLists as $listid => $arrayEmails) {
                if (empty($listid)) {
                    continue;
                }

                $listid = (int)$listid;
                $query = 'INSERT IGNORE INTO #__acym_user_has_list (`list_id`,`user_id`,`subscription_date`,`status`) ';
                $query .= 'SELECT '.intval($listid).',`id`,'.acym_escapeDB($subdate).',1 FROM #__acym_user WHERE `email` IN (';
                $query .= implode(',', $arrayEmails).')';
                $nbsubscribed = acym_query($query);
                $nbsubscribed = intval($nbsubscribed);

                if (isset($this->subscribedUsers[$listid])) {
                    $this->subscribedUsers[$listid]->nbusers += $nbsubscribed;
                } else {
                    $myList = $listClass->getOneById($listid);
                    $this->subscribedUsers[$listid] = $myList;
                    $this->subscribedUsers[$listid]->nbusers = $nbsubscribed;
                }
            }
        }

        if (!empty($lists)) {

            foreach ($lists as $listid => $val) {
                if (empty($val)) {
                    continue;
                }

                if ($val == -1) {
                    $dateColumn = 'unsubscribe_date';
                    $status = -1;
                } else {
                    $dateColumn = 'subscription_date';
                    $status = 1;
                }

                $nbsubscribed = 0;
                $listid = intval($listid);
                $query = 'INSERT IGNORE INTO #__acym_user_has_list (`list_id`,`user_id`,`'.$dateColumn.'`,`status`) VALUES ';
                $b = 0;
                $currentSubids = array();
                foreach ($this->allSubid as $subid) {
                    $subid = intval($subid);
                    $currentSubids[] = $subid;
                    $b++;

                    if ($b > 200) {
                        $query = rtrim($query, ',');
                        if ($val == -1) {
                            $query .= ' ON DUPLICATE KEY UPDATE status = -1';
                            $nbsubscribed = -acym_loadResult('SELECT COUNT(*) FROM #__acym_listsub WHERE `list_id` = '.intval($listid).' AND status != -1 AND `user_id` IN ('.implode(',', $currentSubids).')');
                        }
                        $affected = acym_query($query);
                        $nbsubscribed += intval($affected);
                        $b = 0;
                        $currentSubids = array();
                        $query = 'INSERT IGNORE INTO #__acym_user_has_list (`list_id`,`user_id`,`'.$dateColumn.'`,`status`) VALUES ';
                    }

                    $query .= '('.intval($listid).','.intval($subid).','.acym_escapeDB($subdate).','.$status.'),';
                }
                $query = rtrim($query, ',');
                if ($val == -1) {
                    $query .= ' ON DUPLICATE KEY UPDATE status = -1';
                    if (!empty($currentSubids)) {
                        $nbsubscribed = -acym_loadResult('SELECT COUNT(*) FROM #__acym_listsub WHERE `list_id` = '.intval($listid).' AND status != -1 AND `user_id` IN ('.implode(',', $currentSubids).')');
                    }
                }

                $affected = acym_query($query);
                $nbsubscribed += intval($affected);

                if (isset($this->subscribedUsers[$listid])) {
                    $this->subscribedUsers[$listid]->nbusers += $nbsubscribed;
                } else {
                    $myList = $listClass->getOneById($listid);
                    $myList->status = $val;
                    $this->subscribedUsers[$listid] = $myList;
                    $this->subscribedUsers[$listid]->nbusers = $nbsubscribed;
                }
            }
        }

        return true;
    }
}
