<?php

namespace App\Http\Controllers;

use App\Exports\ShippingsExport;
use App\Http\Requests\ShippingExportRequest;
use Maatwebsite\Excel\Excel;

class ShippingController extends Controller
{
    public function export(ShippingExportRequest $request)
    {
        $filters = $request->validated();

        return Excel::download(new ShippingsExport($filters), 'shippings.xlsx');
    }
}
