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

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    echo '<p style="color:red">This version of AcyMailing requires at least PHP 5.3.0, it is time to upgrade the PHP version of your server!</p>';
    exit;
}

function installAcym()
{
    try {
        include_once(rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acym'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
    } catch (Exception $e) {
        echo 'Initialization error, please re-install';

        return;
    }

    acym_increasePerf();

    $installClass = new acymInstall();
    $installClass->addPref();
    $installClass->updatePref();
    $installClass->updateSQL();

    $updateHelper = acym_get('helper.update');
    $updateHelper->fromLevel = $installClass->fromLevel;
    $updateHelper->fromVersion = $installClass->fromVersion;
    $updateHelper->installList();
    $updateHelper->installNotifications();
    $updateHelper->installFields();
    $updateHelper->installLanguages();
    $updateHelper->installBackLanguages();
    $updateHelper->addUpdateSite();
    $updateHelper->installBounceRules();
    $updateHelper->installAdminNotif();

    $newConfig = new stdClass();
    $newConfig->installcomplete = 1;
    $config = acym_config();
    $config->save($newConfig);
}

function uninstallAcym()
{
    $db = JFactory::getDBO();
    $jversion = preg_replace('#[^0-9\.]#i', '', JVERSION);
    $method = version_compare($jversion, '4.0.0', '>=') ? 'execute' : 'query';

    $db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` = 'mod_acym'");
    $db->$method();

    ?>
	AcyMailing successfully uninstalled.<br/>
	Its modules have been disabled.<br/><br/>
	If you want to completely uninstall AcyMailing and remove its data, please uninstall all the AcyMailing modules and plugins from the Joomla Extensions Manager then run the following query on your database manager:<br/><br/>
    <?php

    $tables = array(
        'action',
        'condition',
        'history',
        'rule',
        'user_has_field',
        'field',
        'url_click',
        'url',
        'user_stat',
        'mail_stat',
        'queue',
        'mail_has_list',
        'tag',
        'step',
        'automation',
        'user_has_list',
        'campaign',
        'list',
        'mail',
        'configuration',
        'user',
    );

    $prefix = $db->getPrefix().'acym_';
    echo 'DROP TABLE '.$prefix.implode(', '.$prefix, $tables).';';

    ?>
	<br/><br/>
	If you don't do this, you will be able to install AcyMailing again without losing your data.<br/>
	Please note that you don't have to uninstall AcyMailing to install a new version, simply install it over the current version.<br/><br/>
    <?php
}

if (!function_exists('com_install')) {
    function com_install()
    {
        return installAcym();
    }
}

if (!function_exists('com_uninstall')) {
    function com_uninstall()
    {
        return uninstallAcym();
    }
}

class com_acymInstallerScript
{
    function install($parent)
    {
        installAcym();
    }

    function update($parent)
    {
        installAcym();
    }

    function uninstall($parent)
    {
        return uninstallAcym();
    }

    function preflight($type, $parent)
    {
        return true;
    }

    function postflight($type, $parent)
    {
        return true;
    }
}

include_once(__DIR__.DIRECTORY_SEPARATOR.'install.class.php');
