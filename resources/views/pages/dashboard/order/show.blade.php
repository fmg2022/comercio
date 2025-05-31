@extends('layouts.dashboard')

@section('titleH1', 'Detalles de la Orden')

@section('content')
  <article class="px-3">
    <div class="flex justify-between items-center">
      <h2 class="text-3xl font-semibold">Orden #{{ $order->id }}</h2>
      <x-buttons.ancorFill href="{{ route('products.edit', $order->id) }}">
        Editar
      </x-buttons.ancorFill>
    </div>
    <x-tables.table>
      <x-slot:thead>
        <tr>
          <th>#</th>
        </tr>
      </x-slot>

      <tr>
        <td>1</td>
      </tr>
      <tr>
        <td>2</td>
      </tr>
    </x-tables.table>
  </article>
@endsection
