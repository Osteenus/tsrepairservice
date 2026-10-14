<div class="topbar"></div>
@if ($watermark)<div class="sample">{{ $watermark }}</div>@endif

<header>
  <img class="logo" src="{{ $logo }}" alt="Tech Solutions Repair Service">
  <div class="company">
    <strong>{{ $company['legal_name'] }}</strong><br>
    @if ($company['address']){{ $company['address'] }}<br>@endif{{ $company['service_area'] }}<br>
    {{ $company['phone'] }}@if ($company['email']) · {{ $company['email'] }}@endif<br>
    {{ $company['website'] }}<br>
    {{ $company['registration'] }}
  </div>
</header>
