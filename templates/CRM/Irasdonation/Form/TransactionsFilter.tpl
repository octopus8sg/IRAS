{crmScope extensionKey='com.octopus8.iras'}
  
  <div class="crm-content-block">

    <div class="crm-block crm-form-block crm-basic-criteria-form-block">
      <div class="crm-accordion-wrapper crm-expenses_search-accordion">
        <div class="crm-accordion-header crm-master-accordion-header">{ts}Filter Transactions{/ts}</div><!-- /.crm-accordion-header -->
        <div class="crm-accordion-body">
          <table class="form-layout transactions-filter">
            <tbody>
            <tr>
              <td class="label"><span>{$form.method.label}</span></td>
              <td><span>{$form.method.html}</span></td>
              <td class="label"><span>{$form.sent_response.label}</span></td>
              <td><span>{$form.sent_response.html}</span></td>
            </tr>
            <tr>
              <td class="label">{$form.transaction_range_start_date.label}</td>
              <td>{$form.transaction_range_start_date.html}</td>
              <td class="label">{$form.transaction_range_end_date.label}</td>
              <td>{$form.transaction_range_end_date.html}</td>
            </tr>
            <tr>
              <td class="label">{$form.sent_range_start_date.label}</td>
              <td>{$form.sent_range_start_date.html}</td>
              <td class="label">{$form.sent_range_end_date.label}</td>
              <td>{$form.sent_range_end_date.html}</td>
            </tr>                                                
            </tbody>
          </table>
        </div><!-- /.crm-accordion-body -->
      </div><!-- /.crm-accordion-wrapper -->
    </div><!-- /.crm-form-block -->
  </div>
{*  {debug}*}
{/crmScope}