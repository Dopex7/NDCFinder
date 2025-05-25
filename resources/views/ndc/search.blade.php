<x-app-layout>
<div class="container mx-auto px-40 py-8">
    <h1 class="text-4xl font-bold text-center">Aplikacioni per Kerkimin e Ilaqeve</h1>

    <form id="ndcForm" method="POST" action="{{ route('ndc.search') }}" class="bg-white p-6 shadow-md mt-20 relative">
        @csrf
        <div class="mb-4 grid-rows-2">
            <label for="ndc_codes" class="block text-2xl font-semibold mb-5">
                Kerko NDC Codes
            </label>

            <div class="flex gap-5 items-center">
                <input type="text" name="ndc_codes" id="ndc_codes"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Shkruaj NDC kodet te ndara me presje, 1234-5234, 9999-0000" required>

                <button id="submitBtn" type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 flex items-center gap-2">
                    <span>Kerko</span>
    
                    <!-- Spinner  -->
                    <svg id="spinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('ndcForm').addEventListener('submit', function () {
        const spinner = document.getElementById('spinner');
        const button = document.getElementById('submitBtn');

        spinner.classList.remove('hidden');
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
    });
</script>
</x-app-layout>
