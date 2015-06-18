<?php

class PluginTimereading_ModuleTopic extends PluginTimereading_Inherit_ModuleTopic
{
    protected $oMapperTimeOfReading;

    public function Init()
    {
        parent::Init();
        $conn = $this->Database_GetConnect();
        $this->oMapperTimeOfReading = Engine::GetMapper(__CLASS__, 'Topic', $conn);
    }

    public function CalculateTimeOfReading($oTopic)
    {
        $iSpeed = (Config::Get('plugin.timereading.speed')) ? Config::Get('plugin.timereading.speed') : 1200;
        $bUseDelta = (Config::Get('plugin.timereading.use_delta') !== null) ? Config::Get('plugin.timereading.use_delta') : true;
        $iDelta = (Config::Get('plugin.timereading.delta')) ? Config::Get('plugin.timereading.delta') : 200;

        $sStripText = strip_tags($oTopic->getTextSource());
        $iCharCount = strlen(preg_replace('/\s/', '', $sStripText));

        if ($bUseDelta) {
            if (($iMod = ($iCharCount % $iSpeed)) > $iDelta) {
                $iCharCount += $iSpeed - $iMod;
            }
        }

        return round(($iCharCount / $iSpeed) * 60);
    }

    public function AddTimeOfReading($iTopicId, $iTime)
    {
        return $this->oMapperTimeOfReading->addTimeOfReading($iTopicId, $iTime);
    }

    public function CalculateAllTopics()
    {
        $aTopicIds = $this->GetAllTopics();

        foreach ($aTopicIds as $iId) {
            if ($oTopic = $this->Topic_GetTopicById($iId)) {
                $iTime = $this->PluginTimereading_Topic_CalculateTimeOfReading($oTopic);
                $this->PluginTimereading_Topic_AddTimeOfReading($oTopic->getId(), $iTime);
            }
        }
    }

    public function GetAllTopics()
    {
        return $this->oMapperTimeOfReading->GetAllTopics([]);
    }
}