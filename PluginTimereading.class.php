<?php

if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginTimereading extends Plugin
{

    protected $aInherits = array(
        'entity' => array(
            'ModuleTopic_EntityTopic' => '_ModuleTopic_EntityTopic',
        ),
        'mapper' => array(
            'ModuleTopic_MapperTopic' => '_ModuleTopic_MapperTopic'
        ),
        'module' => array(
            'ModuleTopic' => '_ModuleTopic',
        ),
    );

    public function Activate()
    {
        if (!$this->isFieldExists('prefix_topic', 'topic_time_of_reading')) {
            $this->ExportSQL(dirname(__FILE__) . '/install.sql');
        }

        $this->PluginTimereading_Topic_CalculateAllTopics();

        return true;
    }

    public function Deactivate()
    {
        if (Config::Get('plugin.timereading.full_deinstall')) {
            $this->ExportSQL(dirname(__FILE__) . '/deinstall.sql');
        }

        return true;
    }

    public function Init()
    {

    }
}
