@php($money = [\App\Crm\Money::class, 'format'])
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice {{ $number }} - Tech Solutions Repair Service</title>
@include('crm.pdf._styles')
<style>
  /* Compact notes as in the estimate template and the PAID stamp under the totals, so the always-printed guarantee fits on one page. */
  .notes p { font-size: 8pt; margin: 0 0 0.04in; }
  .notes h3 { margin-bottom: 0.02in; }
  .status { margin-top: 0.14in; }
</style>
</head>
<body>
<div class="page">
  @include('crm.pdf._header')

  <div class="title-row">
    <h1>INVOICE<span>.</span></h1>
    <div class="meta">
      <div><small>Invoice No.</small><b>{{ $number }}</b></div>
      <div><small>Date</small><b>{{ $date }}</b></div>
      <div><small>Service date</small><b>{{ $service_date }}</b></div>
    </div>
  </div>

  @include('crm.pdf._parties', ['customerHeading' => 'Bill to'])

  <table class="items">
    <thead>
      <tr><th>Description</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Amount</th></tr>
    </thead>
    <tbody>
      @if ($labor_items)
      <tr class="group"><td colspan="4">Service &amp; labor</td></tr>
      @foreach ($labor_items as $it)
      <tr><td>{{ $it['description'] }}@if ($it['warranty'])<span class="sub">Covered by warranty - no charge</span>@endif</td><td class="num">{{ $it['qty'] }}</td><td class="num">{{ $money($it['unit']) }}</td><td class="num">{{ $money($it['amount']) }}</td></tr>
      @endforeach
      @endif
      @if ($part_items)
      <tr class="group"><td colspan="4">Parts</td></tr>
      @foreach ($part_items as $it)
      <tr><td>{{ $it['description'] }}@if ($it['part_number'])<span class="sub">Part # {{ $it['part_number'] }}</span>@endif<span class="sub">@if ($it['warranty'])Warranty replacement - no charge @else Non-warranty part - charged @endif @if ($it['used']) · USED / EXCHANGED part @endif</span></td><td class="num">{{ $it['qty'] }}</td><td class="num">{{ $money($it['unit']) }}</td><td class="num">{{ $money($it['amount']) }}</td></tr>
      @endforeach
      @endif
    </tbody>
  </table>

  <div class="bottom">
    <div class="notes">
      @if ($work_performed)<h3>Work performed</h3><p>{{ $work_performed }}</p>@endif
      <h3>Used or exchanged parts</h3><p>{{ $used_parts_statement }}</p>
      @if ($estimate_ref)<h3>Estimate</h3><p>{{ $estimate_ref }}</p>@endif
      @if ($warranty)<h3>Warranty</h3><p>{{ $warranty }}</p>@endif
      @if ($notes)<h3>Notes</h3><p>{{ $notes }}</p>@endif
      <h3>Payment</h3>
      <p>{{ $payment_methods }}@if ($payment_method_used)<br>Paid by: <b>{{ $payment_method_used }}</b>@endif</p>
    </div>
    <div class="totals">
      <table>
        <tr><td>Labor &amp; service</td><td>{{ $money($totals['labor']) }}</td></tr>
        <tr><td>Parts</td><td>{{ $money($totals['parts']) }}</td></tr>
        @if ($show_tax)<tr><td>Sales tax on parts ({{ $tax_rate_label }})</td><td>{{ $money($totals['tax']) }}</td></tr>@endif
        @if ($totals['discount'])<tr><td>Discount</td><td>−{{ $money($totals['discount']) }}</td></tr>@endif
        <tr class="grand"><td>Total</td><td>{{ $money($totals['total']) }}</td></tr>
        @if ($totals['paid'])<tr><td>Paid</td><td>−{{ $money($totals['paid']) }}</td></tr>@endif
        <tr class="balance"><td>Balance due</td><td>{{ $money($totals['balance']) }}</td></tr>
      </table>
      <span style="margin-left:0.1in" class="status {{ $totals['balance'] <= 0 ? 'paid' : 'due' }}">{{ $totals['balance'] <= 0 ? 'PAID' : 'BALANCE DUE' }}</span>
    </div>
  </div>

  <div class="sign {{ $customer_signature || $completed_by ? 'filled' : '' }}">
    <div>@if ($customer_signature)<span class="val"><img src="{{ $customer_signature['image'] }}" alt=""></span>@endif Customer signature</div>
    <div>@if ($completed_by)<span class="val">{{ $completed_by }}</span>@endif Invoice completed by (signature or employee no.)</div>
    <div>@if ($performed_by)<span class="val">{{ $performed_by }}</span>@endif Repair performed by (signature or employee no.)</div>
  </div>
  {{-- Replaced parts are not always returned (some are kept for recycling), so the customer confirms only the repair. --}}
  <p class="return">{!! $work_accepted ? '&#9745;' : '&#9744;' !!} Repairs satisfactorily completed. Customer initials: @if ($work_accepted && $customer_initials)<b>{{ $customer_initials }}</b>@else{{ '______' }}@endif</p>

  <div class="legal">
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
