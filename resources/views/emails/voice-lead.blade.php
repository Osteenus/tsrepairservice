@if ($kind === 'overdue')
<h1>Call-back overdue</h1>
<p>Nobody has called this customer back yet{{ $lead->followup_count ? ' (the assistant already made its one follow-up call)' : '' }}. Please call now.</p>
@else
<h1>New phone lead</h1>
<p>Taken by the AI voice assistant; details are speech-to-text, check them when you call back.</p>
@endif
<p><strong>Name:</strong> {{ $lead->caller_name ?: 'Not given' }}<br>
<strong>Phone:</strong> {{ $lead->caller_phone }}<br>
<strong>Address:</strong> {{ $lead->service_address ?: 'Not given' }}</p>
<p><strong>Appliance:</strong> {{ trim(($lead->appliance_brand ?? '').' '.($lead->appliance_type ?? '')) ?: 'Not given' }}<br>
<strong>Outcome:</strong> {{ $lead->outcome ?: 'Unknown' }}{{ $lead->consent_to_callback ? ', agreed to a call-back' : '' }}<br>
<strong>$95 service call fee:</strong> {{ $lead->fee_accepted ? 'agreed' : 'not agreed' }}<br>
@if ($lead->preferred_callback_time)<strong>Preferred time:</strong> {{ $lead->preferred_callback_time }}<br>@endif
@if ($due)<strong>Call back by:</strong> {{ $due }}@endif</p>
@if ($lead->reported_problem)
<p><strong>Problem:</strong></p>
<p style="white-space: pre-wrap">{{ $lead->reported_problem }}</p>
@endif
@if ($lead->agent_summary)
<p><strong>Assistant summary:</strong> {{ $lead->agent_summary }}</p>
@endif
@if ($lead->followup_notes)
<p><strong>Follow-up call:</strong> {{ $lead->followup_notes }}</p>
@endif
@if ($url)
<p><a href="{{ $url }}">Open in the CRM</a></p>
@endif
