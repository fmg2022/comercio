<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Http\Requests\PaymentExportRequest;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
	public function export(PaymentExportRequest $request)
	{
		$filters = $request->validated();
		return Excel::download(new PaymentsExport($filters), 'payments.xlsx');
	}
}
