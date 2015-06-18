<?php

class PluginTimereading_ModuleTopic_MapperTopic extends PluginTimereading_Inherit_ModuleTopic_MapperTopic
{
    public function addTimeOfReading($iTopicId, $iTime)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic') . "
			SET
				topic_time_of_reading = ?d
			WHERE
				topic_id = ?d
		";
        if ($this->oDb->query($sql,
            $iTime,
            $iTopicId)
        ) {
            return true;
        }

        return false;
    }
}