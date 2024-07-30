{* HEADER *}

<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="top"}
</div>

{* HELP TEXT *}
<div class="crm-help-text">
  <p>{$helpText}</p>
</div>

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}
{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}

{* FOOTER *}
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

{* ADDITIONAL STYLES *}
<style>
  .crm-submit-buttons {
    margin-bottom: 20px;
  }

  .crm-help-text {
    margin: 20px 0;
    padding: 10px;
    background-color: #f7f7f7;
    border-left: 4px solid #007cba;
    border-radius: 4px;
  }

  .crm-section {
    margin: 20px 0;
    padding: 15px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  .crm-section .label {
    font-weight: bold;
    margin-bottom: 5px;
  }

  .crm-section .content {
    margin-bottom: 10px;
  }

  .crm-section .content textarea,
  .crm-section .content select {
    width: 50%;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  .clear {
    clear: both;
  }
</style>
