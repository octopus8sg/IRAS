{crmScope extensionKey='com.octopus8.iras'}
    <div class="crm-content-block">
        <div class="action-link">
            {*        {debug}*}
            <a class="button report_offline" href="{crmURL p="civicrm/irasdonation/iras_offline_report"}">
                <i class="crm-i fa-plus-circle">&nbsp;</i>
                {ts}Send via File{/ts}
            </a>
            <a class="button report_offline" href="{crmURL p="civicrm/irasdonation/iras_online_report"}">
                <i class="crm-i fa-plus-circle">&nbsp;</i>
                {ts}Send via API{/ts}
            </a>
        </div>
        <div class="clear"></div>
        {include file="CRM/Irasdonation/Form/TransactionsFilter.tpl"}
        <div class="clear"></div>
        <div class="crm-results-block">
            <div class="crm-search-results">
                <table class="selector-transactions row-highlight pagerDisplay" id="DeviceData" name="DeviceData">
                    <thead class="sticky">
                    <tr>
                        <th id="sortable" scope="col">
                            {ts}Transaction ID{/ts}
                        </th>
                        <th scope="col">
                            {ts}Transaction Date{/ts}
                        </th>
                        <th scope="col">
                            {ts}Transaction Amount{/ts}
                        </th>
                        <th scope="col">
                            {ts}Contact ID{/ts}
                        </th>
                        <th scope="col">
                            {ts}Contact Name{/ts}
                        </th>
                        <th scope="col">
                            {ts}Sent Date{/ts}
                        </th>
                        <th scope="col">
                            {ts}Sent Method{/ts}
                        </th>
                        <th scope="col">
                            {ts}Sent Response{/ts}
                        </th>
                        <th scope="col">
                            {ts}Sent Message{/ts}
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
{crmScript ext=com.octopus8.iras file=js/transactions.js}
{/crmScope}
