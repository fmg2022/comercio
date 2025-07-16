@extends('layouts.dashboard')

@section('content')
  <article class="flex flex-col justify-center items-center gap-4 md:mb-5 md:flex-row">
    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
      class="size-56 rounded-lg md:size-32">
    <div class="grow">
      <h1 class="text-2xl font-semibold">
        {{ $product->name }}
        <span>{{ $product->mark }}</span>
      </h1>
      <p class="relative mb-4">
        <x-buttons.linkSimple href="{{ route('products.show', $product->id) }}"
          class="text-slate-100 hover:text-purple-500 peer/popup">
          SKU: (el numero obtenido)
        </x-buttons.linkSimple>
        <x-popups.text class="top-full left-0 hidden bg-purple-800/80 peer-hover/popup:inline-block">
          Ver Producto
        </x-popups.text>
      </p>
    </div>
    <div class="mb-4 flex gap-4">
      <x-buttons.linkFill href="{{ route('products.index') }}"
        class="bg-slate-700 active:bg-slate-600">Volver</x-buttons.linkFill>
      <x-buttons.linkFill href="" class="bg-red-700 active:bg-red-800">
        Generar PDF
      </x-buttons.linkFill>
    </div>
  </article>

  @if ($orders->count() > 0)
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>#</th>
          <th>Usuario</th>
          <th>Orden</th>
          <th>Fecha</th>
          <th class="hidden md:table-cell">Estado</th>
          <th>Cantidad</th>
          <th class="hidden sm:table-cell">Precio</th>
        </tr>
      </x-slot:thead>

      @foreach ($orders as $index => $order)
        <tr>
          @php
            $orderDate = Str::substr($order->date, 0, 10);
          @endphp
          <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</td>
          <td class="relative">
            <x-buttons.linkSimple href="" class="text-slate-100 hover:text-purple-500 peer/popup">
              {{ $order->user->fullName() }}
            </x-buttons.linkSimple>
            <x-popups.text class="top-3/4 left-1/4 hidden bg-purple-800/80 peer-hover/popup:inline-block">
              Ver Perfil
            </x-popups.text>
          </td>
          <td class="relative">
            <x-buttons.linkSimple href="{{ route('orders.show', $order->id) }}"
              class="text-slate-100 hover:text-purple-500 peer/popup">
              #{{ $order->id }}
            </x-buttons.linkSimple>
            <x-popups.text class="top-3/4 left-1/4 hidden bg-purple-800/80 peer-hover/popup:inline-block">
              Ver Orden
            </x-popups.text>
          </td>
          <td>{{ $orderDate }}</td>
          <td class="hidden md:table-cell">
            <span @class([
                "px-2 py-1 mx-auto font-semibold rounded-xl before:content-['●'] before:me-px",
                'bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100' =>
                    $order->orderStatus->name === 'Pendiente',
                'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' =>
                    $order->orderStatus->name === 'Procesando',
                'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' =>
                    $order->orderStatus->name === 'Completo',
                'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100' =>
                    $order->orderStatus->name === 'Delivery',
                'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100' =>
                    $order->orderStatus->name === 'Retirar',
                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' =>
                    $order->orderStatus->name === 'Entregado',
                'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' =>
                    $order->orderStatus->name === 'Cancelado',
            ])>
              {{ $order->orderStatus->name }}
            </span>
          </td>
          <td><span class="ms-2">{{ $order->pivot->quantity }}</span></td>
          <td class="hidden sm:table-cell">{{ $order->pivot->price }}</td>
        </tr>
      @endforeach
      </x-tables-table>
    @else
      <h3 class="text-xl font-semibold text-center">No hay ordenes encotradas</h3>
  @endif
@endsection
