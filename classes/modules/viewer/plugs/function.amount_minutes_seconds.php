<?php

/**
 * Плагин для смарти.
 * Позволяет получить количество минут и секунд (с склонением) по количеству секунд.
 *
 * Список ключей параметров:
 *        seconds          [string]
 *        only_minute*        [bool]
 *        round_minute*   [bool]
 *        lang*   [string]
 * (* - параметр является необязательным)
 *
 * @param   array $aParams
 * @param   Smarty $oSmarty
 * @return  string
 */
function smarty_function_amount_minutes_seconds($aParams, &$oSmarty)
{
    require_once(Config::Get('path.root.engine') . '/classes/Engine.class.php');
    $oEngine = Engine::getInstance();

    $iSecondsParam = (empty($aParams['seconds'])) ? 0 : $aParams['seconds'];
    $bOnlyMinute = (!isset($aParams['only_minute'])) ? false : $aParams['only_minute'];
    $bRoundMinute = (!isset($aParams['round_minute'])) ? true : $aParams['round_minute'];

    require_once(Config::Get('path.root.engine') . '/modules/viewer/plugs/modifier.declension.php');

    /**
     * Если указан другой язык, подгружаем его
     */
    if (isset($aParams['lang']) and $aParams['lang'] != $oEngine->Lang_GetLang()) {
        $oEngine->Lang_SetLang($aParams['lang']);
    }

    $iSecondsInMinute = 60;

    $sMinute = '';
    $sSecond = '';
    if (($iMinutes = intval($iSecondsParam / $iSecondsInMinute)) > 0) {
        if ($bRoundMinute) {
            $iMinutes = round($iSecondsParam / $iSecondsInMinute);
            $iSecondsParam = $iMinutes * $iSecondsInMinute;
        }

        $sMinute = smarty_modifier_declension(
            $iMinutes,
            $oEngine->Lang_Get('plugin.timereading.minutes', array('minutes' => $iMinutes)),
            $oEngine->Lang_GetLang());

        if (($iSeconds = $iSecondsParam % $iSecondsInMinute) > 0 || $bRoundMinute) {
            if ($bRoundMinute) {
                if ($iSeconds > 30) {
                    $iSeconds = $iSecondsInMinute;
                }
            }

            $sSecond = smarty_modifier_declension(
                $iSeconds,
                $oEngine->Lang_Get('plugin.timereading.seconds', array('seconds' => $iSeconds)),
                $oEngine->Lang_GetLang());
        }
    } else {
        if ($bRoundMinute) {
            if ($iSecondsParam >= ($iSecondsInMinute / 2)) {
                $iSeconds = $iSecondsInMinute;
                $iMinutes = 1;
            }
        } else {
            $iSeconds = $iSecondsParam;
            $iMinutes = 0;
        }

        if ($bOnlyMinute) {
            $sMinute = smarty_modifier_declension(
                $iMinutes,
                $oEngine->Lang_Get('plugin.timereading.minutes', array('minutes' => $iMinutes)),
                $oEngine->Lang_GetLang());
        } else {
            $sSecond = smarty_modifier_declension(
                $iSeconds,
                $oEngine->Lang_Get('plugin.timereading.seconds', array('seconds' => $iSeconds)),
                $oEngine->Lang_GetLang());
        }
    }

    if ($bOnlyMinute) {
        return $sMinute;
    } else {
        return $sMinute . (($sMinute != '') ? ' ' : '') . $sSecond;
    }
}
