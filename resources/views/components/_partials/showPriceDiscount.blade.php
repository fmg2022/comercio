<p @if (floatval($item->pivot->discount) != 0) class="text-base font-normal text-slate-500 line-through" @endif>
  ${{ number_format($item->price * $item->pivot->quantity, 2, ',', '.') }}
</p>
@if (floatval($item->pivot->discount) != 0)
  <span>
    ${{ number_format($item->price * $item->pivot->quantity - $item->pivot->discount, 2, ',', '.') }}
  </span>
@endif
