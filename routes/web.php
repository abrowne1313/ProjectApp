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
use App\Http\Controllers\RevisionListsController;
use App\Http\Middleware\HodOnly;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Auth;


// ******************{{ CONTROLS FOR STANDARD USERS }}************************************
Route::get('/', function () {
    return view('login');
});
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');



Route::get('/login', [UserDataController::class, 'showLoginForm'])->name('login');

Route::post('/login', [UserDataController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
  
Route::get('/dashboard', [UserDataController::class, 'index'])
    ->name('dashboard');

Route::get('/classes/{class}', [ClassListsController::class, 'show'])
    ->name('classes.');

 Route::get('/changePassword', [UserDataController::class, 'ChangeOwnPassword']) 
    ->name('ChangePassword' );

Route::post('/changePassword', [UserDataController::class, 'ChangeOwnPasswordPost']) 
    ->name('ChangePassword.submit');   

Route::get('/class-pupil-list/{class}', [ClassListsController::class, 'pupils'])
    ->name('class.pupils');


Route::post('/class/{class}/pupil', [ClassListsController::class, 'addPupil'])
    ->name('class.pupil.add');

Route::delete('/class/{class}/pupil/{pupil}', [ClassListsController::class, 'removePupil'])
    ->name('class.pupil.remove');
    

Route::get('/live-search', [SearchController::class, 'live'])->middleware('auth')
    ->name('live.search');   
    //Pupil Scores
Route::post('/class/{id}/scores/save', [PupilScoresController::class, 'saveScores']) 
->name('class.scores.save');

// Pupil overview route
Route::get('/pupils/{pupil}/scores', [PupilScoresController::class, 'overview'])
    ->name('pupil.scores.overview');

// Get data for yeargroup and subjects
Route::get('/pupils/{pupil}/scores/{year}/{subject}', [PupilScoresController::class, 'showYearSubject'])
    ->name('pupil.scores.show');
 

 //Get Teacher Profile page
 Route::get('/userinfo', [UserDataController::class, 'show'] )   
    ->name('userdata.show');


// Revision List Routes


    Route::get('/topics/{topic}/revisionlist', [RevisionListsController::class, 'show'])
        ->name('revisionlists.show');

  
    Route::post('/topics/{topic}/revisionlist', [RevisionListsController::class, 'storeOrUpdate'])
        ->name('revisionlists.save');


    Route::delete('/topics/{topic}/revisionlist', [RevisionListsController::class, 'destroy'])
        ->name('revisionlists.delete');


        Route::get('/topic/{id}', [TopicsController::class, 'show']) ->name('topic.show');

    //Route to generate PDF revision list
Route::get('/pupils/{pupil}/subject/{subjectID}/revision-pack',[PupilScoresController::class, 'revisionPack']) 
->name('pupil.revisionpack');


    
});

  
// ******************{{ CONTROLS FOR ADMIN ONLY }}************************************
Route::middleware(['auth', \App\Http\Middleware\AdminOnly::class])->group(function () {
Route::get('/admin', [UserDataController::class, 'AdminControls'])
    ->name('AdminControls');

 Route::get('/user_manager', function () {return view('AdminControls.UserManager');
})->name('user.manager');

    //Show all info for single user
Route::get('/userinfofull/{id}', [UserDataController::class, 'showAdminView'])   
    ->name('userdata.showAdminView');

Route::get('/pupil_manager', [PupilDataController::class, 'PupilManager'])
    ->name('pupil.manager');  
    
    
Route::delete('/pupils/{id}', [PupilDataController::class, 'destroy'])
    ->name('pupils.destroy');
    // Create new user tools-  
        Route::get('/create-user', [UserDataController::class, 'CreateUserForm'])
            ->name('CreateUser.show');
        
        Route::post('/create-user', [UserDataController::class, 'store'])
            ->name('CreateUser.store');

//Edit and update user data- admin only
        Route::get('/EditUserData', [UserDataController::class, 'GetEditUserPage'])
            ->name('EditUser');

        Route::get('/admin/users/{user}/edit', [UserDataController::class, 'edit'])
            ->name('userdata.edit');

         Route::put('/admin/users/{user}', [UserDataController::class, 'update'])
            ->name('userdata.update'); 

        Route::get('/admin/users/search', [UserDataController::class, 'liveSearch'])
            ->name('userdata.liveSearch');

                    
        Route::delete('/admin/users/{user}', [UserDataController::class, 'destroy'])
            ->name('userdata.delete');      

            //Change any user password controls 
        Route::post('/ChangeUserPassword', [UserDataController::class, 'ChangeUserPassword']) 
            ->name('ChangeUserPassword.submit');

        Route::get('/change-password/{id}', [UserDataController::class, 'showChangePasswordForm'])
            ->name('ChangeUserPassword');

        Route::get('/change-password', [UserDataController::class, 'showChangePasswordForm'])
    ->name('ChangeAnyUserPassword');
        
        //CRUD controls for class lists
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



// ******************{{ CONTROLS FOR HOD USERS }}************************************

Route::middleware(['auth', \App\Http\Middleware\HodOnly::class])->group(function () {

Route::get('/pupil/create', [PupilDataController::class, 'CreatePupilForm'])->name('CreatePupil');

Route::post('/pupil/store', [PupilDataController::class, 'store']) ->name('pupildata.store');


Route::get('/createclass', [ClassListsController::class, 'CreateClassForm'])->name('CreateClass');

Route::post('/createclass', [ClassListsController::class, 'store'])->name('classlists.store');





//HoD User routes
Route::get('/SubjectOverview', [SubjectController::class, 'SubjectOverview']) ->name('subject.overview');

//HoD Scheme creation routes
Route::get('/HoDControls/CreateScheme', [SchemesController::class, 'create'])->name('schemes.create');
Route::post('/schemes', [SchemesController::class, 'store']);
Route::get('/schemes/{id}', [SchemesController::class, 'show']) ->name('schemes.show');
Route::get('/scheme/{id}/edit', [SchemesController::class, 'edit']) ->name('scheme.edit');
Route::put('/schemes/{id}', [SchemesController::class, 'update'])->name('schemes.update');

//Topic lists


Route::post('/topic/{id}/subtopic', [TopicsController::class, 'storeSubtopic']) ->name('subtopic.store');
Route::delete('/scheme/topic/{id}', [SchemesController::class, 'deleteTopic'])
    ->name('scheme.topic.delete');

});