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

    public function CalculateAllTopics()
    {
        set_time_limit(0);

        $aTopicIds = $this->GetAllTopics();

        $iCount = 0;
        foreach ($aTopicIds as $iId) {
            if ($oTopic = $this->Topic_GetTopicById($iId)) {
                $iTime = $this->PluginTimereading_Topic_CalculateTimeOfReading($oTopic);
                if ($this->PluginTimereading_Topic_AddTimeOfReading($oTopic->getId(), $iTime)) {
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
}