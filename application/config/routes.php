<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Site/Main';
$route['AdminSecure_Area'] = 'Admin/Management/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//**==================================== Dashboard Apis ====================================**//
//** Dashboard Auth */
$route['login'] = 'Dashboard/Auth/signin';
$route['logout'] = 'Dashboard/Auth/signout';
$route['dashboard/reset-password'] = 'Dashboard/Auth/passwordReset';
$route['dashboard/forget-password'] = 'Dashboard/Auth/forgetPassword';

//** Dashboard Register */
if (registration == 0) {
    $route['activateAjax'] = 'Dashboard/Activation/activate';
    $route['register']['GET'] = 'Dashboard/Register/simpleRegister';
    $route['register']['POST'] = 'Dashboard/Register/simpleRegister';
    $route['upgradeAjax'] = 'Dashboard/Activation/upgradeBinary';
} elseif (registration == 1) {
    $route['activateAjax'] = 'Dashboard/Activation/activateBinary';
    $route['register']['GET'] = 'Dashboard/Register/binaryRegister';
    $route['register']['POST'] = 'Dashboard/Register/binaryRegister';
    $route['upgradeAjax'] = 'Dashboard/Activation/upgradeBinary';
}
//** Dashboard UserInfo  */
$route['dashboard'] = 'Dashboard/UserInfo/index';
$route['dashboard/directs/(.+)'] = 'Dashboard/UserInfo/directs/$1';
$route['dashboard/myDownline/(.+)'] = 'Dashboard/UserInfo/myDownline/$1';
$route['dashboard/tree/(:any)'] = 'Dashboard/UserInfo/Tree/$1';
$route['dashboard/genelogy-tree/(:any)'] = 'Dashboard/UserInfo/GenelogyTree/$1';
$route['dashboard/pool/(:any)'] = 'Dashboard/UserInfo/Pool/$1';
$route['get_user/(.+)'] = 'Dashboard/UserInfo/get_user/$1';
$route['dashboard/my-team'] = 'Dashboard/UserInfo/myTeam';
$route['dashboard/my-team/(:num)'] = 'Dashboard/UserInfo/myTeam';
$route['dashboard/success'] = 'Dashboard/UserInfo/sucecss';

//** Dashboard Transfer  */
$route['dashboard/income-transfer'] = 'Dashboard/Transfer/IncomeTransfer';
$route['dashboard/income_wallet-transfer'] = 'Dashboard/Transfer/incomeToeWalletTransfer';
$route['dashboard/wallet-transfer'] = 'Dashboard/Transfer/eWalletTransfer';

//** Dashboard Profile */
$route['dashboard/profile'] = 'Dashboard/Profile/index';
$route['dashboard/account-details'] = 'Dashboard/Profile/accountDetails';
$route['dashboard/upload-proof'] = 'Dashboard/Profile/UploadProof';
$route['dashboard/transaction-password'] = 'Dashboard/Profile/transPassword';
$route['dashboard/zil-update'] = 'Dashboard/Profile/zilUpdate';

//** Dashboard Support  */
$route['dashboard/compose-mail'] = 'Dashboard/Support/ComposeMail';
$route['dashboard/inbox-mail'] = 'Dashboard/Support/Inbox';
$route['dashboard/inbox-mail/(:num)'] = 'Dashboard/Support/Inbox';
$route['dashboard/outbox-mail'] = 'Dashboard/Support/Outbox';
$route['dashboard/outbox-mail/(:num)'] = 'Dashboard/Support/Outbox';

//** Dashboard Fund  */
if (fund_process == 0) {
    $route['dashboard/fund-request'] = 'Dashboard/Fund/Request_fund';
} elseif (fund_process == 1) {
    $route['dashboard/fund-request'] = 'Dashboard/Fund/Deposit_fund';
}
$route['dashboard/fund-history'] = 'Dashboard/Fund/fundHistory';
$route['dashboard/fund-history/(:num)'] = 'Dashboard/Fund/fundHistory';
$route['dashboard/fundrequest-history'] = 'Dashboard/Fund/fundRequestHistory';
$route['dashboard/fundrequest-history/(:num)'] = 'Dashboard/Fund/fundRequestHistory';

