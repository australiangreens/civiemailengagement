<div class='crm-content-block'>
  <h4>{ts}Email engagement information{/ts}</h4>
  {if isset($model.date_calculated)}
    {* We have EE values to display *}
    <table class="report-layout" style="max-width: 500px;">
      <thead>
        <tr>
          <th colspan="2">The following email engagement data was calculated on {ts 1=$model.date_calculated} %1{/ts}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Last clickthrough</td>
          <td>{ts 1=$model.recency} %1 days ago{/ts}</td>
        </tr>
        <tr>
          <td>Number of mailings interacted with over reporting period</td>
          <td>{ts 1=$model.frequency 2=$model.ee_period} %1 in last %2 months{/ts}</td>
        </tr>
        <tr>
          <td>Number of mailings sent over reporting period</td>
          <td>{ts 1=$model.volume 2=$model.ee_period} %1 in last %2 months{/ts}</td>
        </tr>
        <tr>
          <td>Number of mailings sent in the last 30 days</td>
          <td>{ts 1=$model.volume_last_30} %1{/ts}</td>
        </tr>
      </tbody>
    </table>
  {else}
    {* No EE values have been calculated *}
    <p>No email engagement data exists for this contact</p>
  {/if}
</div>