<p>Hello,</p>

<p>You requested a password reset. Click the link below:</p>

<a href="{{ route('forgot.password.Link', ['token' => $token]) }}?email={{ urlencode($email) }}">Reset Password</a>


<p>This link will expire in 5 minutes.</p>