//** Dashboard Activation  */
$route['dashboard/activate-account'] = 'Dashboard/Activation/index';
$route['dashboard/updgrade-account'] = 'Dashboard/Activation/UpgradeAccount';
$route['dashboard/activate-history'] = 'Dashboard/Activation/accountHistory';
$route['dashboard/activate-history/(:num)'] = 'Dashboard/Activation/accountHistory';

//** Dashboard Withdraw  */
if (withdraw == 0) {
    $route['dashboard/directIncomeWithdraw'] = 'Dashboard/Withdraw/DirectIncomeWithdraw_Bank';
} else {
    $route['dashboard/directIncomeWithdraw'] = 'Dashboard/Withdraw/DirectIncomeWithdraw_Wallet';
}
$route['dashboard/withdraw-history'] = 'Dashboard/Withdraw/withdrawHistory';
$route['dashboard/withdraw-history/(:num)'] = 'Dashboard/Withdraw/withdrawHistory';

//** Dashboard Incomes */
$route['dashboard/payout-summary'] = 'Dashboard/Incomes/payout_summary';
$route['dashboard/payout-summary/(:num)'] = 'Dashboard/Incomes/payout_summary';
$route['dashboard/datewise-payout/(.+)'] = 'Dashboard/Incomes/dateWisePayout/$1';
$route['dashboard/incomes/(.+)'] = 'Dashboard/Incomes/incomes/$1';
$route['dashboard/income-ledger'] = 'Dashboard/Incomes/incomesLedger';
$route['dashboard/income-ledger/(:num)'] = 'Dashboard/Incomes/incomesLedger';

$route['dashboard/level-report'] = 'Dashboard/Reports/levelReport';
$route['dashboard/level-report/(:num)'] = 'Dashboard/Reports/levelReport';


//**==================================== Installment ====================================**//

    $route['dashboard/select_installment_small'] = 'Dashboard/Instalment/select_installment';
    $route['dashboard/emi_entry'] = 'Dashboard/Instalment/kist_entry';
    $route['dashboard/emi_history'] = 'Dashboard/Instalment/emi_history';
    $route['dashboard/emi_history/(:any)'] = 'Dashboard/Instalment/emi_history/$1';
    $route['dashboard/select_installment_big'] = 'Dashboard/Instalment/select_installment2';
    $route['dashboard/emi_entry_big'] = 'Dashboard/Instalment/kist_entry2';
    $route['dashboard/emi_history_big'] = 'Dashboard/Instalment/emi_history2';
    $route['dashboard/emi_history_big/(:any)'] = 'Dashboard/Instalment/emi_history2/$1';





//**==================================== Sub Admin Apis ====================================**//

$route['admin/permissions'] = 'Admin/Permissions/index';
$route['admin/permissions/(:num)'] = 'Admin/Permissions/index';
$route['admin/create-subadmin'] = 'Admin/Permissions/CreateSubAdmin';
$route['admin/change-permissions/(.+)']['GET'] = 'Admin/Permissions/ChangePermissions/$1';
$route['admin/change-permissions/(.+)']['POST'] = 'Admin/Permissions/ChangePermissions/$1';
$route['admin/edit-subadmin/(.+)']['GET'] = 'Admin/Permissions/edit/$1';
$route['admin/edit-subadmin/(.+)']['POST'] = 'Admin/Permissions/edit/$1';
$route['admin/access-deined'] = 'Admin/Permissions/accessDeined';
$route['admin/sub-login'] = 'Admin/Management/Sublogin';


//**==================================== Admin Apis ====================================**//
//** Admin Auth */
$route['admin/login'] = 'Admin/Management/login';
$route['admin/logout'] = 'Admin/Management/logout';
//** Admin Management */

