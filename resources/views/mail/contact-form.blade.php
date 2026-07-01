<x-mail::message>
# New Enquiry Received

A new contact form submission has been received from the ClearClaims website.

<x-mail::panel>
**{{ $firstName }} {{ $lastName }}** |
{{ $email }} |
@if($company)
{{ $company }}
@endif
</x-mail::panel>

@if($service)
**Service of Interest:** {{ $service }}

@endif
**Message:**

{{ $body }}

<x-mail::button :url="'mailto:'.$email">
Reply to {{ $firstName }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
