<div class="cols">
  <div class="box">
    <h3>{{ $customerHeading }}</h3>
    <p class="name">{{ $customer['name'] }}</p>
    <p>{{ $customer['address_line1'] }}</p>
    <p>{{ $customer['city_state_zip'] }}</p>
    @if ($customer['phone'])<p class="muted">{{ $customer['phone'] }}</p>@endif
    @if ($customer['email'])<p class="muted">{{ $customer['email'] }}</p>@endif
  </div>
  <div class="box">
    <h3>Appliance</h3>
    <p class="name">{{ $appliance['type'] }}</p>
    <p>{{ $appliance['brand'] }} {{ $appliance['model'] }}</p>
    @if ($appliance['serial'])<p class="muted">S/N {{ $appliance['serial'] }}</p>@endif
  </div>
  <div class="box">
    <h3>Reported problem</h3>
    <p>{{ $appliance['problem'] }}</p>
    @if ($appliance['diagnosis'])<p class="muted" style="margin-top:4px"><b>Findings:</b> {{ $appliance['diagnosis'] }}</p>@endif
  </div>
</div>
