@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/logo.png') }}" class="logo" alt="{{ config('app.name') }} Logo" style="max-width: 200px; height: auto;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
