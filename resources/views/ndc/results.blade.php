<x-app-layout>

<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Rezultatet e Kerkimit</h1>

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left">NDC KODI</th>
          <th class="px-6 py-3 text-left">Emri i Produktit</th>
          <th class="px-6 py-3 text-left">Prodhuesi</th>
          <th class="px-6 py-3 text-left">Lloji i Produktit</th>
          <th class="px-6 py-3 text-left">Burimi</th>
          <th class="px-6 py-3 text-left">Veprimet</th>
        </tr>
      </thead>
      <tbody>
        @foreach($results as $result)
        <tr class="border-t hover:bg-gray-50">
          <td class="px-6 py-4">{{$result['ndc_code'] }}</td>
          <td class="px-6 py-4">{{$result['brand_name']}}</td>
          <td class="px-6 py-4">{{$result['labeler_name']}}</td>
          <td class="px-6 py-4">{{$result['product_type']}}</td>
          <td class="px-6 py-4">
            <span class="px-2 py-1 text-sm rounded-full
              @if($result['source'] === 'Database') bg-green-500 text-white
              @elseif($result['source'] === 'OpenFDA') bg-blue-100 text-blue-800
              @else bg-gray-100 text-gray-800 @endif">
              {{$result['source']}}
            </span>
          </td>
          <td class="px-6 py-4">
            @if($result['source'] === 'Database')
            <form action="{{route('ndc.destroy', $result['id'] ?? '')}}" method="POST" onsubmit="return confirm('A jeni sigurt qe doni ta fshihni kete NDC Code?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700"> Fshi</button>
            </form>
            @else
            <span class="text-gray-400 italic">N/A</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <a href="{{route('ndc.search')}}" class="mt-6 block mx-auto px-4 py-2 w-60 bg-blue-500 text-white font-semibold rounded hover:bg-blue-700 transition text-center">
    Kthehu te Kërkimi
  </a>
</div>



</x-app-layout>
