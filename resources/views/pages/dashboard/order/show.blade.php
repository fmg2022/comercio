@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="flex flex-wrap items-center gap-3"
    class="flex justify-between items-center flex-wrap">
    <x-slot:textTitle>
      {{ 'Detalles de la Orden #' . $order->id }}
      <span class="text-base">({{ $order->date->format('d/m/Y') }})</span>
    </x-slot:textTitle>
    <div class="flex gap-4">
      <x-buttons.linkFill href="{{ url()->previous() }}" class="bg-slate-500 active:bg-slate-600">
        Volver
      </x-buttons.linkFill>
      @if (request()->routeIs('my.orders.show'))
        <form action="{{ route('carts.addFromOrder') }}" method="POST">
          @csrf
          <input type="hidden" name="order_id" value="{{ $order->id }}">
          <button type="submit" class="px-3 py-2 flex items-center gap-2 rounded-md bg-green-800 hover:bg-green-700">
            Agregarlo a
            <x-icons.cart class="size-6" />
          </button>
        </form>
      @endif
      <x-buttons.linkFill href="{{ route('pdf.order', $order->id) }}" class="bg-red-700 active:bg-red-800"
        target="_blank">
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
    </x-slot:thead>
    <x-slot:tbody>
      @foreach ($order->products as $index => $orderLine)
        <tr class="[&>td]:text-slate-200">
          <td class="text-center">{{ $index + 1 }}</td>
          <td>
            <div class="flex items-center flex-wrap gap-2 text-base">
              <img src="{{ asset('images/products/' . $orderLine->image) }}" alt="{{ $orderLine->name }}"
                class="w-16 h-16 object-cover hidden lg:block">
              <span class="text-slate-400 font-semibold">{{ $orderLine->name }}</span>
              <span class="me-2 font-bold">{{ $orderLine->brand->name }}</span>
            </div>
          </td>
          <td class="text-center">{{ $orderLine->pivot->quantity }}</td>
          <td class="text-center font-bold"><span
              class="me-px">$</span>{{ number_format($orderLine->pivot->price, 2, ',', '.') }}</td>
          <td class="relative text-center hidden md:table-cell">
            <span class="peer/popup">
              ${{ number_format($orderLine->pivot->discount, 2, ',', '.') }}
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

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
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
    </x-slot:tbody>
  </x-tables.table>
  <section class="grid grid-cols-[repeat(auto-fit,minmax(500px,1fr))] gap-4 place-content-center">
    @if (isset($order->notes))
      <div class="px-4 py-5 flex flex-col gap-3 bg-slate-800 rounded-b-xl">
        <h3 class="text-xl text-slate-300 font-semibold underline underline-offset-4">Notas adicionales</h3>
        <p>"{{ $order->notes }}"</p>
        <p>De: <i
            class="text-sm font-medium">{{ request()->routeIs('my.orders.show') ? 'Yo' : $order->user->fullName() }}</i>
        </p>
      </div>
    @endif
    <div class="max-w-xs px-4 py-5 grid grid-cols-2 self-start justify-self-end rounded-b-xl font-medium bg-slate-800">
      <div class="[&>p]:ps-4">
        <p>Total (sin IVA)</p>
        <p>IVA (21%)</p>
        <p class="pt-2 mt-4 text-lg font-bold border-t-2 border-slate-600">Total</p>
      </div>
      <div class="text-end [&>p]:ps-6 [&>p]:pe-4">
        <p>$ {{ number_format($order->total, 2, ',', '.') }}</p>
        <p>$ {{ number_format($order->iva, 2, ',', '.') }}</p>
        <p class="pt-2 mt-4 text-lg font-bold border-t-2 border-slate-600">$
          {{ number_format($order->totalWithIva, 2, ',', '.') }}
        </p>
      </div>
    </div>
  </section>
@endsection
