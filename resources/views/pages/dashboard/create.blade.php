@extends('layouts.dashboard')

@section('titleH1', 'Nuevo Producto')

@section('content')
<section class="@container max-w-xl mx-auto mt-10 bg-red-500">
  <form action="" class="[&>label]:@xl:bg-sky-500">
    <label class="flex flex-col gap-2 mb-5 ">
      <span>Nombre</span>
      <input type="text" name="name">
    </label>
  </form>
</section>
@endsection