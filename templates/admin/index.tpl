<div class="row">
    <div class="col col-lg-6">
        <div class="widget widget-large">
            <div class="widget-header"><i class="i-stats"></i> Recent runs</div>
            <div class="widget-content" id="js-entries-list" data-offset="{$offset}">
                <table class="table table-light table-hover">
                    <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>{lang key='timestamp'}</th>
                        <th width="50">{lang key='result'}</th>
                        <th width="90">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $entries as $entry}
                        {include 'extra:currencyrates/widget.entry-row'}
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col col-lg-6">
        <div class="widget widget-large">
            <div class="widget-header"> Details</div>
            <div class="widget-content">
                <div id="js-ph-details">Click the <em>View</em> button on the left pane to see the details.</div>
            </div>
        </div>
    </div>
</div>
{ia_print_css files='_IA_URL_js/jquery/plugins/scrollbars/jquery.mCustomScrollbar'}
{ia_print_js files='jquery/plugins/scrollbars/jquery.mCustomScrollbar.concat.min, _IA_URL_modules/currencyrates/js/admin/index'}