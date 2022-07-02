{crmScope extensionKey='com.octopus8.iras'}
  
  <div class="crm-content-block">

    <div class="crm-block crm-form-block crm-basic-criteria-form-block">
      <div class="crm-accordion-wrapper crm-expenses_search-accordion">
        <div class="crm-accordion-header crm-master-accordion-header">{ts}Filter Transactions{/ts}</div><!-- /.crm-accordion-header -->
        <div class="crm-accordion-body">
          <table class="form-layout transactions-filter">
            <tbody>
            <tr>
              <td><span>{$form.is_api.label}</span></td>
              <td><span>{$form.is_api.html}</span></td>
            </tr>
            <tr>
              <td><span>{$form.sent_response.label}</span></td>
              <td><span>{$form.sent_response.html}</span></td>
            </tr>
            <tr>
              <td><span>{$form.trn_start_date.label}</span></td>
              <td><span>{$form.trn_start_date.html}</span></td>
            </tr>
            <tr>
              <td><span>{$form.trn_end_date.label}</span></td>
              <td><span>{$form.trn_end_date.html}</span></td>
            </tr>
            <tr>
              <td><span>{$form.sent_start_date.label}</span></td>
              <td><span>{$form.sent_start_date.html}</span></td>
            </tr>
            <tr>
              <td><span>{$form.sent_end_date.label}</span></td>
              <td><span>{$form.sent_end_date.html}</span></td>
            </tr>                                                
            </tbody>
          </table>
        </div><!-- /.crm-accordion-body -->
      </div><!-- /.crm-accordion-wrapper -->
    </div><!-- /.crm-form-block -->
  </div>
{*  {debug}*}
{/crmScope}