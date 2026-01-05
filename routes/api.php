<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/oris/databases', function (Request $request){
    info($request->all());
//    $json = [
//        [
//            "databases" =>  [
//                "205207970 ABBEY ASSET MANAGEMENT LLC",
//                "245549027 ADJARA TOUR JSC",
//                "345705846 KORHAN SIMSEK P/E",
//                "VIS A VIS JSC"
//            ]
//        ]];

    $json = $request->databases;
    foreach ($json as $db){
        if (preg_match('/^\d+/', $db)) {
            [$code, $companyName] = explode(' ', $db, 2);
            $company = \App\Models\Company::updateOrCreate([
                'identification_code' => $code
            ]);
            $company->translations()->create([
                'locale' => 'ka',
                'content' => [
                    'company_name' => $companyName,
                ],
            ]);
            echo $code.' - '.$companyName.'<br>';
        }

    }
//    return $databases;
});
