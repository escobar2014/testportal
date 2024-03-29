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

class DashboardController extends acymController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listing()
    {
        acym_setVar('layout', 'listing');
        $config = acym_config();

        if ($config->get('migration') == 0 && acym_existsAcyMailing59()) {

            acym_setVar("layout", "migrate");

            parent::display();

            return;
        }

        $newConfig = new stdClass();
        $newConfig->migration = '1';
        $config->save($newConfig);

        if ($config->get('walk_through') == 1) {
            $step = acym_getVar('int', 'step');
            $task = acym_getVar('string', 'task');
            if (empty($step)) acym_setVar('step', '1');
            if (empty($task)) acym_setVar('task', 'walkThrough');
            $this->walkThrough();

            return;
        }

        $data = array();
        $campaignClass = acym_get('class.campaign');
        $mailStatsClass = acym_get('class.mailstat');
        $urlClickClass = acym_get('class.urlclick');
        $mails = $mailStatsClass->getAllMailsForStats();
        $data['campaignsScheduled'] = $campaignClass->getCampaignForDashboard();
        $data['dashboard_stats'] = true;

        if (empty($mails)) {
            $data['emptyGlobal'] = 'campaigns';
            parent::display($data);

            return;
        }

        $data['mails'] = array();

        foreach ($mails as $mail) {
            if (empty($mail->name) || (empty($mail->id) && $mail->sent != 1)) continue;

            $newMail = new stdClass();
            $newMail->name = $mail->name;
            $newMail->value = $mail->id;
            $data['mails'][] = $newMail;
        }

        $data['selectedMailid'] = empty($selectedMail) ? '' : $selectedMail;

        $statsMailSelected = $mailStatsClass->getOneByMailId($data['selectedMailid']);

        if (empty($statsMailSelected)) {
            $data['emptyGlobal'] = empty($data['selectedMailid']) ? 'campaigns' : 'stats';
        }

        if (empty($statsMailSelected->sent)) {
            $data['emptyGlobal'] = 'stats';
        }

        $statsMailSelected->totalMail = $statsMailSelected->sent + $statsMailSelected->fail;
        $statsMailSelected->pourcentageSent = empty($statsMailSelected->totalMail) ? 0 : intval(($statsMailSelected->sent * 100) / $statsMailSelected->totalMail);
        $statsMailSelected->allSent = empty($statsMailSelected->totalMail) ? acym_translation_sprintf('ACYM_X_MAIL_SUCCESSFULLY_SENT_OF_X', 0, 0) : acym_translation_sprintf('ACYM_X_MAIL_SUCCESSFULLY_SENT_OF_X', $statsMailSelected->sent, $statsMailSelected->totalMail);

        $openRateCampaign = empty($data['selectedMailid']) ? $campaignClass->getOpenRateAllCampaign() : $campaignClass->getOpenRateOneCampaign($data['selectedMailid']);
        $statsMailSelected->pourcentageOpen = empty($openRateCampaign->sent) ? 0 : intval(($openRateCampaign->open_unique * 100) / $openRateCampaign->sent);
        $statsMailSelected->allOpen = empty($openRateCampaign->sent) ? acym_translation_sprintf('ACYM_X_MAIL_OPENED_OF_X', 0, 0) : acym_translation_sprintf('ACYM_X_MAIL_OPENED_OF_X', $openRateCampaign->open_unique, $openRateCampaign->sent);

        $clickRateCampaign = $urlClickClass->getClickRate($data['selectedMailid']);
        $statsMailSelected->pourcentageClick = empty($statsMailSelected->sent) ? 0 : intval(($clickRateCampaign->click * 100) / $statsMailSelected->sent);
        $statsMailSelected->allClick = empty($statsMailSelected->sent) ? acym_translation_sprintf('ACYM_X_MAIL_CLICKED_OF_X', 0, 0) : acym_translation_sprintf('ACYM_X_MAIL_CLICKED_OF_X', $clickRateCampaign->click, $statsMailSelected->sent);

        $bounceRateCampaign = empty($data['selectedMailid']) ? $campaignClass->getBounceRateAllCampaign() : $campaignClass->getBounceRateOneCampaign($data['selectedMailid']);
        $statsMailSelected->pourcentageBounce = empty($statsMailSelected->sent) ? 0 : intval(($bounceRateCampaign->bounce_unique * 100) / $statsMailSelected->sent);
        $statsMailSelected->allBounce = empty($statsMailSelected->sent) ? acym_translation_sprintf('ACYM_X_BOUNCE_OF_X', 0, 0) : acym_translation_sprintf('ACYM_X_BOUNCE_OF_X', $bounceRateCampaign->bounce_unique, $statsMailSelected->sent);


        $campaignOpenByMonth = $campaignClass->getOpenByMonth($data['selectedMailid']);
        $campaignOpenByDay = $campaignClass->getOpenByDay($data['selectedMailid']);
        $campaignOpenByHour = $campaignClass->getOpenByHour($data['selectedMailid']);

        $campaignClickByMonth = $urlClickClass->getAllClickByMailMonth($data['selectedMailid']);
        $campaignClickByDay = $urlClickClass->getAllClickByMailDay($data['selectedMailid']);
        $campaignClickByHour = $urlClickClass->getAllClickByMailHour($data['selectedMailid']);

        if (empty($campaignOpenByMonth) || empty($campaignOpenByDay) || empty($campaignOpenByHour)) {
            $statsMailSelected->empty = true;
            $data['stats_mail_1'] = $statsMailSelected;

            parent::display($data);

            return;
        }

        #To get all the month between the first open date and the last
        $begin = new DateTime(empty($campaignClickByMonth) ? $campaignOpenByMonth[0]->open_date : min(array($campaignOpenByMonth[0]->open_date, $campaignClickByMonth[0]->date_click)));
        $end = new DateTime(empty($campaignClickByMonth) ? end($campaignOpenByMonth)->open_date : max(array(end($campaignOpenByMonth)->open_date, end($campaignClickByMonth)->date_click)));

        $end->modify('+1 day');

        $interval = new DateInterval('P1M');
        $daterange = new DatePeriod($begin, $interval, $end);

        $rangeMonth = array();

        foreach ($daterange as $date) {
            $rangeMonth[] = acym_getTime($date->format('Y-m-d H:i:s'));
        }

        #To get all the day between the first open date and the last
        $begin = new DateTime(empty($campaignClickByDay) ? $campaignOpenByDay[0]->open_date : min(array($campaignOpenByDay[0]->open_date, $campaignClickByDay[0]->date_click)));
        $end = new DateTime(empty($campaignClickByDay) ? end($campaignOpenByDay)->open_date : max(array(end($campaignOpenByDay)->open_date, end($campaignClickByDay)->date_click)));

        $end->modify('+1 hour');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);

        $rangeDay = array();

        foreach ($daterange as $date) {
            $rangeDay[] = acym_getTime($date->format('Y-m-d H:i:s'));
        }


        #To get all the hour between the first open date and the last
        $begin = new DateTime(empty($campaignClickByHour) ? $campaignOpenByHour[0]->open_date : min(array($campaignOpenByHour[0]->open_date, $campaignClickByHour[0]->date_click)));
        $end = new DateTime(empty($campaignClickByHour) ? end($campaignOpenByHour)->open_date : max(array(end($campaignOpenByHour)->open_date, end($campaignClickByHour)->date_click)));

        $end->modify('+1 min');

        $interval = new DateInterval('PT1H');
        $daterange = new DatePeriod($begin, $interval, $end);

        $rangeHour = array();

        foreach ($daterange as $date) {
            $rangeHour[] = acym_getTime($date->format('Y-m-d H:i:s'));
        }

        $openMonthArray = array();
        $openDayArray = array();
        $openHourArray = array();

        foreach ($campaignOpenByMonth as $one) {
            $openMonthArray[acym_date(acym_getTime($one->open_date), 'M Y')] = $one->open;
        }

        foreach ($campaignOpenByDay as $one) {
            $openDayArray[acym_date(acym_getTime($one->open_date), 'd M Y')] = $one->open;
        }

        foreach ($campaignOpenByHour as $one) {
            $openHourArray[acym_date(acym_getTime($one->open_date), 'd M Y H')] = $one->open;
        }

        $clickMonthArray = array();
        $clickDayArray = array();
        $clickHourArray = array();

        foreach ($campaignClickByMonth as $one) {
            $clickMonthArray[acym_date(acym_getTime($one->date_click), 'M Y')] = $one->click;
        }

        foreach ($campaignClickByDay as $one) {
            $clickDayArray[acym_date(acym_getTime($one->date_click), 'd M Y')] = $one->click;
        }

        foreach ($campaignClickByHour as $one) {
            $clickHourArray[acym_date(acym_getTime($one->date_click), 'd M Y H')] = $one->click;
        }

        $statsMailSelected->month = array();
        foreach ($rangeMonth as $one) {
            $one = acym_date($one, 'M Y');
            $currentMonth = array();
            $currentMonth['open'] = empty($openMonthArray[$one]) ? 0 : $openMonthArray[$one];
            $currentMonth['click'] = empty($clickMonthArray[$one]) ? 0 : $clickMonthArray[$one];
            $statsMailSelected->month[$one] = $currentMonth;
        }

        $statsMailSelected->day = array();
        foreach ($rangeDay as $one) {
            $one = acym_date($one, 'd M Y');
            $currentDay = array();
            $currentDay['open'] = empty($openDayArray[$one]) ? 0 : $openDayArray[$one];
            $currentDay['click'] = empty($clickDayArray[$one]) ? 0 : $clickDayArray[$one];
            $statsMailSelected->day[$one] = $currentDay;
        }

        $statsMailSelected->hour = array();
        foreach ($rangeHour as $one) {
            $one = acym_date($one, 'd M Y H');
            $currentHour = array();
            $currentHour['open'] = empty($openHourArray[$one]) ? 0 : $openHourArray[$one];
            $currentHour['click'] = empty($clickHourArray[$one]) ? 0 : $clickHourArray[$one];
            $statsMailSelected->hour[$one.':00'] = $currentHour;
        }
        $data['stats_mail_1'] = $statsMailSelected;

        parent::display($data);
    }

    public function walkThrough()
    {
        $step = acym_getVar('int', 'step');
        $config = acym_config();
        $data = array();
        $data['step'] = $step;

        $data['from_email'] = $config->get('from_email');
        $data['from_name'] = $config->get('from_name');
        $data['bounce_email'] = $config->get('bounce_email');
        $data['from_as_replyto'] = $config->get('from_as_replyto');
        if ($config->get('from_as_replyto') == 1) {
            $data['replyto_email'] = $config->get('from_email');
            $data['replyto_name'] = $config->get('from_name');
        } else {
            $data['replyto_email'] = $config->get('replyto_email');
            $data['replyto_name'] = $config->get('replyto_name');
        }

        $data['mailer_method'] = $config->get('mailer_method');
        if (!empty($data['mailer_method'])) {
            if ($data['mailer_method'] == 'phpmail' || $data['mailer_method'] == 'qmail' || $data['mailer_method'] == 'sendmail') {
                $data['use_server'] = true;
            } elseif ($data['mailer_method'] == 'mail') {
                $data['use_server'] = true;
                $data['mailer_method'] = 'phpmail';
            } else {
                $data['use_server'] = false;
            }
        } else {
            $data['use_server'] = true;
            $data['mailer_method'] = 'phpmail';
        }
        $data['smtp_auth'] = $config->get('smtp_auth');
        $data['smtp_host'] = $config->get('smtp_host');
        $data['smtp_keepalive'] = $config->get('smtp_keepalive');
        $data['smtp_password'] = $config->get('smtp_password');
        $data['smtp_port'] = $config->get('smtp_port');
        $data['smtp_secured'] = $config->get('smtp_secured');
        $data['smtp_username'] = $config->get('smtp_username');
        $data['elasticemail_username'] = $config->get('elasticemail_username');
        $data['elasticemail_password'] = $config->get('elasticemail_password');
        $data['elasticemail_port'] = $config->get('elasticemail_port');

        $data['special_chars'] = $config->get('special_chars');
        $data['encoding_format'] = $config->get('encoding_format');
        $data['charset'] = $config->get('charset');
        $data['use_https'] = $config->get('use_https');
        $data['embed_images'] = $config->get('embed_images');
        $data['embed_files'] = $config->get('embed_files');

        $data['small_display'] = $config->get('small_display', 0);


        acym_setVar('layout', 'walk_through');
        parent::display($data);
    }

    public function passWalkThrough()
    {
        $newConfig = new stdClass();
        $config = acym_config();
        $newConfig->walk_through = 0;

        if ($config->get('templates_installed') == 0) {
            $updateHelper = acym_get('helper.update');
            $updateHelper->installTemplate();
            $newConfig->templates_installed = 1;
        }

        $config->save($newConfig);
        $this->listing();
    }

    public function step1()
    {
        $information = acym_getVar('array', 'information');
        $forReplyTo = acym_getVar('string', 'use_for_reply_to');
        $newConfig = new stdClass();
        $config = acym_config();

        $newConfig->from_name = $information['from_name'];
        $newConfig->from_email = $information['from_email'];
        if ($forReplyTo == 'on') {
            $newConfig->from_as_replyto = 1;
        } else {
            $newConfig->from_as_replyto = 0;
            $newConfig->replyto_name = $information['reply_to_name'];
            $newConfig->replyto_email = $information['reply_to_email'];
        }
        $newConfig->bounce_email = $information['bounce_email'];

        $config->save($newConfig);
        $this->walkThrough();
    }

    public function step2()
    {
        $mailerMethod = acym_getVar('string', 'mailer_method');
        $newConfig = new stdClass();
        $config = acym_config();

        if (empty($mailerMethod)) {
            return;
        }
        $newConfig->mailer_method = $mailerMethod;

        if ($mailerMethod == "smtp") {
            $smtpInfos = acym_getVar('array', 'smtp');
            $newConfig->smtp_auth = $smtpInfos['auth'];
            $newConfig->smtp_host = $smtpInfos['server'];
            $newConfig->smtp_keepalive = $smtpInfos['keepalive'];
            $newConfig->smtp_password = $smtpInfos['password'];
            $newConfig->smtp_port = $smtpInfos['port'];
            $newConfig->smtp_secured = $smtpInfos['secure'];
            $newConfig->smtp_username = $smtpInfos['username'];
        } elseif ($mailerMethod = "elasticemail") {
            $elasticInfos = acym_getVar('array', 'elastic');
            $newConfig->elasticemail_username = $elasticInfos['username'];
            $newConfig->elasticemail_password = $elasticInfos['password'];
            $newConfig->elasticemail_port = $elasticInfos['port'];
        }

        $config->save($newConfig);
        $this->walkThrough();
    }

    public function step3()
    {
        $serverConfig = acym_getVar('array', 'config');
        $newConfig = new stdClass();
        $config = acym_config();

        $newConfig->special_chars = empty($serverConfig['special_char']) ? 0 : $serverConfig['special_char'];
        $newConfig->encoding_format = $serverConfig['encoding_format'];
        $newConfig->charset = $serverConfig['charset'];
        $newConfig->use_https = empty($serverConfig['https']) ? 0 : $serverConfig['https'];
        $newConfig->embed_images = empty($serverConfig['images']) ? 0 : $serverConfig['images'];
        $newConfig->embed_files = empty($serverConfig['attachments']) ? 0 : $serverConfig['attachments'];

        $config->save($newConfig);
        $this->walkThrough();
    }

    public function step4()
    {
        $config = acym_config();
        $newConfig = new stdClass();
        $serverConfig = acym_getVar('array', 'interface');

        $newConfig->small_display = empty($serverConfig['small_display']) ? 0 : $serverConfig['small_display'];

        $config->save($newConfig);
        $this->passWalkThrough();
    }

    public function preMigration()
    {
        $elementToMigrate = acym_getVar("string", "element");
        $helperMigration = acym_get('helper.migration');

        $result = $helperMigration->preMigration($elementToMigrate);

        if (!empty($result["isOk"])) {
            echo $result["count"];
        } else {
            echo "ERROR : ";
            if (!empty($result["errorInsert"])) {
                echo strtoupper(acym_translation("ACYM_INSERT_ERROR"));
            }
            if (!empty($result["errorClean"])) {
                echo strtoupper(acym_translation("ACYM_CLEAN_ERROR"));
            }

            if (!empty($result["errors"])) {
                echo "<br>";

                foreach ($result["errors"] as $key => $oneError) {
                    echo "<br>".$key." : ".$oneError;
                }
            }
        }
        exit;
    }

    public function migrate()
    {
        $elementToMigrate = acym_getVar("string", "element");
        $helperMigration = acym_get('helper.migration');
        $functionName = "do".ucfirst($elementToMigrate)."Migration";

        $result = $helperMigration->$functionName($elementToMigrate);

        if (!empty($result["isOk"])) {
            echo json_encode($result);
        } else {
            echo "ERROR : ";
            if (!empty($result["errorInsert"])) {
                echo strtoupper(acym_translation("ACYM_INSERT_ERROR"));
            }
            if (!empty($result["errorClean"])) {
                echo strtoupper(acym_translation("ACYM_CLEAN_ERROR"));
            }

            if (!empty($result["errors"])) {
                echo "<br>";

                foreach ($result["errors"] as $key => $oneError) {
                    echo "<br>".$key." : ".$oneError;
                }
            }
        }
        exit;
    }

    public function migrationDone()
    {
        $config = acym_config();

        $newConfig = new stdClass();
        $newConfig->migration = "1";
        $config->save($newConfig);

        $updateHelper = acym_get('helper.update');
        $updateHelper->installNotifications();

        $this->listing();
    }

    private function acym_existsAcyMailing59()
    {
        $allTables = acym_getTables();

        if (in_array(acym_getPrefix().'acymailing_config', $allTables)) {
            $queryVersion = 'SELECT `value` FROM #__acymailing_config WHERE `namekey` LIKE "version"';

            $version = acym_loadResult($queryVersion);

            if (version_compare($version, '5.9.0') >= 0) {
                return true;
            }
        }

        return false;
    }

    public function upgrade()
    {
        acym_setVar('layout', 'upgrade');

        $version = acym_getVar('string', 'version', 'enterprise');

        $data = array('version' => $version);

        parent::display($data);
    }
}
