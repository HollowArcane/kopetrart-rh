<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Annonce_communicationController;
use App\Http\Controllers\AnnonceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Besoin_posteController;
use App\Http\Controllers\Classification_cvController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\DossiersController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\EntretienController;
use App\Http\Controllers\PromotionController;

use App\Http\Controllers\FrontOffice\MessageController;
use App\Http\Controllers\FrontOffice\UserController;

use App\Http\Controllers\Test\TestCandidateController;
use App\Http\Controllers\Test\TestCandidateFileController;
use App\Http\Controllers\Test\TestController;

use App\Http\Controllers\Classification\DenormalizedCvController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Staff\ContractBreachController;
use App\Http\Controllers\Staff\ContratController;
use App\Http\Controllers\Staff\MvtStaffContractController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\StaffVacationController;

{ // TRASH
    Route::get('/', function () {
    return view('auth.login');
    });

    Route::get('/dashboard', function () { return view ('dashboard'); })->name('dashboard');

    Route::get('besoin_poste', [Besoin_posteController::class, 'index'])->name('besoin_poste.index');
    Route::get('besoin_poste/create', [Besoin_posteController::class, 'create'])->name('besoin_poste.create');
    Route::post('besoin_poste', [Besoin_posteController::class, 'store'])->name('besoin_poste.store');
    Route::get('besoin_poste/{id}/edit', [Besoin_posteController::class, 'edit'])->name('besoin_poste.edit');
    Route::put('besoin_poste/{id}', [Besoin_posteController::class, 'update'])->name('besoin_poste.update');
    Route::delete('besoin_poste/{id}', [Besoin_posteController::class, 'destroy'])->name('besoin_poste.destroy');
    Route::get('besoin_poste/{id}/analyse', [Besoin_posteController::class, 'analyse'])->name('besoin_poste.analyse');

    Route::put('/dossiers/refuser/{id}', [DossiersController::class, 'refuser'])->name('dossiers.refuser');
    Route::get('/dossier/{id}/evaluate', [DossiersController::class, 'create'])->name('dossier.evaluate');

    Route::get('cv', [CvController::class, 'index'])->name('cv.index');
    Route::get('cv/create', [CvController::class, 'create'])->name('cv.create');
    Route::post('cv', [CvController::class, 'store'])->name('cv.store');
    Route::get('cv/{id}/edit', [CvController::class, 'edit'])->name('cv.edit');
    Route::put('cv/{id}', [CvController::class, 'update'])->name('cv.update');
    Route::delete('cv/{id}', [CvController::class, 'destroy'])->name('cv.destroy');
    Route::get('cv/evaluer/{id}', [Classification_cvController::class, 'create'])->name('cv.evaluer');
    Route::put('cv/evaluer/{id}', [Classification_cvController::class, 'store'])->name('evaluation.store');
    Route::get('cv/comparer/{id}', [CvController::class, 'compareForm'])->name('cv.sendToCompare');
    Route::put('cv/{id}/update-status', [CvController::class, 'updateComparaisonStatus'])->name('cv.updateStatus');
    Route::put('cv/{id}/update-informer', [CvController::class, 'updateInformer'])->name('cv.informer');


    Route::get('dossiers', [DossiersController::class, 'index'])->name('dossiers.index');
    Route::get('dossiers/create', [DossiersController::class, 'create'])->name('dossiers.create');
    Route::post('dossiers', [DossiersController::class, 'store'])->name('dossiers.store');
    Route::get('dossiers/{id}/edit', [DossiersController::class, 'edit'])->name('dossiers.edit');
    Route::put('dossiers/{id}', [DossiersController::class, 'update'])->name('dossiers.update');
    Route::delete('dossiers/{id}', [DossiersController::class, 'destroy'])->name('dossiers.destroy');
    Route::put('/dossiers/refuser/{id}', [DossiersController::class, 'refuser'])->name('dossiers.refuser');
    Route::get('/dossier/{id}/evaluate', [DossiersController::class, 'create'])->name('dossier.evaluate');

    Route::get('employe/{id?}', [EmployeController::class, 'index'])->name('employe');
    Route::get('employe/create', [EmployeController::class, 'create'])->name('employe.create');
    Route::post('employe', [EmployeController::class, 'store'])->name('employe.store');
    Route::get('employe/{id}/edit', [EmployeController::class, 'edit'])->name('employe.edit');
    Route::put('employe/{id}', [EmployeController::class, 'update'])->name('employe.update');
    Route::delete('employe/{id}', [EmployeController::class, 'destroy'])->name('employe.destroy');
    Route::get('employe/promotion/{id_employe}/{candidat}/{postePromotion}', [EmployeController::class, 'employePromotion'])->name('employe.promotion');

    Route::get('promotion', [PromotionController::class, 'index'])->name('promotion.index');
    Route::get('promotion/create', [PromotionController::class, 'create'])->name('promotion.create');
    Route::post('promotion', [PromotionController::class, 'store'])->name('promotion.store');
    Route::get('promotion/{id}/edit', [PromotionController::class, 'edit'])->name('promotion.edit');
    Route::put('promotion/{id}', [PromotionController::class, 'update'])->name('promotion.update');
    Route::delete('promotion/{id}', [PromotionController::class, 'destroy'])->name('promotion.destroy');
    Route::put('/promotion/{id}/valider', [PromotionController::class, 'updateStatus'])->name('promotion.updateStatus');

    Route::get('loginPage', [AdminController::class, 'loginPage'])->name('loginPage');
    Route::post('login', [AdminController::class, 'login'])->name('login');
    Route::get('logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('annonce', [AnnonceController::class, 'index'])->name('annonce.index');
    Route::get('annonce/{id?}', [AnnonceController::class, 'create'])->name('annonce.create');
    Route::post('annonce', [AnnonceController::class, 'store'])->name('annonce.store');

    Route::post('/annonce_communication/batch', [Annonce_communicationController::class, 'storeBatch'])->name('annonce_communication.storeBatch');
    Route::get('publicite', [Annonce_communicationController::class, 'publicite'])->name('annonce_communication.publicite');

    Route::get('/evaluation/{id}/create', [Classification_cvController::class, 'create'])->name('classification_cv.evaluate');

    Route::put('/evaluation/{id}', [Classification_cvController::class, 'store'])->name('classification_cv.store');

    Route::get('/entretien/create/{id}', [EntretienController::class, 'create'])->name('entretien.create');
    Route::get('/entretien/select-cv/', [EntretienController::class, 'selectCv'])->name('entretien.select-cv');
    Route::post('/entretien/{id}', [EntretienController::class, 'store'])->name('entretien.store');
    Route::get('/entretien', [EntretienController::class, 'index'])->name('entretien.index');
    Route::get('/entretien/{id}/update-informer', [EntretienController::class, 'updateInformer'])->name('entretien.informer');

    Route::resource('/test-candidate', TestCandidateController::class);

    Route::get('/test', [TestController::class, 'index']);
    Route::get('/test/create', [TestController::class, 'create']);
    Route::get('/test/form1', [TestController::class, 'form1']);
    Route::get('/test/form2', [TestController::class, 'form2']);
    Route::get('/test/form3', [TestController::class, 'form3']);
    Route::get('/test/pdf/{id}', [TestCandidateFileController::class, 'download_pdf']);
    Route::get('/test/{id}', [TestController::class, 'show']);
    Route::post('/test/store1', [TestController::class, 'store1']);
    Route::post('/test/store2', [TestController::class, 'store2']);
    Route::post('/test/store3', [TestController::class, 'store3']);
    Route::post('/test/store3', [TestController::class, 'store3']);
    Route::delete('/test/{id}', [TestController::class, 'destroy']);
    Route::get('/test-candidate-file/test/{id}', [TestCandidateFileController::class, 'download_test']);
    Route::get('/test-candidate-file/cv/{id}', [TestCandidateFileController::class, 'download_cv']);
    Route::get('/test-candidate-file/attachment/{id}', [TestCandidateFileController::class, 'download_attachment']);


    Route::get('/classification/create', [DenormalizedCvController::class, 'create'])->name('classification.create');
    Route::post('/classification', [DenormalizedCvController::class, 'store'])->name('classification.store');
    Route::get('/classification', [DenormalizedCvController::class, 'index'])->name('classification.index');


    Route::get('/notification/{id}', [NotificationController::class, 'see'])->name('notification.see');
}

{ // CONTRACT
    Route::get('/staff', [ StaffController::class, 'index' ]);
    Route::get('/staff/{id}', [ StaffController::class, 'show' ]);
    Route::get('/candidate', [ StaffController::class, 'candidate' ]);

    Route::get('/staff/{id}/contract', [MvtStaffContractController::class, 'create']);
    Route::post('/staff/{id}/contract', [MvtStaffContractController::class, 'store']);
    Route::get('/staff/{id}/contract/pdf', [MvtStaffContractController::class, 'pdf']);
}

{ // CONTRACT BREACH
    Route::get('/contract-breach', [ContractBreachController::class, 'index']);
    Route::get('/contract-breach/{id}', [ContractBreachController::class, 'accept']);
    Route::get('/staff/{id}/contract-breach/create/{type}', [ContractBreachController::class, 'create']);
    Route::post('/staff/{id}/contract-breach', [ContractBreachController::class, 'store']);
    Route::delete('/contract-breach/{id}', [ContractBreachController::class, 'delete']);
    Route::get('/staff/{id}/contract-breach/{id_contract_breach_type}/salary/{today}/{date_expected}/{comment_status}', [ContractBreachController::class, 'salary_bonus']);
}

{ // STAFF VACATION
    Route::get('/staff-vacation', [StaffVacationController::class, 'index']);
    Route::get('/staff-vacation/create', [StaffVacationController::class, 'create']);
    Route::get('/staff-vacation/{id}', [StaffVacationController::class, 'accept']);
    Route::post('/staff-vacation', [StaffVacationController::class, 'store']);
    Route::get('/staff/{id}/vacation/{today}', [StaffVacationController::class, 'vacation_available']);
    Route::delete('/staff-vacation/{id}', [StaffVacationController::class, 'delete']);
}

{ // FRONT OFFICE
    Route::get('/front', [UserController::class, 'index_login']);
    Route::get('/front/login', [UserController::class, 'index_login']);
    Route::get('/front/register', [UserController::class, 'index_register']);
    Route::post('/front/login', [UserController::class, 'login']);
    Route::post('/front/register', [UserController::class, 'register']);

    Route::get('/front/home', [App\Http\Controllers\FrontOffice\CVController::class, 'index']);
    // duplicate route for safety
    Route::get('/front/cv', [App\Http\Controllers\FrontOffice\CVController::class, 'index']);
    Route::get('/front/cv/create', [App\Http\Controllers\FrontOffice\CVController::class, 'create']);
    Route::post('/front/cv', [App\Http\Controllers\FrontOffice\CVController::class, 'store']);

    Route::post('/front/message', [MessageController::class, 'create']);

    Route::get('/front/test', [App\Http\Controllers\FrontOffice\TestController::class, 'index']);
    Route::get('/front/test/{id_test}/{id_cv}', [App\Http\Controllers\FrontOffice\TestController::class, 'edit']);
    Route::post('front/test/{id_test}/{id_cv}', [App\Http\Controllers\FrontOffice\TestController::class, 'update']);
}