$route['admin/dashboard'] = 'Admin/Management/index';
$route['admin/edit-user/(.+)'] = 'Admin/Management/EditUser/$1';
//** Admin Reports */
$route['admin/users'] = 'Admin/Management/users';
$route['admin/users/(:any)'] = 'Admin/Management/users';
$route['admin/todayJoined'] = 'Admin/Management/todayJoinedusers';
$route['admin/todayJoined/(:any)'] = 'Admin/Management/todayJoinedusers';
$route['admin/user-login/(.+)'] = 'Admin/Management/user_login/$1';
$route['admin/edit-user/(.+)'] = 'Admin/Settings/EditUser/$1';
$route['admin/paid-users'] = 'Admin/Management/paidUsers';
$route['admin/paid-users/(:any)'] = 'Admin/Management/paidUsers';
$route['admin/availableIncome'] = 'Admin/Report/availableIncome';
$route['admin/availableIncome/(:any)'] = 'Admin/Report/availableIncome';
$route['admin/todaypaid-users'] = 'Admin/Management/TodaypaidUsers';
$route['admin/todaypaid-users/(:any)'] = 'Admin/Management/TodaypaidUsers';
$route['admin/sendIncome'] = 'Admin/Management/sendIncome';
$route['admin/buy-price'] = 'Admin/Settings/token_value';
$route['admin/sell-value'] = 'Admin/Settings/sellValue';
$route['admin/news'] = 'Admin/Settings/news';
$route['admin/create-news'] = 'Admin/Settings/CreateNews';
$route['admin/reset-password'] = 'Admin/Settings/passwordReset';
$route['admin/edit-news/(.+)'] = 'Admin/Settings/editNews/$1';
$route['admin/delete-news/(.+)'] = 'Admin/Settings/deleteNews/$1';
$route['admin/popup'] = 'Admin/Settings/popup_upload';
$route['admin/withdraw_popup'] = 'Admin/Settings/withdraw_popup';
$route['admin/qrcode'] = 'Admin/Settings/upload_qrcode';
$route['admin/links'] = 'Admin/Settings/upload_links';
$route['admin/incomes/(.+)'] = 'Admin/Withdraw/income/$1';
$route['admin/income-ledgar'] = 'Admin/Withdraw/incomeLedgar';
$route['admin/income-ledgar/(:any)'] = 'Admin/Withdraw/incomeLedgar';
$route['admin/adminincome-ledgar'] = 'Admin/Withdraw/adminincomeLedgar';
$route['admin/adminincome-ledgar/(:any)'] = 'Admin/Withdraw/adminincomeLedgar';
$route['admin/payout-summary'] = 'Admin/Withdraw/payout_summary';
$route['admin/payout-summary/(:num)'] = 'Admin/Withdraw/payout_summary';
$route['admin/dateWisePayout/(.+)'] = 'Admin/Withdraw/dateWisePayout/$1';
$route['admin/kyc-history/(.+)'] = 'Admin/Withdraw/AddressRequests/$1';
$route['admin/withdraw-history/(.+)'] = 'Admin/Withdraw/WithdrawHistory/$1';
$route['admin/inbox'] = 'Admin/Support/inbox';
$route['admin/inbox/(.+)'] = 'Admin/Support/inbox';
$route['admin/outbox'] = 'Admin/Support/outbox';
$route['admin/outbox/(.+)'] = 'Admin/Support/outbox/$1';
$route['admin/compose'] = 'Admin/Support/compose';
$route['admin/compose/(:num)'] = 'Admin/Support/compose';
$route['admin/compose-mail'] = 'Admin/Support/ComposeMail';

$route['admin/support-view/(.+)']['GET'] = 'Admin/Support/view/$1';
$route['admin/support-view/(.+)']['POST'] = 'Admin/Support/view/$1';

//** Admin Withdraw */
$route['admin/withdraw-request/(.+)'] = 'Admin/Withdraw/request/$1';
$route['admin/send-wallet'] = 'Admin/Management/SendWallet';
$route['admin/fund-history'] = 'Admin/Management/fund_history';
$route['admin/fund-history/(:num)'] = 'Admin/Management/fund_history';
$route['admin/adminfund-history'] = 'Admin/Management/admin_fund_history';
$route['admin/adminfund-history/(:num)'] = 'Admin/Management/admin_fund_history';
$route['admin/send-coin'] = 'Admin/Management/SendCoin';

//** Admin Fund */
$route['admin/fund-requests/(.+)'] = 'Admin/Management/Fund_requests/$1';
$route['admin/update-fund-request/(.+)'] = 'Admin/Management/update_fund_request/$1';
//** Admin Settings */
$route['admin/create-news'] = 'Admin/Settings/CreateNews';
$route['admin/news-history'] = 'Admin/Settings/newsHistory';
$route['admin/edit-news/(.+)'] = 'Admin/Settings/EditNews/$1';
$route['admin/delete-news/(.+)'] = 'Admin/Settings/DeleteNews/$1';
$route['admin/token-value'] = 'Admin/Settings/tokenValue';
$route['admin/sethub-rate'] = 'Admin/Settings/setHubRate';
//** Admin Support */
