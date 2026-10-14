@php($money = [\App\Crm\Money::class, 'format'])
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Estimate {{ $number }} - Tech Solutions Repair Service</title>
@include('crm.pdf._styles')
<style>
  .auth { margin-top: 0.1in; border: 1.5px solid var(--ink); padding: 0.07in 0.12in; font-size: 8.8pt; }
  .auth b { font-size: 8pt; text-transform: uppercase; letter-spacing: 0.08em; color: var(--red); }
  .rev { margin-top: 0.07in; border: 1px dashed var(--muted); padding: 0.06in 0.12in; font-size: 8pt; color: #333; }
  .rev .lines { display: grid; grid-template-columns: 1.1fr 2.2fr 1fr 1.4fr; gap: 0.12in; margin-top: 0.1in; }
  .rev .lines div { border-top: 1px solid var(--ink); padding-top: 0.03in; color: var(--muted); font-size: 7.5pt; }
  .notes p { font-size: 8pt; margin: 0 0 0.04in; }
  .notes h3 { margin-bottom: 0.02in; }
  .rev .lines.filled { margin-top: 0.06in; }
  .rev .lines .val { display: block; color: var(--ink); font-size: 8pt; margin-bottom: 0.03in; border: 0; }
</style>
</head>
<body>
<div class="page">
  @include('crm.pdf._header')

  <div class="title-row">
    <h1>ESTIMATE<span>.</span></h1>
    <div class="meta">
      <div><small>Estimate No.</small><b>{{ $number }}</b></div>
      <div><small>Date</small><b>{{ $date }}</b></div>
      @if ($valid_until)<div><small>Valid until</small><b>{{ $valid_until }}</b></div>@endif
    </div>
  </div>

  @include('crm.pdf._parties', ['customerHeading' => 'Customer'])

  <table class="items">
    <thead>
      <tr><th>Description</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Amount</th></tr>
    </thead>
    <tbody>
      @if ($labor_items)
      <tr class="group"><td colspan="4">Service call, travel &amp; labor</td></tr>
      @foreach ($labor_items as $it)
      <tr><td>{{ $it['description'] }}</td><td class="num">{{ $it['qty'] }}</td><td class="num">{{ $money($it['unit']) }}</td><td class="num">{{ $money($it['amount']) }}</td></tr>
      @endforeach
      @endif
      @if ($part_items)
      <tr class="group"><td colspan="4">Parts</td></tr>
      @foreach ($part_items as $it)
      <tr><td>{{ $it['description'] }}@if ($it['part_number'])<span class="sub">Part # {{ $it['part_number'] }}</span>@endif @if ($it['used'])<span class="sub">USED / EXCHANGED part</span>@endif</td><td class="num">{{ $it['qty'] }}</td><td class="num">{{ $money($it['unit']) }}</td><td class="num">{{ $money($it['amount']) }}</td></tr>
      @endforeach
      @endif
    </tbody>
  </table>

  <div class="bottom">
    <div class="notes">
      <h3>Diagnostic / service call</h3>
      <p>{{ $diagnostic_terms }}</p>
      @if ($warranty)<h3>Warranty</h3><p>{{ $warranty }}</p>@endif
      @if ($notes)<h3>Notes</h3><p>{{ $notes }}</p>@endif
      <h3>Taxes</h3>
      <p>Sales tax and other applicable taxes are not included in this estimate.</p>
    </div>
    <div class="totals">
      <table>
        <tr><td>Service call &amp; labor</td><td>{{ $money($totals['labor']) }}</td></tr>
        <tr><td>Parts</td><td>{{ $money($totals['parts']) }}</td></tr>
        @if ($totals['discount'])<tr><td>Discount</td><td>−{{ $money($totals['discount']) }}</td></tr>@endif
        <tr class="grand"><td>Estimate total</td><td>{{ $money($totals['total']) }}</td></tr>
      </table>
    </div>
  </div>

  <div class="auth">
    <b>Customer authorization</b><br>
    I received this written estimate and authorize the repairs described above. The service dealer may not charge for work done or parts supplied above this estimate total without my prior consent.
    <div class="sign {{ $customer_signature ? 'filled' : '' }}" style="margin-top:{{ $customer_signature ? '0.24in' : '0.12in' }}">
      <div>@if ($customer_signature)<span class="val"><img src="{{ $customer_signature['image'] }}" alt=""></span>@endif Customer signature</div>
      <div>@if ($customer_signature)<span class="val">{{ $customer_signature['name'] }}</span>@endif Printed name</div>
      <div>@if ($customer_signature)<span class="val">{{ $technician }}</span>@endif Technician (signature or employee no.)</div>
      <div style="flex:0.6">@if ($customer_signature)<span class="val">{{ $customer_signature['at'] }}</span>@endif Date / time</div>
    </div>
  </div>

  <div class="rev">
    <b style="text-transform:uppercase;letter-spacing:0.08em;color:var(--red)">Revised estimate / change authorization</b> (use if the cost increases; get the customer&#39;s consent first)
    @if ($consent)
    <div class="lines filled">
      <div><span class="val">{{ $consent['at'] }}</span>Date / time</div>
      <div><span class="val">{{ $consent['description'] }}</span>Added or changed work and parts</div>
      <div><span class="val">{{ $money($consent['new_total']) }}</span>New total</div>
      <div><span class="val">{{ $consent['approved_by'] }}</span>Approved by (signature, or name + phone if oral)</div>
    </div>
    @else
    <div class="lines">
      <div>Date / time</div>
      <div>Added or changed work and parts</div>
      <div>New total</div>
      <div>Approved by (signature, or name + phone if oral)</div>
    </div>
    @endif
  </div>

  <div class="legal" style="margin-top:0.07in">
    @include('crm.pdf._legal')
  </div>

  <footer>
    <b>Thank you for choosing {{ $company['name'] }}!</b><br>
    {{ $company['phone'] }} · {{ $company['website'] }}
  </footer>
</div>
@include('crm.pdf._fit')
</body>
</html>
