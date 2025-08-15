<form
  {{ $attributes->merge([
      'class' =>
          'max-w-md mx-auto p-3 grid gap-4 grid-cols-1 place-items-center border border-purple-900/40 rounded-xl sm:grid-cols-2 sm:w-full',
      'autocomplete' => 'off',
  ]) }}>
  {{ $slot }}
</form>
