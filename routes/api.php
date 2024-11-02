<?php

use App\Http\Controllers\AboutSchoolController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AnnouncmentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryArticleController;
use App\Http\Controllers\ExtracuriculerController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\GelombangController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PendaftaranPageController;
use App\Http\Controllers\PendaftaransRuleController;
use App\Http\Controllers\PendidikanPageController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ProfileSchoolController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\userController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\VisitorController;
use App\Models\Category_article;
use App\Models\Hero;
use App\Models\Prestation;
use App\Models\ProfileSchool;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Laravel\Sanctum\Sanctum;

Route::group(['middleware' => ['auth:sanctum', "isAdmin"]], function () {
    Route::get('/auth-check', function () {
        return response()->json([
            "authenticated" => Auth::check(),
            "user" => Auth::user()
        ]);
    });
    Route::resource('/articles', ArticleController::class)->only(['store', 'update', 'destroy']);
    Route::resource('announcment', AnnouncmentController::class)->except('show', 'index');
    Route::resource('students', StudentController::class)->except('show', 'index');
    Route::get('students/accept/{id}', [StudentController::class, 'accept']);
    Route::get('students/reject/{id}', [StudentController::class, 'reject']);
    Route::resource('/agenda', AgendaController::class)->except('show', 'index');
    Route::resource('/user', userController::class)->only('update', 'destroy');
    Route::resource('/prestasi', PrestationController::class)->except('show', 'index');
    Route::delete('/facilities/galery/{id}', [FacilityController::class, 'deleteGalery']);
    Route::resource('/prestasi', PrestationController::class)->except('show', 'index');
    Route::resource('/extrakurikuler', ExtracuriculerController::class)->except('show', 'index');
    Route::delete('/jurusan/galery/{id}', [JurusanController::class, 'deleteGalery']);
    Route::resource('/jurusan', JurusanController::class)->except('show', 'index');
    Route::resource('/galery', GaleryController::class)->except('show', 'index');
    Route::resource('/video', VideoController::class)->except('show', 'index');
    Route::put('about-school/{id}', [AboutSchoolController::class, 'update']);
    Route::post('hero-images', [HeroController::class, 'store']);
    Route::get('hero-images/{id}', [HeroController::class, 'destroy']);
    Route::get('pendaftaran-page/{id}', [PendaftaranPageController::class, 'destroy']);
    Route::post('pendaftaran-page/{id}', [PendaftaranPageController::class, 'update']);
    Route::post('pendidikan-page/{id}', [PendidikanPageController::class, 'update']);
    Route::resource('pendaftaran-rule', PendaftaransRuleController::class)->except('index', 'show');
    Route::resource('social-media', SocialMediaController::class)->except('index', 'show');
    Route::put('/visi-misi/{id}', [VisiMisiController::class, 'update']);
    Route::post('/todoLists', [TodoListController::class, 'store']);
    Route::delete('/todoLists/{id}', [TodoListController::class, 'destroy']);
    Route::resource('gelombang', GelombangController::class)->only('store', 'update', 'destroy');
});


Route::resource('profile-school', ProfileSchoolController::class);
Route::get('gelombang/switch/{id}', [GelombangController::class, 'switch_status']);
// frontend
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);
Route::get('/', function () {
    return response()->json(['status' => 'success'], 200);
})->middleware('logVisitor');
Route::get('/visitors', [VisitorController::class, 'index']);
Route::resource('/user', userController::class);
Route::resource('/articles', ArticleController::class);
Route::get('/visi-misi', [VisiMisiController::class, 'index']);
Route::post('/logout', [LogoutController::class, 'logout']);
Route::get('/visi-misi/{id}', [VisiMisiController::class, 'show']);
Route::resource('/extrakurikuler', ExtracuriculerController::class);
Route::delete('/extrakurikuler/galery/{id}', [ExtracuriculerController::class, 'deleteGalery']);
Route::post('/login', [LoginController::class, 'login']);
Route::resource('/facilities', FacilityController::class);
Route::resource('/teachers', TeacherController::class);
Route::resource('/agenda', AgendaController::class);
Route::resource('announcment', AnnouncmentController::class);
Route::resource('students', StudentController::class);
Route::resource('/prestasi', PrestationController::class);
Route::put('/prestasi/{id}/update', [PrestationController::class, 'updated']);
Route::resource('/jurusan', JurusanController::class);
Route::resource('/galery', GaleryController::class);
Route::resource('/video', VideoController::class);
Route::get('about-school', [AboutSchoolController::class, 'index']);
Route::get('hero-images', [HeroController::class, 'index']);
Route::get('pendaftaran-page', [PendaftaranPageController::class, 'index']);
Route::resource('gelombang', GelombangController::class);
Route::get('pendidikan-page', [PendidikanPageController::class, 'index']);
Route::resource('pendaftaran-rule', PendaftaransRuleController::class);
Route::resource('social-media', SocialMediaController::class);
Route::resource('alumnis', AlumniController::class);
Route::get('/todoLists', [TodoListController::class, 'index']);
Route::get('/todoLists/{id}', [TodoListController::class, 'checked']);
