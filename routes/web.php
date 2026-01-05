<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()){
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
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

        Route::get('/documents/{document}/print', [DocumentController::class, 'print'])
            ->name('print');

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
        Route::post('/disable_user', [UserController::class, 'disableUser'])->name('disabe.user');
        Route::post('/render_relation_data', [UserController::class, 'renderRelationData'])->name('render.relation.data');
        Route::get('/show_ratings/{id}',[UserController::class,'showEmployeeRating'])->name('show.employee.ratings');
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
