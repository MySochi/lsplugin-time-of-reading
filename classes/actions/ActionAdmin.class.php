<?php

class PluginTimereading_ActionAdmin extends PluginTimereading_Inherit_ActionAdmin
{
    protected function RegisterEvent()
    {
        $this->AddEvent('timereading', 'EventTimeReading');
        parent::RegisterEvent();
    }

    protected function EventTimeReading()
    {
        if (!LS::Adm()) {
            return parent::EventNotFound();
        }

        $this->Message_AddNotice($this->Lang_Get('plugin.timereading.admin_time_reading_is_calculated',
            array('count' => $this->PluginTimereading_Topic_CalculateAllTopics())));
    }
}