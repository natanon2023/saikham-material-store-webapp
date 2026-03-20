<?php

use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Technician\DashboardController as TechnicianDashboard;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MaterialTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;

use App\Http\Controllers\Technician\ProjectController as TechnicianProjectController;

use App\Models\Customer;
use GuzzleHttp\Handler\Proxy;
use Laravel\Jetstream\Rules\Role;
use PHPUnit\TextUI\XmlConfiguration\Group;



Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');


Route::get('/', [CustomerController::class, 'publicPage'])->name('home');
Route::get('/check-status', [CustomerController::class, 'cakestatuspage'])->name('customer.cakestatuspage');
Route::get('/project-detail/{id}', [CustomerController::class, 'projectDetail'])->name('customer.projectdetail');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'technician' => redirect()->route('technician.dashboard'),
        };
    })->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

    Route::get('/admin/get-amphures/{province_id}', [UserController::class, 'getAmphures']);
    Route::get('/admin/get-tambons/{amphure_id}', [UserController::class, 'getTambons']);

    Route::prefix('admin')->name('admin.users.')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('index');
        Route::get('users/create', [UserController::class, 'create'])->name('create');
        Route::post('users', [UserController::class, 'store'])->name('store');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('users-trash', [UserController::class, 'trash'])->name('trash');
        Route::post('users-restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::get('users/{id}/show', [UserController::class, 'show'])->name('show');
    });

    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    Route::prefix('materials')->name('admin.materials.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');

        Route::get('/{id}/showdetailmaterial',[MaterialController::class,'showdetailmaterial'])->name('showdetailmaterial');

        Route::get('/{id}/editmaterial', [MaterialController::class,'editmaterial'])->name('editmaterial');
        Route::put('/{id}/material', [MaterialController::class, 'updatematerial'])->name('updatematerial');

        Route::get('trash', [MaterialController::class, 'trash'])->name('trash'); 
        Route::put('restore/{id}', [MaterialController::class, 'restore'])->name('restore'); 
         Route::delete('delete/{id}', [MaterialController::class, 'destroy'])->name('destroy'); 


        Route::get('/showselecttype',[MaterialController::class,'showselecttypematerials'])->name('showselecttypematerials');

        Route::get('/formaluminium',[MaterialController::class,'formaluminium'])->name('formaluminium');
        Route::post('/createaluminium',[MaterialController::class,'createaluminium'])->name('createaluminium');

        Route::get('/formglass',[MaterialController::class,'formglass'])->name('formglass');
        Route::post('/createglass',[MaterialController::class,'createglass'])->name('createglass');

        Route::get('/formaccessory',[MaterialController::class,'formaccessory'])->name('formaccessory');
        Route::post('/createaccessory',[MaterialController::class,'createaccessory'])->name('createaccessory');

        Route::get('/formtool',[MaterialController::class,'formtool'])->name('formtool');
        Route::post('/createtool',[MaterialController::class,'createtool'])->name('createtool');

        Route::get('/formconsumable',[MaterialController::class,'formconsumable'])->name('formconsumable');
        Route::post('/createconsumable',[MaterialController::class,'createconsumable'])->name('createconsumable');

        Route::get('/addstockpage',[MaterialController::class,'addstockpage'])->name('addstockpage');
        Route::post('/addstock',[MaterialController::class,'addstock'])->name('addstock');
        Route::get('/historystock',[MaterialController::class,'historystock'])->name('historystock');
        Route::get('/formeditsatock/{id}',[MaterialController::class,'formeditsatock'])->name('formeditsatock');
        Route::post('/stock/edit/{id}', [MaterialController::class, 'editstock'])->name('editstock');

        
        
    });

    Route::prefix('project')->name('admin.projects.')->group(function(){
        Route::get('/index/{id}', [ProjectController::class, 'index'])->name('index');

        Route::get('/formpendingsurvey',[ProjectController::class,'formpendingsurvey'])->name('formpendingsurvey');
        Route::post('/pendingsurvey',[ProjectController::class,'pendingsurvey'])->name('pendingsurvey');

        Route::get('/formnewcustomer',[ProjectController::class,'formnewcustomer'])->name('formnewcustomer');
        Route::post('/createnewcustomer',[ProjectController::class,'createnewcustomer'])->name('createnewcustomer');
        Route::get('/customer/edit/{id}', [ProjectController::class, 'editcustomer'])->name('editcustomer');
        Route::put('/customer/update/{id}', [ProjectController::class, 'updatecustomer'])->name('updatecustomer');
        Route::get('/customer/delete/{id}', [ProjectController::class, 'deletecustomer'])->name('deletecustomer');
        Route::get('/customer/restore/{id}', [ProjectController::class, 'restorecustomer'])->name('restorecustomer');

        Route::get('/customer/projecteditcustomer/{id}', [ProjectController::class, 'projecteditcustomer'])->name('projecteditcustomer');
        Route::put('/customer/updatecustomerproject/{id}', [ProjectController::class, 'updatecustomerproject'])->name('updatecustomerproject');
        

        Route::get('/formprojectexpense/{id}',[ProjectController::class,'formprojectexpense'])->name('formprojectexpense');
        Route::post('/createprojectexpense',[ProjectController::class,'createprojectexpense'])->name('createprojectexpense');
        Route::get('/formeditProjectexpense/{id}',[ProjectController::class,'formeditProjectexpense'])->name('formeditProjectexpense');
        Route::put('/projectexpense/update/{id}', [ProjectController::class, 'editprojectexpense'])->name('projectexpense.update');
        Route::delete('/projectexpense/{id}', [ProjectController::class, 'deleteProjectExpense']) ->name('deleteProjectExpense');

        Route::post('/{id}/waiting-survey', [ProjectController::class, 'updatestatuswaiting_survey'])->name('updatestatuswaiting_survey');

        Route::get('/expensedetail/{id}',[ProjectController::class,'expensedetail'])->name('expensedetail');

        Route::get('/formexpense/{id}',[ProjectController::class,'formexpense'])->name('formexpense');
        Route::post('/createexpense',[ProjectController::class,'createexpense'])->name('createexpense');

        Route::get('/formprojectname',[ProjectController::class,'formprojectname'])->name('formprojectname');
        Route::post('/createprojectname',[ProjectController::class,'createprojectname'])->name('createprojectname');
        Route::put('/admin/projects/update-name/{id}', [ProjectController::class, 'updateprojectname'])->name('admupdateprojectname');
        Route::get('/admin/projects/delete-name/{id}', [ProjectController::class, 'deleteprojectname'])->name('deleteprojectname');
        Route::get('/admin/projects/restore-name/{id}', [ProjectController::class, 'restoreprojectname'])->name('restoreprojectname');
        

        Route::get('/formsurveying/{id}',[ProjectController::class,'formsurveying'])->name('formsurveying');


        Route::get('/formprojectimage/{id}',[ProjectController::class,'formprojectimage'])->name('formprojectimage');
        Route::get('/formeditprojectimage/{id}',[ProjectController::class,'formeditprojectimage'])->name('formeditprojectimage');
        Route::put('/image/update/{id}', [ProjectController::class, 'updateprojectimage'])->name('updateprojectimage');
        Route::post('/createprojectimage',[ProjectController::class,'createprojectimage'])->name('createprojectimage');
        Route::delete('/deleteprojectimage/{id}',[ProjectController::class,'deleteprojectimage'])->name('deleteprojectimage');

        Route::get('/formprojectimagedetail/{id}',[ProjectController::class,'formprojectimagedetail'])->name('formprojectimagedetail');
        Route::post('/createprojectimagedetail',[ProjectController::class,'createprojectimagedetail'])->name('createprojectimagedetail');

        Route::get('/formcustomerneed/{id}',[ProjectController::class,'formcustomerneed'])->name('formcustomerneed');
        Route::post('/addcustomerneed',[ProjectController::class,'addcustomerneed'])->name('addcustomerneed');
        Route::get('/editformcustomerneed/{id}',[ProjectController::class,'editformcustomerneed'])->name('editformcustomerneed');
        Route::put('/updatecustomerneed/{id}', [ProjectController::class, 'updatecustomerneed'])->name('updatecustomerneed');
        Route::delete('deletecustomerneed/{id}',[ProjectController::class,'deletecustomerneed'])->name('deletecustomerneed');

        Route::get('/formcustomerneeddetial/{id}',[ProjectController::class,'formcustomerneeddetial'])->name('formcustomerneeddetial');
        Route::post('/addcustomerneeddetial',[ProjectController::class,'addcustomerneeddetial'])->name('addcustomerneeddetial');

        Route::get('/formproductset',[ProjectController::class,'formproductset']) ->name('formproductset');
        Route::get('/productset/{id}/edit',[ProjectController::class,'formeditproductset']) ->name('formeditproductset');
        Route::put('/productset/{id}',[ProjectController::class, 'editproductset'])->name('editproductset');
        Route::delete('/productset/{id}',[ProjectController::class, 'deleteproductset'])->name('deleteproductset');
        Route::get('/productset/deleted',[ProjectController::class,'showdeletproductset']) ->name('showdeletproductset');
        Route::put('/productset/restore/{id}', [ProjectController::class, 'restoreproductset'])->name('restoreproductset');

        


        Route::get('/formproductsetname',[ProjectController::class,'formproductsetname']) ->name('formproductsetname');
        Route::post('/createproductsetname',[ProjectController::class,'createproductsetname'])->name('createproductsetname');

        Route::get('/productsetdetail',[ProjectController::class,'productsetdetail'])->name('productsetdetail');

        Route::post('/createproductset',[ProjectController::class,'createproductset']) ->name('createproductset');
        Route::get('/formaddproductsetitem/{id}',[ProjectController::class,'formaddproductsetitem']) ->name('formaddproductsetitem');
        Route::delete('/deletematerialproductsetitem/{id}',[ProjectController::class,'deletematerialproductsetitem'])->name('deletematerialproductsetitem');
        
        Route::get('/showitemproduct/{id}',[ProjectController::class,'showitemproduct'])->name('showitemproduct');
    
        Route::post('/addmaterialproductsetitem',[ProjectController::class,'addmaterialproductsetitem'])->name('addmaterialproductsetitem');

        Route::post('/updatestatussurveying/{id}',[ProjectController::class,'updatestatussurveying']) ->name('updatestatussurveying');

        Route::post('/updatestatuspendingquotation',[ProjectController::class,'updatestatuspendingquotation']) ->name('updatestatuspendingquotation');

        Route::get('/bidpage/{id}',[ProjectController::class,'bidpage']) ->name('bidpage');

        Route::post('/satatuswaitingapproval',[ProjectController::class,'satatuswaitingapproval'])->name('satatuswaitingapproval');
        Route::get('/detail/{id}', [ProjectController::class, 'projectalldetail'])->name('alldetail');

        Route::post('/addautersurver',[ProjectController::class,'addautersurver'])->name('addautersurver');

        Route::get('/addbid/{id}',[ProjectController::class,'addbid'])->name('addbid');
        Route::get('/addbiddocument/{id}',[ProjectController::class,'addbiddocument'])->name('addbiddocument');
        Route::post('/projects/{id}/revise-quotation', [ProjectController::class, 'reviseQuotation'])->name('reviseQuotation');

        Route::post('{id}/approved',[ProjectController::class,'updatestatusapproved']) ->name('updatestatusapproved');

        Route::post('{id}/materialplanning',[ProjectController::class,'updatestatusmaterialplanning']) ->name('updatestatusmaterialplanning');
        Route::get('/materialplanningpage/{id}',[ProjectController::class,'materialplanningpage'])->name('materialplanningpage');
        Route::get('/materialplanningpagedocument/{id}',[ProjectController::class,'materialplanningpagedocument'])->name('materialplanningpagedocument');

        Route::post('{id}/waitingpurchase', [ProjectController::class, 'updatestatuswaitingpurchase'])->name('updatestatuswaitingpurchase');

        Route::post('{id}/readytowithdraw', [ProjectController::class, 'updatestatusreadytowithdraw']) ->name('updatestatusreadytowithdraw');

        Route::get('{id}/withdraw', [ProjectController::class, 'withdrawpage'])->name('withdrawpage');
        Route::get('projects/{id}/withdrawtools', [ProjectController::class, 'withdrawtoolspage'])->name('withdrawtoolspage');
        Route::post('projects/{id}/withdrawtools/store', [ProjectController::class, 'withdrawtoolsstore'])->name('withdrawtoolsstore');
        
        Route::get('{id}/installingpage', [ProjectController::class, 'installingpage'])->name('installingpage');
        Route::post('/project/{id}/assign-installer', [ProjectController::class, 'assignInstalleruser'])->name('assignInstalleruser');
        Route::delete('/project/{id}/remove-installer', [ProjectController::class, 'removeInstaller'])->name('removeinstaller');


        Route::put('{id}/assign-installer',[ProjectController::class, 'assignInstaller'])->name('assignInstaller');

        Route::post('{id}/installing', [ProjectController::class, 'updatestatusinstalling'])->name('updatestatusinstalling');

        Route::post('{id}/completed', [ProjectController::class, 'updatestatuscompleted'])->name('updatestatuscompleted');

        Route::post('{id}/cancelled', [ProjectController::class, 'updatestatuscancelled'])->name('updatestatuscancelled');

        Route::put('/updateProjectPendingSurvey/{id}', [ProjectController::class, 'updateProjectPendingSurvey'])->name('updateProjectPendingSurvey');

        Route::get('/formdetialexpense/{id}',[ProjectController::class,'formdetialexpense'])->name('formdetialexpense');
        Route::post('/createdetialexpense',[ProjectController::class,'createdetialexpense'])->name('createdetialexpense');
        Route::get('/formeditdetialexpense/{id}',[ProjectController::class,'formeditdetialexpense'])->name('formeditdetialexpense');
        Route::put('/detialexpense/update/{id}', [ProjectController::class, 'editdetialexpense'])->name('editdetialexpense.update');
        Route::delete('/deletedetialexpense/{id}', [ProjectController::class, 'deletedetialexpense']) ->name('deletedetialexpense');

        Route::get('delete-expense/{id}', [ProjectController::class, 'deleteexpense'])->name('deleteexpense');
        Route::get('restore-expense/{id}', [ProjectController::class, 'restoreexpense'])->name('restoreexpense');
        Route::put('expense/update/{id}', [ProjectController::class, 'updateexpense'])->name('updateexpense');

        Route::get('/{id}/choosetypeissues', [ProjectController::class, 'choosetypeissues'])->name('choosetypeissues');
        Route::get('/admin/projects/{id}/issues/create', [ProjectController::class, 'createIssue'])->name('issues.create');
        Route::get('/admin/projects/{id}/issues/generalissues', [ProjectController::class, 'generalissues'])->name('generalissues');
        Route::post('/admin/projects/{id}/issues', [ProjectController::class, 'storeIssue'])->name('issues.store');
        Route::post('/admin/projects/{id}/storegeneralissues', [ProjectController::class, 'storegeneralissues'])->name('storegeneralissues');

        Route::get('/manageproblemsindex', [ProjectController::class, 'manageproblemsindex'])->name('manageproblemsindex');
        Route::get('projects/issues/{project_id}/detail', [ProjectController::class, 'issuedetail'])->name('issues.detail');
        Route::delete('projects/issues/{id}', [ProjectController::class, 'destroyissue'])->name('issues.destroy');
        Route::post('projects/issues/{id}/restore', [ProjectController::class, 'restoreissue'])->name('issues.restore');

        Route::get('projects/issues/{project_id}/showissuedetail', [ProjectController::class, 'showissuedetail'])->name('showissuedetail');
        Route::get('projects/issues/{id}/edit', [ProjectController::class, 'editIssue'])->name('issues.edit');
        Route::post('projects/issues/{id}/update', [ProjectController::class, 'updateIssue'])->name('issues.update');
        Route::get('projects/issues/{id}/refill', [ProjectController::class, 'refillIssue'])->name('issues.refill');
        Route::post('projects/issues/{id}/refill-store', [ProjectController::class, 'storeRefillIssue'])->name('issues.refill.store');
        Route::post('projects/issues/{id}/refill-undo', [ProjectController::class, 'undoRefillIssue'])->name('issues.refill.undo');
        Route::post('projects/issues/{id}/updateIssuegeneralproblems', [ProjectController::class, 'updateIssuegeneralproblems'])->name('issues.updateIssuegeneralproblems');
        Route::post('projects/issues/{id}/updateresolved', [ProjectController::class, 'updateresolved'])->name('updateresolved');
        Route::post('projects/issues/{id}/undoIssuegeneralproblems', [ProjectController::class, 'undoIssuegeneralproblems'])->name('undoIssuegeneralproblems');



        Route::get('adminfulleventcalendarpage',[ProjectController::class,'adminfulleventcalendarpage'])->name('adminfulleventcalendarpage');

        Route::get('formcrateimgtype',[ProjectController::class,'formcrateimgtype'])->name('formcrateimgtype');
        Route::post('crateimgtype',[ProjectController::class,'crateimgtype'])->name('crateimgtype');
        Route::put('/{id}', [ProjectController::class, 'updateimgtype'])->name('updateimgtype');
        Route::delete('/{id}', [ProjectController::class, 'deleteimgtype'])->name('deleteimgtype');
        Route::post('/{id}/restore', [ProjectController::class, 'restoreimgtype'])->name('restoreimgtype');

        Route::get('/receipt/{id}', [ProjectController::class, 'receipt'])->name('receipt');
        Route::get('/tax-invoice/{id}', [ProjectController::class, 'taxInvoice'])->name('taxInvoice');

        Route::get('/restockpage/{id}', [ProjectController::class, 'restockpage'])->name('restockpage');
        Route::post('/restockform/{id}', [ProjectController::class, 'restockForm'])->name('restockform');
        Route::post('/processrestock/{id}', [ProjectController::class, 'processrestock'])->name('processrestock');

        Route::put('/productsetname/{id}/update', [ProjectController::class, 'admupdateproductsetname'])->name('admupdateproductsetname');
        Route::get('/productsetname/{id}/delete', [ProjectController::class, 'deleteproductsetname'])->name('deleteproductsetname');
        Route::get('/productsetname/{id}/restore', [ProjectController::class, 'restoreproductsetname'])->name('restoreproductsetname');

        Route::get('/confirmworkcompletedpage/{id}', [ProjectController::class, 'confirmworkcompletedpage'])->name('confirmworkcompletedpage');
        Route::post('projects/customerneed/{need_id}/upload-after-image', [ProjectController::class, 'uploadAfterImage'])->name('uploadafterimage');
        Route::delete('projects/customerneed/{need_id}/delete-after-image', [ProjectController::class, 'deleteAfterImage'])->name('deleteafterimage');

        Route::delete('projects/{id}/destroy', [ProjectController::class, 'destroy'])->name('destroy');
        Route::post('projects/{id}/restore', [ProjectController::class, 'restore'])->name('restore');

        Route::post('projects/{id}/cancelwithdrawal', [ProjectController::class, 'cancelWithdrawal'])->name('cancelWithdrawal');


        Route::post('projects/{id}/withdraw/form', [ProjectController::class, 'withdrawform'])->name('withdrawform');
        Route::post('projects/{id}/withdraw/store', [ProjectController::class, 'withdrawstore'])->name('withdrawstore');
        Route::get('/managewithdrawals', [ProjectController::class, 'managewithdrawals'])->name('managewithdrawals');
        Route::get('/{id}/withdrawdetails', [ProjectController::class, 'withdrawdetails'])->name('withdrawdetails');
        Route::get('/projects/{id}/return-materials',[ProjectController::class, 'returnMaterialsPage'])->name('return_materials_page');
        Route::post('/projects/{id}/return-materials',[ProjectController::class, 'storeReturnMaterials'])->name('store_return_materials');
        Route::post('/withdrawal-item/{id}/return-tool',[ProjectController::class, 'returnTool'])->name('return_tool');
        Route::get('/withdrawal-item/{id}/edit',[ProjectController::class, 'editWithdrawalItemPage'])->name('edit_withdrawal_item_page');
        Route::put('/withdrawal-item/{id}/edit',[ProjectController::class, 'editWithdrawalItem'])->name('edit_withdrawal_item');




        


        });

    Route::get('/api/get-amphures/{id}', [ProjectController::class, 'getAmphures']);
    Route::get('/api/get-tambons/{id}', [ProjectController::class, 'getTambons']);

        

    Route::prefix('materialstype')->name('admin.materalstype.')->group(function () {

        Route::get('/createaluminiumType', [MaterialTypeController::class, 'createFormaluminiumType'])->name('createFormaluminiumType');
        Route::post('/createaluminiumType', [MaterialTypeController::class, 'createaluminiumType'])->name('createaluminiumType');
        Route::get('aluminium/edit/{id}', [MaterialTypeController::class, 'editAluminiumType'])->name('editAluminiumType');
        Route::put('aluminium/update/{id}', [MaterialTypeController::class, 'updateAluminiumType'])->name('updateAluminiumType');
        Route::delete('aluminium/delete/{id}', [MaterialTypeController::class, 'deleteAluminiumType'])->name('deleteAluminiumType');


        Route::get('/createglassType', [MaterialTypeController::class, 'createFormglassType'])->name('createFormglassType');
        Route::post('/createglassType', [MaterialTypeController::class, 'createglassType'])->name('createglassType');
        Route::get('/glass/edit/{id}', [MaterialTypeController::class, 'editGlassType'])->name('editGlassType');
        Route::put('/glass/update/{id}', [MaterialTypeController::class, 'updateGlassType'])->name('updateGlassType');
        Route::delete('/glass/delete/{id}', [MaterialTypeController::class, 'deleteGlassType'])->name('deleteGlassType');


        Route::get('/createaccessoryType', [MaterialTypeController::class, 'createFormaccessoryType'])->name('createFormaccessoryType');
        Route::post('/createaccessoryType', [MaterialTypeController::class, 'createaccessoryType'])->name('createaccessoryType');
        Route::get('/accessory/edit/{id}', [MaterialTypeController::class, 'editAccessoryType'])->name('editAccessoryType');
        Route::put('/accessory/update/{id}', [MaterialTypeController::class, 'updateAccessoryType'])->name('updateAccessoryType');
        Route::delete('/accessory/delete/{id}', [MaterialTypeController::class, 'deleteAccessoryType'])->name('deleteAccessoryType');


        Route::get('/createtoolType', [MaterialTypeController::class, 'createFormtoolType'])->name('createFormtoolType');
        Route::post('/createtoolType', [MaterialTypeController::class, 'createtoolType'])->name('createtoolType');
        Route::get('/tool/edit/{id}', [MaterialTypeController::class, 'editToolType'])->name('editToolType');
        Route::put('/tool/update/{id}', [MaterialTypeController::class, 'updateToolType'])->name('updateToolType');
        Route::delete('/tool/delete/{id}', [MaterialTypeController::class, 'deleteToolType'])->name('deleteToolType');

        Route::get('/createdealer', [MaterialTypeController::class, 'createFormdealer'])->name('createFormdealer');
        Route::post('/createdealer', [MaterialTypeController::class, 'createdealer'])->name('createdealer');
        Route::get('/dealer/edit/{id}', [MaterialTypeController::class, 'editdealer'])->name('editdealer');
        Route::put('/dealer/update/{id}', [MaterialTypeController::class, 'updatedealer'])->name('updatedealer');
        Route::delete('/dealer/delete/{id}', [MaterialTypeController::class, 'deletedealer'])->name('deletedealer');

        Route::get('/createFormunit', [MaterialTypeController::class, 'createFormunit'])->name('createFormunit');
        Route::post('/createunit', [MaterialTypeController::class, 'createunit'])->name('createunit');
        Route::get('/unit/edit/{id}', [MaterialTypeController::class, 'editunit'])->name('editunit');
        Route::put('/unit/update/{id}', [MaterialTypeController::class, 'updateunit'])->name('updateunit');
        Route::delete('/unit/delete/{id}', [MaterialTypeController::class, 'deleteunit'])->name('deleteunit');


        Route::get('/createFormconsumable', [MaterialTypeController::class, 'createFormconsumableType'])->name('createFormconsumableType');
        Route::post('/createconsumable', [MaterialTypeController::class, 'createconsumableType'])->name('createconsumableType');
        Route::get('/consumable/edit/{id}', [MaterialTypeController::class, 'editconsumableType'])->name('editconsumableType');
        Route::put('/consumable/update/{id}', [MaterialTypeController::class, 'updateconsumableType'])->name('updateconsumableType');
        Route::delete('/consumable/delete/{id}', [MaterialTypeController::class, 'deleteconsumableType'])->name('deleteconsumableType');
    });



    Route::get('/admin/materials-type/trash', [MaterialTypeController::class, 'trash'])->name('materialstype.trash');
    Route::post('/admin/materials-type/restore', [MaterialTypeController::class, 'restore'])->name('materialstype.restore');
});




