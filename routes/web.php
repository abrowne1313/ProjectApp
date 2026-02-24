<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\PupilDataController;
use App\Http\Controllers\ClassListsController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SchemesController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\PupilScoresController;
use App\Http\Controllers\PupilTargetController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('login');
});


Route::middleware(['auth', AdminOnly::class])->get('/admin', [UserDataController::class, 'AdminControls'])
    ->name('AdminControls');




Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');



Route::get('/login', [UserDataController::class, 'showLoginForm'])->name('login');
// Route::get('/login', [UserDataController::class, 'login'])->name('login');
Route::post('/login', [UserDataController::class, 'login'])->name('login.submit');



  Route::middleware('auth')->get('/dashboard', [UserDataController::class, 'index'])
    ->name('dashboard');

  Route::middleware('auth')->get('/classes/{class}', [ClassListsController::class, 'show'])
    ->name('classes.');
  
//Create new user tools- admin only
Route::middleware(['auth', \App\Http\Middleware\AdminOnly::class])->group(function () {
    
Route::get('/createuser', [UserDataController::class, 'CreateUserForm'])->name('CreateUser');

Route::post('/createuser', [UserDataController::class, 'CreateUserForm'])->name('CreateUser');
});

//Edit and update user data- admin only
Route::middleware(['auth'])->group(function () {
Route::get('/EditUserData', [UserDataController::class, 'GetEditUserPage'])->name('EditUser');

   Route::get('/admin/users/{user}/edit', [UserDataController::class, 'edit'])
    ->name('userdata.edit');

    Route::get('/admin/users/search', [UserDataController::class, 'liveSearch'])
    ->name('userdata.liveSearch');

    Route::put('/admin/users/{user}', [UserDataController::class, 'update'])
        ->name('userdata.update');

    Route::post('/admin/users/{user}', [UserDataController::class, 'store'])
        ->name('userdata.store');        
});



Route::middleware(['auth', AdminOnly::class])->group(function () {
Route::get('/ChangeUserPassword', [UserDataController::class, 'ChangeUserPasswordForm']) ->name('ChangeUserPassword'); 

Route::post('/ChangeUserPassword', [UserDataController::class, 'ChangeUserPassword']) ->name('ChangeUserPassword.submit');

Route::get('/createpupil', [PupilDataController::class, 'CreatePupilForm'])->name('CreatePupil');

Route::post('/createpupil', [PupilDataController::class, 'store']) ->name('pupildata.store');

Route::get('/createclass', [ClassListsController::class, 'CreateClassForm'])->name('CreateClass');

Route::post('/createclass', [ClassListsController::class, 'store'])->name('classlists.store');


Route::get('/user_manager', function () {
    return view('admincontrols.UserManager');
})->name('user.manager');

Route::get('/pupil_manager', function () {
    return view('admincontrols.PupilManager');
})->name('pupil.manager');

});   

//CRUD controls for class lists
    Route::middleware(['auth', \App\Http\Middleware\AdminOnly::class])->group(function () {

    Route::get('/class_manager', [ClassListsController::class, 'index'])
        ->name('class.manager');

    Route::get('/class/{class}/edit', [ClassListsController::class, 'edit'])
        ->name('class.edit');

    Route::put('/class/{class}', [ClassListsController::class, 'update'])
        ->name('class.update');

    Route::delete('/class/{class}', [ClassListsController::class, 'destroy'])
        ->name('class.destroy');


// CRUD pupil info to classes



Route::get('/subject_manager', [SubjectController::class, 'index'])
    ->name('subject.manager');



    Route::get('/subjects/create', [SubjectController::class, 'create'])
    ->name('subject.create');
   
    Route::post('/subjects', [SubjectController::class, 'store'])
    ->name('subject.store');


    Route::get('/subject/{subject}/edit', [SubjectController::class, 'edit'])
        ->name('subject.edit');

    Route::put('/subject/{subject}', [SubjectController::class, 'update'])
        ->name('subject.update');

    Route::delete('/subject/{subject}', [SubjectController::class, 'destroy'])
        ->name('subject.destroy');
});

Route::get('/class-pupil-list/{class}', [ClassListsController::class, 'pupils'])
    ->name('class.pupils');


Route::post('/class/{class}/pupil', [ClassListsController::class, 'addPupil'])
    ->name('class.pupil.add');

Route::delete('/class/{class}/pupil/{pupil}', [ClassListsController::class, 'removePupil'])
    ->name('class.pupil.remove');
    



Route::get('/live-search', [SearchController::class, 'live'])
    ->middleware('auth')
    ->name('live.search');

//HoD User routes
Route::get('/SubjectOverview', [SubjectController::class, 'SubjectOverview']) 
->middleware('auth') 
->name('subject.overview');

//HoD Scheme creation routes
Route::get('/HoDControls/CreateScheme', [SchemesController::class, 'create'])->name('schemes.create');
Route::post('/schemes', [SchemesController::class, 'store']);
Route::get('/schemes/{id}', [SchemesController::class, 'show']) ->name('schemes.show');

//Topic lists
Route::get('/topic/{id}', [TopicsController::class, 'show']) ->name('topic.show');

Route::post('/topic/{id}/subtopic', [TopicsController::class, 'storeSubtopic']) ->name('subtopic.store');


//Pupil Scores
Route::post('/class/{id}/scores/save', [PupilScoresController::class, 'saveScores']) 
->name('class.scores.save');

