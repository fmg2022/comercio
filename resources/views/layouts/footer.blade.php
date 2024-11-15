<footer
	class="flex flex-col items-center bg-zinc-50 text-center text-surface dark:bg-neutral-700 dark:text-white lg:text-left">
	<div class="container p-3">
		<div class="w-full p-4 mb-6 flex items-center gap-5 bg-black/10 rounded-lg">
			<x-application-logo />
			<h5>Siguenos en: </h5>
			<ul class="flex gap-2">
				<li>YT</li>
				<li>FB</li>
				<li>IG</li>
				<li>TW</li>
				<li>TT</li>
			</ul>
		</div>
		<div class="grid place-items-center md:grid-cols-2">
			<div class="mb-6">
				<h5 class="mb-2.5 font-bold uppercase">Conocenos</h5>
				<ul class="text-center md:text-start">
					<li>
						<x-ancor-link>Historia</x-ancor-link>
					</li>
					<li>
						<x-ancor-link>Nuestro compromiso</x-ancor-link>
					</li>
					<li>
						<x-ancor-link>Preguntas frecuentes</x-ancor-link>
					</li>
					<li>
						<x-ancor-link>Contacto</x-ancor-link>
					</li>
				</ul>
			</div>
			<div class="mb-6">
				<h5 class="mb-2.5 font-bold uppercase">Descubre</h5>
				<ul class="text-center md:text-start">
					<li>
						<x-ancor-link>Eventos</x-ancor-link>
					</li>
					<li>
						<x-ancor-link>Promociones</x-ancor-link>
					</li>
					<li>
						<x-ancor-link>Folletos</x-ancor-link>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="w-full bg-black/5 p-4 text-center">
		© 2024 Copyright:
		<x-ancor-link>Comercio</x-ancor-link>
	</div>
</footer>