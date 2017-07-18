<tr>
    <td>{$entry.id}</td>
    <td>{$entry.ts|date_format:"{$core.config.date_format} {$core.config.time_format}"}</td>
    <td class="text-center">
        {if $entry.success}
            <span class="btn btn-xs btn-success disabled"><i class="i-checkmark"></i></span>
        {else}
            <span class="btn btn-xs btn-danger disabled"><i class="i-remove-sign"></i></span>
        {/if}
    </td>
    <td class="text-right">
        <a class="btn btn-xs btn-default js-cmd-view-details" href="#" data-id="{$entry.id}" data-loading-text="{lang key='loading'}">{lang key='view'}</a>
    </td>
</tr>