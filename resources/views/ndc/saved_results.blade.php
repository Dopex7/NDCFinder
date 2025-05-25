<x-app-layout>
        <div class="container mx-auto px-4 py-8 flex items-center justify-between">
        <h1 class="text-2xl font-bold mb-0">NDC Produktet e Ruajtura</h1>
         <button id="exportBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition mr-10">
    Exporto CSV
             </button>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table id="resultsTable" class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left">NDC KODI</th>
                        <th class="px-6 py-3 text-left">Emri i Produktit</th>
                        <th class="px-6 py-3 text-left">Prodhuesi</th>
                        <th class="px-6 py-3 text-left">Lloji i Produktit</th>
                        <th class="px-6 py-3 text-left">Veprimet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $result->ndc_code }}</td>
                        <td class="px-6 py-4">{{ $result->brand_name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $result->labeler_name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $result->product_type ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('ndc.destroy', $result->id) }}" method="POST" onsubmit="return confirm('A jeni sigurt qe doni ta fshihni kete NDC Code?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Fshi
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination links -->
        <div class="mt-6">
            {{ $results->links() }}
        </div>

        <a href="{{ route('ndc.search') }}" class="mt-6 block mx-auto px-4 py-2 w-60 bg-blue-500 text-white font-semibold rounded hover:bg-blue-700 text-center">
            Kthehu te Kërkimi
        </a>
    </div>

    <script>
// Logic to export CSV
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('exportBtn').addEventListener('click', function() {
    const rows = document.querySelectorAll('#resultsTable tr');
    let csvContent = '';

    rows.forEach(row => {
      const cols = row.querySelectorAll('th, td');
      let rowData = [];

      cols.forEach((col, index) => {
        // Skip the Veprimet column 
        if (index === 4) return;

        const text = col.innerText.replace(/"/g, '""');
        rowData.push(`"${text}"`);
      });

      csvContent += rowData.join(',') + '\n';
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'tenton_rezultatet.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
});
</script>
</x-app-layout>
