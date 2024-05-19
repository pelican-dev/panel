<footer class="fixed bottom-4 left-0 z-20 w-full dark:border-gray-600" style="padding: 0; background: none; height: auto;">
    <div class="flex items-center justify-center w-full" style="padding: 0.25rem 0;">
        <img src="{{ asset('pelican.ico') }}" alt="Pelican Logo" class="w-6 h-6" style="margin-right: 8px;">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2024
            <a href="https://pelican.dev" class="hover:underline">Pelican</a>
        </span>
    </div>
</footer>

<style>
footer {
    padding: 0;
    margin: 0;
    height: auto;
    background: none;
    bottom: 4px; /* Toegevoegd */
}

footer .flex {
    padding: 0.25rem 0;
    margin: 0;
}

footer img {
    margin-right: 8px;
}

footer span {
    padding: 0;
    margin: 0;
}
</style>
