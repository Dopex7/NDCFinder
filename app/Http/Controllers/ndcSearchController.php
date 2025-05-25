<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NdcCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ndcSearchController extends Controller
{
    public function __construct()
    {
        // require users to be logged in for any actions here
        $this->middleware('auth');
    }

    // show the search form page
    public function showSearchForm()
    {
        return view('ndc.search');
    }

    // process the submitted search request
    public function handleSearch(Request $request)
    {
        $request->validate([
            'ndc_codes' => 'required|string'
        ]);
        // split inpt by commas, trim spaces
        $ndcCodes = $this->splitInputCodes($request->ndc_codes);
        // fetch all matchng ndcs from local db in one go
        $localNdcRecords = NdcCode::whereIn('ndc_code', $ndcCodes)->get()->keyBy('ndc_code');
        // find codes not in the local db
        $missingCodes = array_filter($ndcCodes, fn($code) => !$localNdcRecords->has($code));
        $results = [];
        // add local results first
        foreach ($localNdcRecords as $ndcRecord) {
            $results[$ndcRecord->ndc_code] = $this->prepareResult($ndcRecord, 'Database');
        }
        // for missing codes, pull data from opnfda api
        if (!empty($missingCodes)) {
            $apiResults = $this->searchFdaApiBulk($missingCodes);

            foreach ($missingCodes as $code) {
                if (isset($apiResults[$code])) {
                    $saved = $this->saveNdcFromApi($code, $apiResults[$code]);
                    $results[$code] = $this->prepareResult($saved, 'OpenFDA');
                } else {
                    // mark as not fond if api returns nothing
                    $results[$code] = $this->notFoundResult($code);
                }
            }
        }

        // keep results in the same order as user input
        $orderedResults = [];
        foreach ($ndcCodes as $code) {
            $orderedResults[] = $results[$code] ?? $this->notFoundResult($code);
        }

        return view('ndc.results', ['results' => $orderedResults]);
    }

    // turn comma separated string into array of trimmed codes
    public function splitInputCodes(string $input): array
    {
        $codes = explode(',', $input);
        return array_map('trim', $codes);
    }

    // query openfda api for multiple ndc codes at once
    public function searchFdaApiBulk(array $codes): array
    {
        $queryParts = array_map(fn($code) => 'product_ndc:"' . $code . '"', $codes);
        $query = implode('+or+', $queryParts);

        $apiUrl = "https://api.fda.gov/drug/ndc.json?search={$query}&limit=100";

        $response = Http::get($apiUrl);

        Log::info('openfda api response:', ['response' => $response->body()]);

        $results = [];

        if ($response->successful() && !empty($response->json('results'))) {
            foreach ($response->json('results') as $item) {
                $ndcCode = $item['product_ndc'] ?? null;

                if ($ndcCode && in_array($ndcCode, $codes)) {
                    $results[$ndcCode] = $item;
                }
            }
        } else {
            Log::warning('openfda api returned no results or failed.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }

        return $results;
    }

    // insert or update ndc data fetched from api
    public function saveNdcFromApi(string $code, array $apiData): NdcCode
    {
        return NdcCode::updateOrCreate(
            ['ndc_code' => $code],
            [
                'brand_name' => $apiData['brand_name'] ?? null,
                'generic_name' => $apiData['generic_name'] ?? null,
                'labeler_name' => $apiData['labeler_name'] ?? null,
                'product_type' => $apiData['product_type'] ?? null,
            ]
        );
    }

    // format a result consistently for the view
    public function prepareResult(NdcCode $ndc, string $source): array
    {
        return [
            'id' => $ndc->id,
            'ndc_code' => $ndc->ndc_code,
            'brand_name' => $ndc->brand_name ?? '-',
            'generic_name' => $ndc->generic_name ?? '-',
            'labeler_name' => $ndc->labeler_name ?? '-',
            'product_type' => $ndc->product_type ?? '-',
            'source' => $source,
        ];
    }

    // create a not found placeholder result
    public function notFoundResult(string $code): array
    {
        return [
            'ndc_code' => $code,
            'brand_name' => '-',
            'generic_name' => '-',
            'labeler_name' => '-',
            'product_type' => '-',
            'source' => 'nuk u gjet',
        ];
    }

    // delete a saved ndc 
    public function destroy($id)
    {
        $ndc = NdcCode::findOrFail($id);
        $ndc->delete();

        return redirect()->back()->with('success', 'ndc code eshte fshire me sukses!');
    }

    // show paginated saved ndc products
    public function savedResults()
    {
        $results = NdcCode::orderBy('created_at', 'desc')->paginate(10);

        return view('ndc.saved_results', compact('results'));
    }
}
