<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="http://pujabazar.com/images/invoice-logo.png" class="logo" alt="Puja Bazar Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
