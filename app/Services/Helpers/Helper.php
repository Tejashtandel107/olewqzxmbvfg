<?php
    namespace App\Services\Helpers;

    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Str;
    use App\Models\Setting;

    class Helper {

        public function DateFormat($date="",$format="") {
            $format = ($format !== "") ? $format : config('constant.DATE_TIME_FORMAT');
            $dt = (!empty($date)) ? Carbon::parse($date) : Carbon::now();
            return $dt->format($format);
        }

        /**
         * convert the D-M-Y format to D/M/Y
         * @param $value
         * @param $format
         * 
         */
        public function convertDateFormat($value="",$format="") {
            if(!empty($value)){
                $format = (empty($format)) ? config('constant.DATE_FORMAT') : $format;
                return Carbon::createFromFormat($format,$value);
            }
            else{
                return Carbon::now();
            }            
        }
        public function uploadFile($request_file,$filepath) {
            $filename=Helper::getUniqueFilename() . '.' . $request_file->getClientOriginalExtension();
            return $request_file->storeAs($filepath,$filename);
        }

        public function getUniqueFilename($prefix='',$more_entropy=true) {
        	$filename=uniqid($prefix,$more_entropy);
        	$filename=str_replace('.','',$filename ?? '');
        	return $filename;
        }

        public function getPlaceHolderImg($value) {
            if(Storage::exists($value)){
                return Storage::url($value);
            }
            else{
                return config('constant.DEFAULT_IMG_HOLDER');
            }
        }

        public function getProfileImg($value) {
            if(!empty($value) && Storage::exists($value)){
                return Storage::url($value);    
            }
            else{
                return config('constant.DEFAULT_IMG_PROFILE');
            }
        }

        public function getStoragePath($value) {
            if(Storage::exists($value)){
                return Storage::url($value);
            }
            else{
                return '';
            }
        }

        public static function getStorageRealPath($file="") {
            return Storage::disk(config('filesystems.local'))->getDriver()->getAdapter()->applyPathPrefix($file);
        }

        public function formatAmount($amount=0.00,$decimal=2,$currency_symbol=true) {
            $number = number_format(abs(is_numeric($amount) ? $amount : 0), $decimal, ',', '.');
            if($currency_symbol){
                return ($amount<0) ? '-&euro;' . $number : '&euro;' . $number;
            }
            else
                return $number;
        }

        public function getHumanDate($value) {
            if(!empty($value)) { 
                return Carbon::parse($value)->toFormattedDateString();
            }
            else {
                return null;
            }
        }
        /**
         * Get User Role Name.
         *
         * @param $role_id
         * @return str 
         */
        public function getRoleName($role_id=""){
            switch($role_id){
                case config('constant.ROLE_SUPER_ADMIN_ID');
                    return "Super Ammini";
                break;
                case config('constant.ROLE_ACCOUNT_MANAGER_ID');
                    return "Account Manager";
                break;
                case config('constant.ROLE_CLIENT_ID');
                    return "Studio";
                break;
                case config('constant.ROLE_OPERATOR_ID');
                    return "Operatore";
                break;
                default:
                    return;
            }
        }
        
        public function isSuperAdmin($role_id){
            return ($role_id == config('constant.ROLE_SUPER_ADMIN_ID'));
        }

        public function isAccountManager($role_id){
            return ($role_id == config('constant.ROLE_ACCOUNT_MANAGER_ID'));
        }

        public function isClient($role_id){
            return ($role_id == config('constant.ROLE_CLIENT_ID'));
        }

        public function isOperator($role_id){
            return ($role_id == config('constant.ROLE_OPERATOR_ID'));
        }

        public function getCompanyTypes(){
            return config('constant.COMPANY_TYPES');
        }
        public function getBusinessTypes(){
            return config('constant.BUSINESS_TYPES');
        }
        public function getPettyCashBookTypes(){
            return config('constant.PETTY_CASH_BOOK_TYPES');
        }
        public function getOperatorPricingTypes(){
            return config('constant.OPERATOR_PRICING_TYPES');
        }
        public function getAccountManagerPricingTypes(){
            return config('constant.ACCOUNT_MANAGER_PRICING_TYPES');
        }
        public function getStudioPricingTypes(){
            return config('constant.STUDIO_PRICING_TYPES');
        }
        public function getStudioPricingTypeLabel($value=""){
            $pricings = config('constant.STUDIO_PRICING_TYPES');
            return ($pricings[$value]) ?? 'N/A';
        }
        public function getAccountManagerPricingTypeLabel($value=""){
            $pricings = config('constant.ACCOUNT_MANAGER_PRICING_TYPES');
            return ($pricings[$value]) ?? 'N/A';
        }

        public function getOperatorPricingTypeLabel($value=""){
            $pricings = config('constant.OPERATOR_PRICING_TYPES');
            return ($pricings[$value]) ?? 'N/A';
        }

        public function getPricingTypes(){
            return array();
        }
        public function getYears(){
            $years = [];
            $firstYear = (int)date('Y') - 1;
            $lastYear = $firstYear + 1;
            for($i=$firstYear;$i<=$lastYear;$i++)
            {
                $years[$i] = $i;
            }
            return $years;
        }
        
        public function getUserAccountURL($user){
            $my_account_url="";
            if($user->isSuperAdmin()){
                $my_account_url = route('admin.profile.edit');
            }
            elseif($user->isAccountManager()){
                $my_account_url = route('account-manager.profile.edit');
            }
            elseif($user->isOperator()){
                $my_account_url = route('operator.profile.edit');
            }
            elseif($user->isClient()){
                $my_account_url = route('client.profile.edit');
            }
            return $my_account_url;
        }

        public function getUserHomeURL($user){
            $home_url="";
            if ($user->isSuperAdmin()) {
                $home_url = route('admin.line-incomes.index');
            }
            else if($user->isAccountManager()) {
                $home_url = route('account-manager.line-expenses.index');
            }
            else if($user->isOperator()) {
                $home_url = route('operator.line-expenses.index');
            }
            else if($user->isClient()) {
                $home_url = route('client.line-incomes.index');
            }

            return $home_url;
        }
        /**
         * Generate a list of months
         */
        public function getMonths(){
            
            $months = [];
            for ($month = 1; $month <= 12; $month++) {
                $carbonMonth = Carbon::create(null, $month, 1);
                $months[$carbonMonth->month] = ucfirst($carbonMonth->translatedFormat('F'));
            }
            return $months;
        }
        public function getSettings(){
            return Cache::rememberForever('settings', function () {
                return Setting::pluck('option_value', 'option_name')->toArray();
            });
        }
    
        public function getSetting($key, $default = null){
            $settings =$this->getSettings();
            return $settings[$key] ?? $default;
        }
    }
?>
