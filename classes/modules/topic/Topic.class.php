<?php

class PluginTimereading_ModuleTopic extends PluginTimereading_Inherit_ModuleTopic
{
    public function Init()
    {
        parent::Init();
    }

    public function CalculateTimeOfReading($oTopic)
    {
        $iSpeed = (Config::Get('plugin.timereading.speed')) ? Config::Get('plugin.timereading.speed') : 1200;
        $bUseDelta = (Config::Get('plugin.timereading.use_delta') !== null) ? Config::Get('plugin.timereading.use_delta') : true;
        $iDelta = (Config::Get('plugin.timereading.delta')) ? Config::Get('plugin.timereading.delta') : 200;

        $iCharCount = strlen(preg_replace('/\s/', '', strip_tags($oTopic->getText())));

        if ($bUseDelta) {
            if (($iMod = ($iCharCount % $iSpeed)) > $iDelta) {
                $iCharCount += $iSpeed - $iMod;
            }
        }

        return round(($iCharCount / $iSpeed) * 60);
    }

    public function AddTimeOfReading($iTopicId, $iTime)
    {
        return $this->oMapperTopic->addTimeOfReading($iTopicId, $iTime);
    }

    public function AddTimeToWatch($iTopicId, $iTime)
    {
        return $this->oMapperTopic->addTimeToWatch($iTopicId, $iTime);
    }

    public function CalculateAllTopics()
    {
        set_time_limit(0);

        $aTopicIds = $this->GetAllTopics();

        $iCount = 0;
        foreach ($aTopicIds as $iId) {
            if ($oTopic = $this->Topic_GetTopicById($iId)) {
                $iTime = $this->PluginTimereading_Topic_CalculateTimeOfReading($oTopic);
                $iTimeVideo = $this->PluginTimereading_Topic_videoParser($oTopic);

                if ($this->PluginTimereading_Topic_AddTimeOfReading($oTopic->getId(), $iTime) ||
                    $this->PluginTimereading_Topic_AddTimeToWatch($oTopic->getId(), $iTimeVideo)
                ) {
                    $iCount++;
                }
            }
        }

        return $iCount;
    }

    public function GetAllTopics()
    {
        return $this->oMapperTopic->GetAllTopics(array());
    }

    public function videoParser($oTopic)
    {
        set_time_limit(0);

        $iTime = 0;
        $iTime += $this->parseYoutube($oTopic->getTextSource());
        $iTime += $this->parseVimeo($oTopic->getTextSource());
        $iTime += $this->parseRutube($oTopic->getTextSource());
        $iTime += $this->parseCoub($oTopic->getTextSource());

        return $iTime;
    }

    private function parseYoutube($sText)
    {
        $sApiKey = 'AIzaSyDuwNRZvuGXr0o5eSiRLUUt8h16a8Uwjgc';
        $sRegex = '/<video>(?:http(?:s|):|)(?:\/\/|)(?:www\.|)youtu(?:\.|)be(?:-nocookie|)(?:\.com|)\/(?:e(?:mbed|)\/|v\/|watch\?(?:.+&|)v=|)([a-zA-Z0-9_\-]+?)(&.+)?<\/video>/Ui';
        preg_match_all($sRegex, $sText, $matches);
        $aVideoId = $matches[1];

        $iDurationTotal = 0;
        foreach ($aVideoId as $sVideoId) {
            $sReturn = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id=' .
                $sVideoId . '&part=contentDetails&key=' . $sApiKey);

            if ($sReturn) {
                $oJson = json_decode($sReturn);

                if (!isset($oJson->{'error'})) {
                    $oDateTime = new DateTime('@0');
                    $oDateTime->add(new DateInterval($oJson->{'items'}[0]->{'contentDetails'}->{'duration'}));
                    $iDurationTotal += $oDateTime->getTimestamp();
                }
            }
        }

        return $iDurationTotal;
    }

    private function parseVimeo($sText)
    {
        $sRegex = '/<video>(?:http(?:s|):|)(?:\/\/|)(?:www\.|)vimeo\.com\/(\d+).*<\/video>/i';
        preg_match_all($sRegex, $sText, $matches);
        $aVideoId = $matches[1];

        $iDurationTotal = 0;
        foreach ($aVideoId as $sVideoId) {
            $sReturn = file_get_contents('https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/' . $sVideoId);

            if ($sReturn) {
                $oJson = json_decode($sReturn);

                if (!isset($oJson->{'error'})) {
                    $iDurationTotal += $oJson->{'duration'};
                }
            }
        }

        return $iDurationTotal;
    }

    private function parseRutube($sText)
    {
        $sRegex = '/<video>(?:http(?:s|):|)(?:\/\/|)(?:www\.|)rutube\.ru\/video\/([a-zA-Z0-9]+).*<\/video>/i';
        preg_match_all($sRegex, $sText, $matches);
        $aVideoId = $matches[1];

        $iDurationTotal = 0;
        foreach ($aVideoId as $sVideoId) {
            $sReturn = file_get_contents('http://rutube.ru/api/video/' . $sVideoId);

            if ($sReturn) {
                $oJson = json_decode($sReturn);

                if (!isset($oJson->{'error'})) {
                    $iDurationTotal += $oJson->{'duration'};
                }
            }
        }

        return $iDurationTotal;
    }

    private function parseCoub($sText)
    {

        $sRegex = '/<video>(?:http(?:s|):|)(?:\/\/|)(?:www\.|)coub\.com\/(?:view|embed)\/([a-zA-Z0-9_\-]+).*<\/video>/i';
        preg_match_all($sRegex, $sText, $matches);
        $aVideoId = $matches[1];

        $iDurationTotal = 0;
        foreach ($aVideoId as $sVideoId) {
            $sReturn = file_get_contents('http://coub.com/api/v2/coubs/' . $sVideoId);

            if ($sReturn) {
                $oJson = json_decode($sReturn);

                if (!isset($oJson->{'error'})) {
                    $iDurationTotal += round($oJson->{'duration'});
                }
            }
        }

        return $iDurationTotal;
    }
}