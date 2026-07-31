@props(['tbody'])

<table
  {{ $attributes->merge(['class' => 'w-full border-separate border-spacing-y-1 [&_th]:py-4 [&_th]:px-3 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800']) }}>
  <thead class="text-sm text-slate-400">
    {{ $thead }}
  </thead>
  <tbody {{ $tbody->attributes->class(['text-sm']) }}>
    {{ $tbody }}
  </tbody>
</table>
