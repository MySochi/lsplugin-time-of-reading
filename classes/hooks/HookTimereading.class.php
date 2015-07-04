<?php

class PluginTimereading_HookTimereading extends Hook
{
    public function RegisterHook()
    {
        $this->AddHook('topic_add_after', 'CalculateTime', __CLASS__);
        $this->AddHook('topic_edit_after', 'CalculateTime', __CLASS__);

        $this->AddHook('template_admin_action_item', 'InjectAdmin');
        foreach (Config::Get('plugin.timereading.hooks') as $sHook) {
            $this->AddHook('template_' . $sHook, 'TimeToReadAndWatch');
        }
    }

    public function TimeToReadAndWatch($aParam)
    {
        $this->Viewer_Assign('oTopic', $aParam['topic']);

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.topic.show.info.tpl');
    }

    public function InjectAdmin()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.admin.menu.tpl');
    }

    public function CalculateTime($aParam)
    {
        $oTopic = $aParam['oTopic'];

        if (Config::Get('plugin.timereading.function.watch_time')) {
            $iVideoTime = $this->PluginTimereading_Topic_videoParser($oTopic);
            $this->PluginTimereading_Topic_AddTimeToWatch($oTopic->getId(), $iVideoTime);
        }

        if (Config::Get('plugin.timereading.function.read_time')) {
            $iReadingTime = $this->PluginTimereading_Topic_CalculateTimeOfReading($oTopic);
            $this->PluginTimereading_Topic_AddTimeOfReading($oTopic->getId(), $iReadingTime);
        }
    }
}