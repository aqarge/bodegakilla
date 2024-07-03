<?php

use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::redirect('/', RouteServiceProvider::HOME);

Route::get('/ola', function () {
    $data = Debt::first()->products;
    dd($data);
    return response($data);
});

Route::get('/total', function(Request $request) {
    dd($request->all());
})->name('debttotal.total');

Route::get('/total_debt', function(Request $request) {
    $subtotales = DB::table('debtproducts')->where('debt_id', $request->query('debt'))->get();
    $debttotal = Debt::find($request->query('debt'));

    if (!$debttotal) {
        return redirect()->back()->withErrors(['error' => 'Debt not found']);
    }

    $total = 0;
    foreach ($subtotales as $value) {
        $total += $value->subtotal;
    }

    $debttotal->total_debt = $total;
    $debttotal->save();

    return redirect()->back()->with('status', 'Total debt updated successfully');
})->name('debttotal.total');
