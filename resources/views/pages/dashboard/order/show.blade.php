@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="flex flex-wrap items-center gap-3"
    class="flex justify-between items-center flex-wrap">
    <x-slot:textTitle>
      {{ 'Detalles de la Orden #' . $order->id }}
      <span class="text-base">({{ Str::substr($order->date, 0, 10) }})</span>
    </x-slot:textTitle>
    <div class="flex gap-2">
      <x-buttons.linkFill href="{{ route('orders.index') }}" class="bg-slate-500 active:bg-slate-600">
        Lista de Ordenes
      </x-buttons.linkFill>
      <x-buttons.linkFill href="" class="bg-red-700 active:bg-red-800">
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
        <td class="text-center hidden md:table-cell">${{ $orderLine->pivot->discount_formated }}
        </td>
        <td class="text-center hidden md:table-cell">
          <span class="me-px">$</span>{{ $orderLine->pivot->subtotal() }}
        </td>
        <td>
          <div class="relative flex justify-end items-center">
            <x-popups.contentWcheck iid="chorderline-{{ $order->id }}" labelClass="hover:bg-slate-900"
              class="right-12 -top-1/4">
              <x-slot:label>
                <x-icons.threeDotsX class="size-6" />
              </x-slot:label>

              <ul
                class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-200 font-semibold [&>li]:bg-slate-800 [&>li]:transition-colors">
                <li>
                  <a href="{{ route('products.show', $orderLine->id) }}" class="px-4 py-2.5 flex gap-3">
                    <span>
                      <x-icons.show class="size-5" />
                    </span>
                    Ver Producto
                  </a>
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
@endsection
