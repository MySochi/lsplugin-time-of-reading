<?php

class PluginTimereading_HookTimereading extends Hook
{
    public function RegisterHook()
    {
        $this->AddHook('topic_add_after', 'CalculateTime', __CLASS__);
        $this->AddHook('topic_edit_after', 'CalculateTime', __CLASS__);

        $this->AddHook('template_admin_action_item', 'InjectAdmin');
    }

    public function InjectAdmin()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.admin.menu.tpl');
    }

    public function CalculateTime($aParam)
    {
        $oTopic = $aParam['oTopic'];

        $iTime = $this->PluginTimereading_Topic_CalculateTimeOfReading($oTopic);
        $this->PluginTimereading_Topic_AddTimeOfReading($oTopic->getId(), $iTime);
    }
}