Route::middleware(['auth', 'verified', 'role:technician'])->group(function () {
    Route::get('/technician/dashboard', [TechnicianDashboard::class, 'index'])->name('technician.dashboard');
    Route::get('/projects/{id}', [TechnicianProjectController::class, 'show'])->name('technician.projects.show');
    Route::post('/projects/{id}/close', [TechnicianProjectController::class, 'close'])->name('technician.projects.close');

    Route::prefix('technician/project')->name('technician.projects.')->group(function(){
        Route::get('/formsurveying/{id}',[TechnicianProjectController::class,'formsurveying'])->name('formsurveying');
        Route::post('/updatestatussurveying/{id}',[TechnicianProjectController::class,'updatestatussurveying']) ->name('updatestatussurveying');
        Route::post('/updatestatuspendingquotation',[TechnicianProjectController::class,'updatestatuspendingquotation']) ->name('updatestatuspendingquotation');


        Route::get('/formprojectimage/{id}',[TechnicianProjectController::class,'formprojectimage'])->name('formprojectimage');
        Route::post('/createprojectimage',[TechnicianProjectController::class,'createprojectimage'])->name('createprojectimage');
        Route::delete('/deleteprojectimage/{id}',[TechnicianProjectController::class,'deleteprojectimage'])->name('deleteprojectimage');

        Route::get('/formcustomerneed/{id}',[TechnicianProjectController::class,'formcustomerneed'])->name('formcustomerneed');
        Route::post('/addcustomerneed',[TechnicianProjectController::class,'addcustomerneed'])->name('addcustomerneed');
        Route::delete('deletecustomerneed/{id}',[TechnicianProjectController::class,'deletecustomerneed'])->name('deletecustomerneed');
    });
});
