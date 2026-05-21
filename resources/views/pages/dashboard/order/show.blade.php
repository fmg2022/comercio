@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="flex flex-wrap items-center gap-3"
    class="flex justify-between items-center flex-wrap">
    <x-slot:textTitle>
      {{ 'Detalles de la Orden #' . $order->id }}
      <span class="text-base">({{ Str::substr($order->date, 0, 10) }})</span>
    </x-slot:textTitle>
    <div class="flex gap-2">
      <x-buttons.linkFill href="{{ url()->previous() }}" class="bg-slate-500 active:bg-slate-600">
        Volver
      </x-buttons.linkFill>
      <x-buttons.linkFill href="{{ route('pdf.order', $order->id) }}" class="bg-red-700 active:bg-red-800" target="_blank">
        Generar PDF
      </x-buttons.linkFill>
    </div>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr>
        <th>#</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th class="hidden md:table-cell">Descuento</th>
        <th class="hidden md:table-cell">Subtotal</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>
    @foreach ($order->products as $index => $orderLine)
      <tr class="[&>td]:text-slate-200">
        <td class="text-center">{{ $index + 1 }}</td>
        <td>
          <div class="ps-5 flex items-center flex-wrap gap-2 text-base">
            <img src="{{ asset('images/products/' . $orderLine->image) }}" alt="{{ $orderLine->name }}"
              class="w-16 h-16 object-cover hidden lg:block">
            <span class="text-slate-400 font-semibold">{{ $orderLine->name }}</span>
            <span class="me-2 font-bold">{{ $orderLine->brand->name }}</span>
          </div>
        </td>
        <td class="text-center">{{ $orderLine->pivot->quantity }}</td>
        <td class="text-center font-bold"><span class="me-px">$</span>{{ $orderLine->pivot->price_formated }}</td>
        <td class="relative text-center hidden md:table-cell">
          <span class="peer/popup">
            ${{ $orderLine->pivot->discount_formated }}
          </span>
          <x-popups.text
            class="top-2/3 left-1/2 -translate-x-1/2 w-max hidden bg-slate-900/80 peer-hover/popup:inline-block">
            {{ $orderLine->pivot->offer_template_id ? $orderLine->pivot->offerName() : 'Sin descuento' }}
          </x-popups.text>
        </td>
        <td class="text-center hidden md:table-cell">
          <span class="me-px">$</span>{{ $orderLine->pivot->subtotal() }}
        </td>
        <td>
          <div class="relative flex justify-end">
            <x-popups.contentWcheck iid="chorderline-{{ $orderLine->id }}" labelClass="hover:bg-slate-900"
              class="right-12 -top-1/4">
              <x-slot:label>
                <x-icons.threeDotsX class="size-6" />
              </x-slot:label>

              <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                <li>
                  @can('list products')
                    <a href="{{ route('products.show', $orderLine->id) }}"
                      class="px-4 py-2.5 flex gap-3 hover:bg-slate-700">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Ver Producto
                    </a>
                  @endcan
                </li>
              </ul>
            </x-popups.contentWcheck>
          </div>
        </td>
      </tr>
    @endforeach
  </x-tables.table>
  <div
    class="w-full max-w-2xs px-10 py-3 ms-auto flex justify-between items-center text-xl font-bold bg-slate-700 rounded-b-md">
    <span>Total</span>
    <span><span class="me-px">$</span>{{ $order->total_formated }}</span>
  </div>

  @if (isset($order->notes))
    <div class="px-4 py-5 mt-6 flex flex-col gap-4 bg-slate-800 rounded-xl lg:max-w-1/2">
      <h3 class="text-xl text-slate-300 font-semibold underline underline-offset-4">Notas adicionales</h3>
      <p>"{{ $order->notes }}"</p>
      <p>De: <i
          class="text-sm font-medium">{{ request()->routeIs('my.orders.show') ? 'Yo' : $order->user->fullName() }}</i>
      </p>
    </div>
  @endif
@endsection
