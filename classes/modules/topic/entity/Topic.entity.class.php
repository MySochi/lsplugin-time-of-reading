<?php

class PluginTimereading_ModuleTopic_EntityTopic extends PluginTimereading_Inherit_ModuleTopic_EntityTopic
{
    public function getTimeOfReading()
    {
        return $this->_getDataOne('topic_time_of_reading');
    }

    public function setTimeOfReading($data)
    {
        $this->_aData['topic_time_of_reading'] = $data;
    }
}