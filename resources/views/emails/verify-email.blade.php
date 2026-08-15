<x-mail::message>
# Welcome aboard, {{ $name }}!

Thank you for signing up. Before you can log in and start using your dashboard, we need you to confirm that this email address belongs to you.

<x-mail::button :url="$url" color="success">
Verify Email Address
</x-mail::button>

*Note: This link will expire in 60 minutes for security purposes.*

If you did not create an account with us, no further action is required.

Regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
