<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/','AccountController@loginGet');

Route::get('/account/logout','AccountController@logout');

// Route::get('/hrmslogin', function () {
//     return view('hrmslogin');
// });

Route::get('/employees','EmployeeController@getUserData');

//GET add employee
Route::get('/admin/addemployee','EmployeeController@getaddEmployee');

//POST add employee
Route::post('/admin/addemployee','EmployeeController@postAddEmployee');

//POST add employee
Route::post('/admin/removeemployee','EmployeeController@removeEmployee');

//GET user/profile
Route::get('/user/profile','UserController@getUserProfile');

//GET user/attendance
Route::get('/user/attendance','UserController@getUserAttendance');

//GET user/applyleave
Route::get('/user/leave','UserController@getApplyLeave');

//GET user/leavereports
Route::get('/user/leavereports','UserController@getLeaveReports');

//GET user/employeereports
Route::get('/user/employeereports','UserController@getEmployeeReports');

//POST userimage uplaod
Route::post('/user/UploadFile','UserController@UploadFile');

//POST userimage uplaod confirm
Route::post('/user/UpdateUserPhoto','UserController@UpdateUserPhoto');

//POST userimage remove
Route::post('/user/removeProfilePhoto','UserController@removeUserPhoto');

//GET userpassword change
Route::get('/user/changepassword','UserController@getChangeUserPassword');

//POST userpassword change
Route::post('/user/changepassword','UserController@postChangeUserPassword');

//POST GetEmpPunchDetails ajax
Route::post('/user/GetEmpPunchDetails', 'UserController@getEmpPunchDetails');

Route::post('/user/AddAttendance', 'UserController@AddAttendance');

Route::post('/user/SearchPunchDetails','UserController@SearchAttendance');

Route::post('/user/GetLeaveStatistics','UserController@GetLeaveStatistics');

Route::post('/user/GetLeaveDetails','UserController@postLeaveReports');

Route::post('/user/AddLeave','UserController@AddLeave');

Route::post('/user/postUserReport','UserController@postUserReport');

//POST route
Route::post('/loginSubmit', 'AccountController@loginPost');

//POST userprofile
Route::post('/user/profile','UserController@userProfilePost');

Auth::routes();

Route::get('/home', 'HomeController@index');
