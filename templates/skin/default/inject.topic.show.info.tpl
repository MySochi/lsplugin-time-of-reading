{if $oTopic}
<li class="topic-info-time">
    <i>
        {if {cfg name='plugin.timereading.function.read_time'}}
            {if ($oTopic->getTimeOfReading() <= {cfg name='plugin.timereading.read_nothing_limit'})}
            {elseif ($oTopic->getTimeOfReading() <= {cfg name='plugin.timereading.read_instantly_limit'})}
                {$aLang.plugin.timereading.instantly_read}
            {else}
                {amount_minutes_seconds seconds=$oTopic->getTimeOfReading() only_minute=true round_minute=true}
                {$aLang.plugin.timereading.topic_info_time_of_reading}
            {/if}
        {/if}

        {if {cfg name='plugin.timereading.function.watch_time'}}
            {if ($oTopic->getTimeToWatch() <= {cfg name='plugin.timereading.watch_nothing_limit'})}
            {elseif ($oTopic->getTimeToWatch() <= {cfg name='plugin.timereading.watch_instantly_limit'})}
                {if ($oTopic->getTimeOfReading() > {cfg name='plugin.timereading.read_nothing_limit'})}
                    {$aLang.plugin.timereading.and}
                {/if}
                {$aLang.plugin.timereading.instantly_watch}
            {else}
                {if ($oTopic->getTimeOfReading() > {cfg name='plugin.timereading.read_nothing_limit'})}
                    {$aLang.plugin.timereading.and}
                {/if}
                {amount_minutes_seconds seconds=$oTopic->getTimeToWatch() only_minute=true round_minute=true}
                {$aLang.plugin.timereading.topic_info_time_to_watch}
            {/if}
        {/if}
    </i>
</li>
{/if}