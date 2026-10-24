<h1>New {{ $byPhone ? 'phone' : 'website' }} repair request</h1>
@if ($byPhone)
<p>Taken by the voice bot; answers are speech-to-text, check them when you call back.</p>
@endif
<p><strong>Name:</strong> {{ $name }}<br>
<strong>Phone:</strong> {{ $phone }}<br>
<strong>Email:</strong> {{ $email ?: 'Not provided' }}</p>
<p><strong>Appliance:</strong> {{ $appliance }}<br>
<strong>City or ZIP:</strong> {{ $location }}<br>
<strong>Brand:</strong> {{ $brand ?: 'Not provided' }}<br>
<strong>Model:</strong> {{ $model ?: 'Not provided' }}</p>
<p><strong>Problem:</strong></p>
<p style="white-space: pre-wrap">{{ $messageText }}</p>
@if ($photoCount)
<p><strong>Photos:</strong> {{ $photoCount }} attached — open the request in the CRM to view them.</p>
@endif
