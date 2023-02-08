{* HEADER *}

  <h2>Generate report manually, generates report for given date range</h2>
  <br>
  {* choose date this report will not be saved to database as reported *}
  <div>
    <span>{$form.start_date.label}</span>
    <span>{$form.start_date.html}</span>
    <span>{$form.end_date.label}</span>
    <span>{$form.end_date.html}</span>
  </div>
  <br>
  
  <div>
    <span>{$form.include_previous.label}</span>
    <span>{$form.include_previous.html}</span>
  </div>
  <br>

{* FIELD EXAMPLE: OPTION 2 (MANUAL LAYOUT)

  <div>
    <span>{$form.value.label}</span>
    <span>{$form.value.html}</span>
  </div>

{* IRAS configuration *}
<div>
  <span>{$form.IRAS_endpoint.label}</span>
  <span>{$form.IRAS_endpoint.html}</span>
</div>

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
