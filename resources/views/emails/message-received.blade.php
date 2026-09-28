<h1>New website repair request</h1>
<p><strong>Name:</strong> {{ $name }}<br>
<strong>Phone:</strong> {{ $phone }}<br>
<strong>Email:</strong> {{ $email ?: 'Not provided' }}</p>
<p><strong>Appliance:</strong> {{ $appliance }}<br>
<strong>City or ZIP:</strong> {{ $location }}<br>
<strong>Brand:</strong> {{ $brand ?: 'Not provided' }}<br>
<strong>Model:</strong> {{ $model ?: 'Not provided' }}</p>
<p><strong>Problem:</strong></p>
<p style="white-space: pre-wrap">{{ $messageText }}</p>
