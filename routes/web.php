<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentChangeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Mail\MonthlyDocumentsReportMail;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()){
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/phpinfo', function (){
    dd([
        ini_get('upload_max_filesize'),
        ini_get('post_max_size')
    ]);
    phpinfo();
});

Auth::routes(['register' => false]);

Route::get('/test_email_send', function (){
    // =========================
    // PERIOD: PREVIOUS MONTH
    // =========================
    $start = Carbon::now()->subMonth()->startOfMonth();
    $end   = Carbon::now()->subMonth()->endOfMonth();

    // =========================
    // LOAD USERS WITH SETTINGS
    // =========================
    $users = User::with([
        'companies' => function ($q) {
            $q->wherePivot('receive_report', true);
        },
        'contractTypes' => function ($q) {
            $q->wherePivot('receive_report', true);
        },
    ])->get();

    foreach ($users as $user) {

        // თუ არაფერი აქვს მონიშნული — გამოტოვე
        if ($user->companies->isEmpty() || $user->contractTypes->isEmpty()) {
            continue;
        }

        $allowedCompanyIds = $user->companies->pluck('id')->values();
        $allowedTypeIds    = $user->contractTypes->pluck('id')->values();

        $report = [];

        // =========================
        // PER COMPANY
        // =========================
        foreach ($user->companies as $company) {

            // დამატებითი დაცვა (FK პრობლემების თავიდან ასაცილებლად)
            if (!$allowedCompanyIds->contains($company->id)) {
                continue;
            }

            $documents = $company->documents()
                ->whereBetween('documents.created_at', [$start, $end])
                ->whereIn('documents.contract_type_id', $allowedTypeIds)
                ->with('contractType')
                ->get();

            if ($documents->isEmpty()) {
                continue;
            }

            $grouped = $documents->groupBy('contract_type_id');

            foreach ($grouped as $docs) {
                $report[] = [
                    'company'       => $company->company_name,
                    'contract_type' => $docs->first()->contractType->contract_type_name ?? 'N/A',
                    'total'         => $docs->count(),
                ];
            }
        }

        // =========================
        // SEND EMAIL IF HAS DATA
        // =========================
        if (empty($report)) {
            continue;
        }
        try {

            Mail::to('giodevadze01@gmail.com')
                ->send(new MonthlyDocumentsReportMail($report, $start));

        } catch (\Throwable $e) {

            dd([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

        }
        return $report;

    }
});


Route::get('locale/{locale}', function ($locale) {
    session(['locale' => $locale]);
    App::setLocale($locale);
    return redirect()->back();
})->name('locale');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth', 'locale'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/company/{id}', [DashboardController::class, 'company'])->name('company');
    Route::get('/change-password', [UserProfileController::class, 'changePassword'])->name('change.password');
    Route::post('/update-password', [UserProfileController::class, 'updatePassword'])->name('update.password');

    Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/ajax', [DocumentController::class, 'ajax'])->name('ajax');
        Route::get('/download/{document}', [DocumentController::class, 'download'])->name('download');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/upload-temp', [DocumentController::class, 'uploadTemp'])->name('uploadTemp');

        Route::post('/edit', [CompanyController::class, 'editRender'])->name('edit');
        Route::post('/store', [DocumentController::class, 'storeAjax'])->name('store');
        Route::post('/{id}/update', [CompanyController::class, 'update'])->name('update');
        Route::post('/delete', [CompanyController::class, 'delete'])->name('delete');

        Route::get('/{document}/request-change', [DocumentController::class, 'requestChange'])->name('requestChange');
        Route::post('/{document}/update-request-change', [DocumentController::class, 'updateRequestChange'])->name('update.requestChange');


        Route::get('/documents/{document}/print', [DocumentController::class, 'print'])
            ->name('print');

        Route::delete('/{document}',
            [DocumentController::class, 'destroy']
        )->name('destroy');

        Route::get('/trash',
            [DocumentController::class, 'trash']
        )->name('trash');

        Route::post('/{id}/restore',
            [DocumentController::class, 'restore']
        )->name('restore');

        // Force delete
        Route::delete('/{id}/force-delete',
            [DocumentController::class, 'forceDelete']
        )->name('forceDelete');


    });

    Route::group(['prefix' => 'document/changes', 'as' => 'document.changes.'], function () {
        Route::get('/pending',
            [DocumentChangeController::class, 'index']
        )->name('index');

        Route::get('/{change}',
            [DocumentChangeController::class, 'show']
        )->name('show');

        Route::post('/{change}/approve',
            [DocumentChangeController::class, 'approve']
        )->name('approve');

        Route::post('/{change}/reject',
            [DocumentChangeController::class, 'reject']
        )->name('reject');
    });

    Route::group(['prefix' => 'companies', 'as' => 'companies.'], function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::post('/ajax', [CompanyController::class, 'ajax'])->name('ajax');
        Route::get('/create', [CompanyController::class, 'create'])->name('create');
        Route::post('/edit', [CompanyController::class, 'editRender'])->name('edit');
        Route::post('/store', [CompanyController::class, 'store'])->name('store');
        Route::post('/{id}/update', [CompanyController::class, 'update'])->name('update');
        Route::post('/delete', [CompanyController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'contract_types', 'as' => 'contract_types.'], function () {
        Route::get('/', [ContractTypeController::class, 'index'])->name('index');
        Route::post('/ajax', [ContractTypeController::class, 'ajax'])->name('ajax');
        Route::get('/create', [ContractTypeController::class, 'create'])->name('create');
        Route::post('/edit', [CompanyController::class, 'editRender'])->name('edit');
        Route::post('/store', [ContractTypeController::class, 'store'])->name('store');
        Route::post('/{id}/update', [CompanyController::class, 'update'])->name('update');
        Route::post('/delete', [CompanyController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/documents', [ReportController::class, 'monthlyDocuments'])->name('documents');
        Route::post('/ajax', [ForestController::class, 'ajax'])->name('ajax');
        Route::post('/create', [ForestController::class, 'createRender'])->name('create');
        Route::post('/edit', [ForestryAdministrationController::class, 'editRender'])->name('edit');
        Route::post('/store', [ForestController::class, 'store'])->name('store');
        Route::post('/{id}/update', [ForestryAdministrationController::class, 'update'])->name('update');
        Route::post('/delete_blog', [ForestryAdministrationController::class, 'deleteBlog'])->name('delete');
    });

    Route::group(['prefix' => 'users','as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [UserController::class, 'update'])->name('update');
        Route::post('/users_ajax', [UserController::class, 'getUsersForAjax'])->name('ajax');
        Route::post('/delete_user', [UserController::class, 'deleteUser'])->name('delete.user');
        Route::post('/disable_user', [UserController::class, 'disableUser'])->name('disable.user');
        Route::get('/report_settings/{id}', [UserController::class, 'reportSettings'])->name('report.settings');
        Route::put('/report_settings/{user}/update', [UserController::class, 'updateReportSettings'])->name('update.report.settings');
        Route::get('/impersonate/{id}', function ($id) {
            $user = User::findOrFail($id);
            Auth::user()->impersonate($user);
            return redirect(url('/dashboard'));
        });
        Route::get('/impersonate_leave', function () {
            Auth::user()->leaveImpersonation();
            return redirect(url('/dashboard'));
        });
        Route::get('/test', function () {
            $user = User::role(6)->get();
            return $user;
        });
    });

    Route::resource('roles', RoleController::class);

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {



        Route::group(['prefix' => 'languages', 'as' => 'languages.'], function () {
            Route::get('/', [LanguageController::class, 'index'])->name('index');
            Route::get('/{code}/show', [LanguageController::class, 'show'])->name('show');
            Route::post('/show/ajax', [LanguageController::class, 'languageJsonAjax'])->name('show.ajax');
            Route::post('/create_language_json', [LanguageController::class, 'createLanguageJson'])->name('create.language.json');
            Route::post('/edit_language_json', [LanguageController::class, 'editLanguageJson'])->name('edit.language.json');
            Route::post('/store_language_json/{id}', [LanguageController::class, 'storeLanguageJson'])->name('store.language.json');
            Route::post('/update_language_json/{id}', [LanguageController::class, 'updateLanguageJson'])->name('update.language.json');
            Route::post('/delete_language_json', [LanguageController::class, 'deleteLanguageJson'])->name('delete.language.json');

            Route::get('/json_lang_import', [LanguageController::class, 'jsonLangImport'])->name('json.lang.import');
        });


    });
});
