<li class="topic-info-time">
    <i>
        {if ($oTopic->getTimeOfReading() == 0)}
            {$aLang.plugin.timereading.without_text}
        {elseif ($oTopic->getTimeOfReading() <= $oConfig->GetValue('plugin.timereading.read_instantly_limit'))}
            {$aLang.plugin.timereading.instantly_read}
        {else}
            {amount_minutes_seconds seconds=$oTopic->getTimeOfReading()}
            {$aLang.plugin.timereading.topic_info_time_of_reading}
        {/if}

        {if ($oTopic->getTimeToWatch() == 0)}
            {$aLang.plugin.timereading.without_video}
        {elseif ($oTopic->getTimeToWatch() <= $oConfig->GetValue('plugin.timereading.watch_instantly_limit'))}
            {$aLang.plugin.timereading.and}
            {$aLang.plugin.timereading.instantly_watch}
        {else}
            {$aLang.plugin.timereading.and}
            {amount_minutes_seconds seconds=$oTopic->getTimeToWatch() only_minute=true round_minute=true}
            {$aLang.plugin.timereading.topic_info_time_to_watch}
        {/if}
    </i>
</li>