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
        'action' => array(
            'ActionAdmin' => '_ActionAdmin',
        ),
    );

    public function Activate()
    {
        if (!$this->isFieldExists('prefix_topic', 'topic_time_of_reading')) {
            $this->ExportSQL(dirname(__FILE__) . '/sql_dumps/install.sql');
        }

        if (Config::Get('plugin.timereading.calculate_when_activate')) {
            $this->PluginTimereading_Topic_CalculateAllTopics();
        }

        return true;
    }

    public function Deactivate()
    {
        if (Config::Get('plugin.timereading.full_deinstall')) {
            $this->ExportSQL(dirname(__FILE__) . '/sql_dumps/deinstall.sql');
        }

        return true;
    }

    public function Init()
    {
        $this->Viewer_GetSmartyObject()->addPluginsDir(dirname(__FILE__) . '/classes/modules/viewer/plugs');
        //$this->Viewer_GetSmartyObject()->loadPlugin('smarty_function_amount_minutes_seconds');
    }
}
