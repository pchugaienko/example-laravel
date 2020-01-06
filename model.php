<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class ms_talent extends BaseModel implements UserInterface, RemindableInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ms_talent';

    protected $primaryKey  = 'talent_id';

    //enable soft delete. which will update delete_at field with timestamp.
    protected $softDelete = true;

    protected $hidden = array('password');

    //enable timestamps, allow updated time and created time.
    public $timestamps = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function hideSensitiveDetailsFromOutputs() {
        $this->hidden += ['bank', 'rsa', 'passport', 'abn', 'tfn', 'trading_name', 'bank_bsb', 'bank_account', 'bank_account_name', 'super_provider', 'super_account'];
    }

    public function getIdentifierString() {
        if(!empty($this->first_legal_name) && !empty($this->last_legal_name)) {
            return "{$this->first_legal_name} {$this->last_legal_name}";
        }
        else {
            return "{$this->first_name} {$this->last_name} (Stage Name)";
        }

    }

    //==============start: talent authentication method ========================
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getReminderEmail()
    {
        return $this->user_email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    //==============end: talent authentication method ==========================

    //return all fields of data table.
    public static function data_fields()
    {
        $fields = DB::select("SHOW COLUMNS FROM ms_talent");
        return $fields;
    }

    public static function wc_validate($post_data)
    {
        $validation_rules = array(
            'login-email' =>  'required|regex:/@/',
            'login-pass'  =>  'required'
        );

        return $validator = Validator::make($post_data,$validation_rules);
    }

    public static function wc_profile_validate($post_data)
    {
        $validation_rules = array(
            'pay_method'		=>	'not_in:0',
            'payroll_calendar'	=>	'not_in:0',
            'tfn'               =>  'required|regex:/^[0-9]{3}+-[0-9]{3}+-[0-9]{3}$/',
            'bank'              =>  'required',
            'bank_account'		=>	'required',
            'bank_bsb'          =>  'required|regex:/^[0-9]{3}+-[0-9]{3}$/',
            'bank_account'      =>  'required',
            'superfund_id'    =>  'required|not_in:0',
            'super_account'     =>  'required',
            'mobile'	        =>	'required|digits:10',
            'email'		        => 	'required|regex:/@/',
            //'website'		    =>	'url',
            //	'profile'		    =>	'url',
            //'city'              =>  'required',
            'state'             =>  'not_in:0',
            'country'           =>  'not_in:0'
        );

        return $validator = Validator::make($post_data,$validation_rules);
    }

    public static function wc_email_validate($post_data)
    {
        $validation_rules = array(
            'email' =>  'required|regex:/@/'
        );

        return $validator = Validator::make($post_data,$validation_rules);
    }

    public static function wc_update_password_validate($post_data)
    {
        $validation_rules = array(
            'password'  =>  'required|min:6|alpha_num',
            're-password'   =>  'required|same:password'
        );

        return $validator = Validator::make($post_data,$validation_rules);
    }

    public static function validate($post_data)
    {
        $validation_rules = array(
            'first_name' 		=> 	'required',
            'last_name' 		=> 	'required',
            'gender' 			=> 	'required',
            'dob'				=>	'required',
            'talent_status'		=>	'not_in:0',
            'talent_category' 	=> 	'not_in:0',
            'talent_start_date'	=>	'required',
            /*'tfn'               =>  'required|regex:/^[0-9]{3}+-[0-9]{3}+-[0-9]{3}$/',
            'bank'              =>  'required',
            'bank_account'		=>	'required',
            'bank_bsb'          =>  'required|regex:/^[0-9]{3}+-[0-9]{3}$/',
            'bank_account'      =>  'required',
            'super_provider'    =>  'required',
            'super_account'     =>  'required',*/
            'mobile'	        =>	'required|digits:10',
            'email'		        => 	'required|regex:/@/',//
            //'website'		    =>	'url',
            //'city'              =>  'required',
            //'postcode'          => 'required',
            //'address'           => 'required',
            'state'             =>  'required',
            'country'           =>  'not_in:0'
        );

        return $validator = Validator::make(
            $post_data,
            $validation_rules
        );
    }

    public static function get_filters($state, $status, $rating,$category,$talent_height,$talent_eye_colour, $bra,$shoe,$hair_colour,$collar_size,$dress_size,$pant_size, $t_shirt, $chest_bust,$availability_option, $suit_size, $waist_size, $hip_size)
    {
        //add image expired option to status.
        $status[4] ='Active - Image Expired';
        $status[5] ='Active - Image Need Review';

        $skill_set = array();
        $taxonomy = get_entity_attributes_taxonomy('ms_talent');
        foreach($taxonomy as $tax)
        {
            if($tax->parent == 'skill')
            {
                $skills = get_entity_attributes_depth('ms_talent',$tax->attribute_taxonomy_id);
                $skills_arr = array();
                foreach($skills as $skill)
                {
                    $skills_arr[$skill->attribute_id]   =   $skill->attribute_title;
                }

                $skill_set[$tax->taxonomy]  = array('title' => $tax->name, 'data'=>$skills_arr);
            }
        }

        //start build filter array
        $filters    =   array(
            'General'       =>  array(
                'gender'=>array(
                    'title' =>  'Gender',
                    'data'  =>  array(
                        '1' =>  'Male',
                        '2' =>  'Female'
                    )
                ),
                'induction_complete' => array(
                    'title' =>  'Induction',
                    'data'  =>  array(
                        '1' =>  'Induction Complete',
                        '2' =>  'Induction Not Complete'
                    )
                ),
                'talent_category'   =>array(
                    'title' =>  'Talent Type',
                    'data'  =>  $category
                ),
                'status'    =>  array(
                    'title' =>  'Talent Status',
                    'data'  =>  $status
                ),
                'rating'    =>  array(
                    'title' =>  'Talent Rating',
                    'data'  =>  $rating
                ),
                'state'     =>  array(
                    'title' =>  'State',
                    'data'  =>  $state
                ),
                'license'   =>  array(
                    'title' =>  'License',
                    'data'  =>  array(
                        '1' => 'No License',
                        '2' => 'With License'
                    )
                ),
                'rsa'   =>  array(
                    'title' =>  'RSA',
                    'data'  =>  array(
                        '1' =>  'No RSA',
                        '2' =>  'With RSA'
                    )
                ),
                'rcg'   =>  array(
                    'title' =>  'RCG',
                    'data'  =>  array(
                        '1' =>  'No RCG',
                        '2' =>  'With RCG'
                    )
                ),
                'passport'  =>  array(
                    'title' =>  'Passport',
                    'data'  =>  array(
                        '1' =>  'No Passport',
                        '2' =>  'With Passport'
                    )
                ),
                'search' => array(
                    'title' =>  'Search by First Name, Last Name, Email or Mobile',
                    'data'  =>  'First Name / Last Name / Email / Mobile'
                )

            ),
            'Profile'   =>  array(
                'age_from'    => array(
                    'title' =>  'Age From',
                    'data'  =>  'Enter age from'
                ),
                'age_to'    => array(
                    'title' =>  'Age To',
                    'data'  =>  'Enter age to'
                ),
                'height'    =>  array(
                    'title' =>  'Height',
                    'data'  => $talent_height
                ),
                'eye_colour'    => array(
                    'title' =>  'Eye Colour',
                    'data'  => $talent_eye_colour
                ),
                'hair_colour'=> array(
                    'title' =>  'Hair Colour',
                    'data'  =>  $hair_colour
                ),
                'shoe'    => array(
                    'title' =>  'Shoe Size',
                    'data'  =>  $shoe
                ),
                'bra'    => array(
                    'title' =>  'Bra Size',
                    'data'  =>  $bra
                ),
                'dress_size'    =>  array(
                    'title' =>  'Dress Size',
                    'data'  =>  $dress_size
                ),
                'pant_size'    =>  array(
                    'title' =>  'Pant Size',
                    'data'  =>  $pant_size
                ),
                'collar_size'=>array(
                    'title' =>  'Collar/Shirt',
                    'data'  =>  $collar_size
                ),
                't_shirt'   =>array(
                    'title' =>  'T Shirt',
                    'data'  =>  $t_shirt
                )
            ,
                'chest_bust'=>array(
                    'title' =>  'Chest/Bust',
                    'data'  =>  $chest_bust
                ),
                'suit_size'=>array(
                    'title' =>  'Suit Size',
                    'data'  =>  $suit_size
                ),
                'waist_size'=>array(
                    'title' =>  'Waist Size',
                    'data'  =>  $waist_size
                ),
                'hip_size'=>array(
                    'title' =>  'Hip Size',
                    'data'  =>  $hip_size
                )
            ),
            'Skills' =>  $skill_set,
            'Availability'  =>  array(
                'availability_option' => array(
                    'title' =>  'Availability',
                    'data'  =>  $availability_option
                ),
                'date_range'    => array(
                    'title' =>  'Date Range',
                    'data'  => 1
                )
            )
        );
        //end build filter array

        return $filters;
    }

    public static function get_filters_new($state, $status, $rating,$category,$talent_height,$talent_eye_colour, $bra,$shoe,$hair_colour,$collar_size,$dress_size,$pant_size, $t_shirt, $chest_bust,$availability_option,$suit_size, $waist_size, $hip_size)
    {
        //add image expired option to status.
        $status[4] ='Active - Image Expired';
        $status[5] ='Active - Image Need Review';

        $skill_set = array();
        $taxonomy = get_entity_attributes_taxonomy('ms_talent');
        foreach($taxonomy as $tax)
        {
            if($tax->parent == 'skill')
            {
                $skills = get_entity_attributes_depth('ms_talent',$tax->attribute_taxonomy_id);
                $skills_arr = array();
                foreach($skills as $skill)
                {
                    $skills_arr[$skill->attribute_id]   =   $skill->attribute_title;
                }

                $skill_set[$tax->taxonomy]  = array('title' => $tax->name, 'data'=>$skills_arr);
            }
        }

        //start build filter array
        $filters    =   array(
            'General'       =>  array(
                'gender'=>array(
                    'title' =>  'Gender',
                    'data'  =>  array(
                        '1' =>  'Male',
                        '2' =>  'Female'
                    )
                ),
                'induction_complete' => array(
                    'title' =>  'Induction',
                    'data'  =>  array(
                        '1' =>  'Induction Complete',
                        '2' =>  'Induction Not Complete'
                    )
                ),
                'talent_category'   =>array(
                    'title' =>  'Talent Type',
                    'data'  =>  $category
                ),
                'status'    =>  array(
                    'title' =>  'Talent Status',
                    'data'  =>  $status
                ),
                'rating'    =>  array(
                    'title' =>  'Talent Rating',
                    'data'  =>  $rating
                ),
                'state'     =>  array(
                    'title' =>  'State',
                    'data'  =>  $state
                ),
                'license'   =>  array(
                    'title' =>  'License',
                    'data'  =>  array(
                        '1' => 'No License',
                        '2' => 'With License'
                    )
                ),
                'rsa'   =>  array(
                    'title' =>  'RSA',
                    'data'  =>  array(
                        '1' =>  'No RSA',
                        '2' =>  'With RSA'
                    )
                ),
                'rcg'   =>  array(
                    'title' =>  'RCG',
                    'data'  =>  array(
                        '1' =>  'No RCG',
                        '2' =>  'With RCG'
                    )
                ),
                'passport'  =>  array(
                    'title' =>  'Passport',
                    'data'  =>  array(
                        '1' =>  'No Passport',
                        '2' =>  'With Passport'
                    )
                ),
                'influential' => array(
                    'title' => 'Influential',
                    'data' => array(
                        '1' => 'No Influential',
                        '2' => 'Influential'
                    )
                ),
                'search' => array(
                    'title' =>  'Search by First Name, Last Name, Email or Mobile',
                    'data'  =>  'First Name / Last Name / Email / Mobile'
                )

            ),
            'Profile'   =>  array(
                'age_from'    => array(
                    'title' =>  'Age From',
                    'data'  =>  'Enter age from'
                ),
                'age_to'    => array(
                    'title' =>  'Age To',
                    'data'  =>  'Enter age to'
                ),
                'height_from'    => array(
                    'title' =>  'Height From',
                    'data'  =>  $talent_height
                ),
                'height_to'    => array(
                    'title' =>  'Height To',
                    'data'  =>  $talent_height
                ),
                'eye_colour'    => array(
                    'title' =>  'Eye Colour',
                    'data'  => $talent_eye_colour
                ),
                'hair_colour'=> array(
                    'title' =>  'Hair Colour',
                    'data'  =>  $hair_colour
                ),
                'shoe_from'    => array(
                    'title' =>  'Shoe Size From',
                    'data'  =>  $shoe
                ),
                'shoe_to'    => array(
                    'title' =>  'Shoe Size To',
                    'data'  =>  $shoe
                ),
                'bra_from'    => array(
                    'title' =>  'Bra Size From',
                    'data'  =>  $bra
                ),
                'bra_to'    => array(
                    'title' =>  'Bra Size To',
                    'data'  =>  $bra
                ),
                'dress_size_from'    =>  array(
                    'title' =>  'Dress Size From',
                    'data'  =>  $dress_size
                ),
                'dress_size_to'    =>  array(
                    'title' =>  'Dress Size To',
                    'data'  =>  $dress_size
                ),
                'pant_size_from'    =>  array(
                    'title' =>  'Pant Size From',
                    'data'  =>  $pant_size
                ),
                'pant_size_to'    =>  array(
                    'title' =>  'Pant Size To',
                    'data'  =>  $pant_size
                ),
                'suit_size_from'    =>  array(
                    'title' =>  'Suit Size From',
                    'data'  =>  $suit_size
                ),
                'suit_size_to'    =>  array(
                    'title' =>  'Suit Size To',
                    'data'  =>  $suit_size
                ),
                'waist_size_from'    =>  array(
                    'title' =>  'Waist Size From',
                    'data'  =>  $waist_size
                ),
                'waist_size_to'    =>  array(
                    'title' =>  'Waist Size To',
                    'data'  =>  $waist_size
                ),
                'hip_size_from'    =>  array(
                    'title' =>  'Hip Size From',
                    'data'  =>  $hip_size
                ),
                'hip_size_to'    =>  array(
                    'title' =>  'Hip Size To',
                    'data'  =>  $hip_size
                ),
                'collar_size_from'=>array(
                    'title' =>  'Collar/Shirt From',
                    'data'  =>  $collar_size
                ),
                'collar_size_to'=>array(
                    'title' =>  'Collar/Shirt To',
                    'data'  =>  $collar_size
                ),
                't_shirt_from'   =>array(
                    'title' =>  'T Shirt From',
                    'data'  =>  $t_shirt
                ),
                't_shirt_to'   =>array(
                    'title' =>  'T Shirt To',
                    'data'  =>  $t_shirt
                ),
                'chest_bust_from'=>array(
                    'title' =>  'Chest/Bust From',
                    'data'  =>  $chest_bust
                ),
                'chest_bust_to'=>array(
                    'title' =>  'Chest/Bust To',
                    'data'  =>  $chest_bust
                )
            ),
            'Skills' =>  $skill_set,
            'Availability'  =>  array(
                'availability_option' => array(
                    'title' =>  'Availability',
                    'data'  =>  $availability_option
                ),
                'date_range'    => array(
                    'title' =>  'Date Range',
                    'data'  => 1
                )
            )
        );
        //end build filter array

        return $filters;
    }


    public static function get_display_fields()
    {
        $fields = array(
            'first_name'    =>  array(
                'title' =>  'First Name',
                'sortable'=>  1,
                'class' => ''
            ),
            'last_name'      =>  array(
                'title' =>  'Last Name',
                'sortable'=>  1,
                'class' => ''
            ),
            'rating'     =>  array(
                'title' =>  'Rating',
                'sortable'=>  1,
                'class' => ''
            ),
            'email'      =>  array(
                'title' =>  'Email',
                'sortable'=>  1,
                'class' => ''
            ),
            'mobile'        =>  array(
                'title' =>  'Mobile',
                'sortable'=>  1,
                'class' => ''
            ),

            'state'     =>  array(
                'title' =>  'State',
                'sortable'=>  1,
                'class' => ''
            ),
            'height'     =>  array(
                'title' =>  'Height',
                'sortable'=>  0,
                'class' => ''
            ),
            'eye_colour'     =>  array(
                'title' =>  'Eye Colour',
                'sortable'=>  0,
                'class' => ''
            ),
            'hair_colour'   =>  array(
                'title'     =>  'Hair Colour',
                'sortable'  =>  0,
                'class'     =>  '',
            ),
            'dob'     =>  array(
                'title' =>  'Age',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'dress'     =>  array(
                'title' =>  'Dress',
                'sortable'=>  0,
                'class' => ''
            ),
            't_shirt'   =>  array(
                'title'     =>  'T-Shirt',
                'sortable'  =>  0,
                'class'     =>  ''
            ),
            'shoe'     =>  array(
                'title' =>  'Shoe',
                'sortable'=>  0,
                'class' => ''
            ),
            'chest_bust'      =>  array(
                'title' =>  'Chest/Bust',
                'sortable'=>  0,
                'class' => ''
            ),
            'availability'     =>  array(
                'title' =>  'Availability',
                'sortable'=>  0,
                'class' => 'center'
            )
        );

        return $fields;
    }

    public function availabilities()
    {
        return $this->hasMany('fs_talent_availability','talent_id','talent_id');
    }

    public static function get_talents_by_status($status,$fields = null)
    {
        if($fields != null)
        {
            return self::where('talent_status',$status)->orderBy('first_name','asc')->orderby('first_name','asc')->get($fields);
        }
        else
        {
            return self::where('talent_status',$status)->orderBy('first_name','asc')->orderby('first_name','asc')->get();
        }
    }

    public static function get_comm_display_fields()
    {
        $fields = array(
            'first_name'    =>  array(
                'title' =>  'First Name',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'last_name'      =>  array(
                'title' =>  'Last Name',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'email'      =>  array(
                'title' =>  'Email',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'mobile'        =>  array(
                'title' =>  'Mobile',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'rating'     =>  array(
                'title' =>  'Rating',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'state'     =>  array(
                'title' =>  'State',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'availability'     =>  array(
                'title' =>  'Availability',
                'sortable'=>  0,
                'class' => 'center'
            )
        );

        return $fields;
    }

    public static function get_talent_issues_fields()
    {
        $fields = array(
            'stage_name'    =>  array(
                'title' =>  'Talenet Stage Name',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'full_name'      =>  array(
                'title' =>  'Talent Legal Name',
                'sortable'=>  1,
                'class' => 'center'
            ),
            'error'      =>  array(
                'title' =>  'Xero Error',
                'sortable'=>  1,
                'class' => 'center'
            )
        );

        return $fields;
    }

    public static function get_protected_fields()
    {
        //modify any fields of this will report to system, and then system will create task to admin, and also flag the fields.
        return array(
            'tfn',
            'mobile',
            'email',
            'bank',
            'bank_bsb',
            'bank_account_name',
            'bank_account',
            'super_provider',
            'super_account',
            'height',
            'dress',
            'bra',
            'waist',
            'chest_bust',
            'hips_women_only_',
            'shoe',
            'dress',
            't_shirt',
            'pant_size',
            'suit_size'
        );
    }

    public static function get_expired_talents()
    {
        return self::where('talent_status',1)->where('img_expired','>',0)->get();
    }

    public static function get_talents_by_state($state)
    {
        return self::where('talent_status',1)->where('state',$state)->get();
    }

    public static function get_talents_by_category($category)
    {

        return self::where('talent_status',1)->where('talent_category',$category)->get();
    }

    public static function update_talent_images_status($talent_id)
    {
        $talent_images = BaseModel::es_select('ms_talent','ms_file',array('talent_id'=>$talent_id));

        $review_count = 0;
        $expired_count = 0;
        foreach($talent_images as $image)
        {
            if($image->status == 0)
            {
                $review_count++;
            }
            if($image->status == 2)
            {
                $expired_count++;
            }
        }
        $talent = self::find($talent_id);

        $talent->img_review = $review_count;
        $talent->img_expired = $expired_count;

        $talent->save();
    }

    public static function get_talent_by_eav($key,$input_data=null)
    {
        //$input_data format like '170_175'. which defines range.
        //range values must be numbers. it should not wrapped with \"\".
        if($input_data != null)
        {
            $data = explode('_',$input_data);
            $query_str = 'AND ';

            if(isset($data[1]))
            {
                if($data[0] == 0)
                {
                    $query_str .= 'esd.data <= '.$data[1];
                }
                elseif($data[1] == 0)
                {
                    $query_str .= 'esd.data >= '.$data[0];
                }
                else
                {
                    $query_str .= 'esd.data >= '.$data[0].' AND esd.data <= '.$data[1];
                }
            }
            else
            {
                $query_str .= 'esd.data = "'.$input_data.'"';
            }
        }
        else
        {
            $query_str = '';
        }

        $talents_eav_result = DB::select("SELECT es.parent_id FROM es_entity_relationship es
                    LEFT JOIN es_entity_attribute_data esd ON esd.data_id = es.child_id
                    LEFT JOIN es_entity_attribute ea ON esd.attribute_id = ea.attribute_id
                    WHERE ea.attribute_name = '".$key."' 
                    AND es.child_entity_type_id = '1'
                    AND es.parent_entity_type_id = '7'
                    ".$query_str);

        $result = array();

        foreach($talents_eav_result as $var)
        {
            $result[]= $var->parent_id;
        }

        return $result;
    }

    public static function get_talent_by_eav_based_on_order($key,$input_data=null)
    {
        //$input_data format like '170_175'. which defines range.
        //range values must be numbers. it should not wrapped with \"\".
        if($input_data != null)
        {
            $data = explode('_',$input_data);
            $query_str = 'AND ';

            // get order for the data value
            $es_attribute_data = new es_attribute_data();
            if($key === 't_shirt') {
                if(isset($data[0])) {
                    $order = $es_attribute_data->get_tshirt_size_order($data[0]);
                    $data[0] = $order;
                }
                if(isset($data[1])) {
                    $order = $es_attribute_data->get_tshirt_size_order($data[1]);
                    $data[1] = $order;
                }
                if(isset($input_data)) {
                    $order = $es_attribute_data->get_tshirt_size_order($input_data);
                    $input_data = $order;
                }
            }
            else if($key === 'suit_size') {
                if(isset($data[0])) {
                    $order = $es_attribute_data->get_suit_size_order($data[0]);
                    $data[0] = $order;
                }
                if(isset($data[1])) {
                    $order = $es_attribute_data->get_suit_size_order($data[1]);
                    $data[1] = $order;
                }
                if(isset($input_data)) {
                    $order = $es_attribute_data->get_suit_size_order($input_data);
                    $input_data = $order;
                }
            }
            else if($key === 'hips_women_only_') {
                if(isset($data[0])) {
                    $order = $es_attribute_data->get_hip_size_order($data[0]);
                    $data[0] = $order;
                }
                if(isset($data[1])) {
                    $order = $es_attribute_data->get_hip_size_order($data[1]);
                    $data[1] = $order;
                }
                if(isset($input_data)) {
                    $order = $es_attribute_data->get_hip_size_order($input_data);
                    $input_data = $order;
                }
            }
            else if($key === 'waist') {
                if(isset($data[0])) {
                    $order = $es_attribute_data->get_waist_size_order($data[0]);
                    $data[0] = $order;
                }
                if(isset($data[1])) {
                    $order = $es_attribute_data->get_waist_size_order($data[1]);
                    $data[1] = $order;
                }
                if(isset($input_data)) {
                    $order = $es_attribute_data->get_waist_size_order($input_data);
                    $input_data = $order;
                }
            }
            else if($key === 'bra') {
                if(isset($data[0])) {
                    $order = $es_attribute_data->get_bra_size_order($data[0]);
                    $data[0] = $order;
                }
                if(isset($data[1])) {
                    $order = $es_attribute_data->get_bra_size_order($data[1]);
                    $data[1] = $order;
                }
                if(isset($input_data)) {
                    $order = $es_attribute_data->get_bra_size_order($input_data);
                    $input_data = $order;
                }
            }

            if(isset($data[1]))
            {
                if($data[0] == 0)
                {
                    $query_str .= 'esd.order_id <= '.$data[1];
                }
                elseif($data[1] == 0)
                {
                    $query_str .= 'esd.order_id >= '.$data[0];
                }
                else
                {
                    $query_str .= 'esd.order_id >= '.$data[0].' AND esd.order_id <= '.$data[1];
                }
            }
            else
            {
                $query_str .= 'esd.order_id = "'.$input_data.'"';
            }
        }
        else
        {
            $query_str = '';
        }

        $talents_eav_result = DB::select("SELECT es.parent_id FROM es_entity_relationship es
                    LEFT JOIN es_entity_attribute_data esd ON esd.data_id = es.child_id
                    LEFT JOIN es_entity_attribute ea ON esd.attribute_id = ea.attribute_id
                    WHERE ea.attribute_name = '".$key."' 
                    AND es.child_entity_type_id = '1'
                    AND es.parent_entity_type_id = '7'
                    ".$query_str);

        $result = array();

        foreach($talents_eav_result as $var)
        {
            $result[]= $var->parent_id;
        }

        return $result;
    }

    public static function get_talent_by_skill($input_data)
    {
        $talents_eav_result = DB::select("SELECT es.parent_id FROM es_entity_relationship es    
                    LEFT JOIN es_entity_attribute_data esd ON esd.data_id = es.child_id
                    LEFT JOIN es_entity_attribute ea ON esd.attribute_id = ea.attribute_id
					WHERE es.child_entity_type_id = '1'
                    AND es.parent_entity_type_id = '7' AND ea.attribute_id IN (".implode(',', $input_data).") 
					group by es.parent_id HAVING COUNT(*) = ".count($input_data)."; ");

        $result = array();

        foreach($talents_eav_result as $var)
        {
            $result[]= $var->parent_id;
        }

        return $result;
    }


    //----------------delete attribute data for entity EAV field-------------------
    public static function remove_talent_attribute_data($data_id)
    {
        es_attribute_data::where('data_id',$data_id)->delete();

        BaseModel::es_remove_entity_relationship('ms_talent','es_entity_attribute_data',null,$data_id);

    }

    //----check email exists or not, it needs to be unique as talent login username------
    public static function check_email($talent_id, $email)
    {
        $result = self::where('email',$email)->where('talent_id','<>',$talent_id)->get();

        return $result->isEmpty(); //return true when empty.
    }

    //----get talent profile image----------------------------------------------
    public static function get_profile_image($id)
    {
        $profile_image_data = ms_file::find($id);
        if(count($profile_image_data) > 0)
        {
            $profile_image = $profile_image_data->toArray();
        }
        else
        {
            $profile_image = array('file_name'=>'default_profile_pic','file_ext'=>'jpg','file_path'=>'/images/');
        }

        return $profile_image;
    }

    //---- check/generate slug for talent. it must be unique
    public static function check_talent_slug($slug)
    {
        $slug = preg_replace('/\s+/','',$slug);
        $slug = preg_replace('/[^a-z]+/i', '-', $slug);

        $result = self::withTrashed()->where('talent_slug','LIKE','%'.$slug.'%')->orderBy('talent_slug','DESC')->get();

        if( count($result) > 0)
        {
            $lg_record = $result[0]->talent_slug;
            $lg_record_arr = explode('-', $lg_record);

            if( is_numeric( $lg_record_arr[count($lg_record_arr)-1] ))
            {
                $i = 1;

                while( (count($result)+$i) <= $lg_record_arr[count($lg_record_arr)-1] )
                {
                    $i++;
                }

                $slug = $slug.'-'.(count($result)+$i);
            }
            else{
                $slug = $slug.'-'.(count($result)+1);
            }
        }

        return $slug;
    }

    //---- check email of talent. it must be unique
    public static function check_talent_email($email)
    {
        $result = self::where('email',strtolower($email))->first();
        if( count($result) > 0)
        {
            return $result->talent_id;
        }
        else
        {
            return 0;
        }
    }

    public static function get_simple_talent_data($talent_id)
    {
        $talent= ms_talent::find($talent_id);
        $result = array();

        if(count($talent) > 0)
        {
            $result['talent_id'] = $talent->talent_id;
            $result['talent_name'] = ucwords($talent->first_name).' '.$talent->last_name;
            $image = self::get_profile_image($talent->primary_image_id);
            $result['talent_image'] = $image['file_path'].$image['file_name'].'_thumbnail.'.$image['file_ext'];
        }

        return $result;
    }

    //Acessors
    public function getDobAttribute($value) { return self::getDecrypted($value); }
    public function setDobAttribute($value) { $this->attributes['dob'] = self::storeEncrypted($value); }

    public function getBankAttribute($value) { return self::getDecrypted($value); }
    public function setBankAttribute($value) { $this->attributes['bank'] = self::storeEncrypted($value); }

    public function getRsaAttribute($value) { return self::getDecrypted($value); }
    public function setRsaAttribute($value) { $this->attributes['rsa'] = self::storeEncrypted($value); }

    public function getPassportAttribute($value) { return self::getDecrypted($value); }
    public function setPassportAttribute($value) { $this->attributes['passport'] = self::storeEncrypted($value); }

    public function getAbnAttribute($value) { return self::getDecrypted($value); }
    public function setAbnAttribute($value) { $this->attributes['abn'] = self::storeEncrypted($value); }

    public function getTfnAttribute($value) { return self::getDecrypted($value); }
    public function setTfnAttribute($value) { $this->attributes['tfn'] = self::storeEncrypted($value); }

    public function getTradingNameAttribute($value) { return self::getDecrypted($value); }
    public function setTradingNameAttribute($value) { $this->attributes['trading_name'] = self::storeEncrypted($value); }

    public function getBankBsbAttribute($value) { return self::getDecrypted($value); }
    public function setBankBsbAttribute($value) { $this->attributes['bank_bsb'] = self::storeEncrypted($value); }

    public function getBankAccountAttribute($value) { return self::getDecrypted($value); }
    public function setBankAccountAttribute($value) { $this->attributes['bank_account'] = self::storeEncrypted($value); }

    public function getBankAccountNameAttribute($value) { return self::getDecrypted($value); }
    public function setBankAccountNameAttribute($value) { $this->attributes['bank_account_name'] = self::storeEncrypted($value); }

    public function getSuperProviderAttribute($value) { return self::getDecrypted($value); }
    public function setSuperProviderAttribute($value) { $this->attributes['super_provider'] = self::storeEncrypted($value); }

    public function getSuperAccountAttribute($value) { return self::getDecrypted($value); }
    public function setSuperAccountAttribute($value) { $this->attributes['super_account'] = self::storeEncrypted($value); }

    private static function storeEncrypted($value) {
        return storeEncryptedField($value);
    }

    private static function getDecrypted($value) {
        return getDecryptedField($value);
    }

    public static function printActiveTalentsWithInvalidTFN() {
        $arrTalents = \ms_talent::all();

        foreach($arrTalents as $objTalent) {
            if($objTalent->talent_status == 3) {
                continue;
            }

            $tfn = getJustNumbersFromString($objTalent->tfn);
            if(!self::isTfnValid($tfn)) {
                echo "{$objTalent->getIdentifierString()} has an invalid TFN (typed: {$objTalent->tfn})\n";
            }
        }
    }

    public static function getActiveTalentsWithInvalidData() {
        return \ms_talent::where('talent_status', '3')->orderBy('first_legal_name', 'asc')->orderBy('first_name', 'asc')->get();
    }

    public static function printActiveTalentsWithInvalidData($page) {

        $talents = \ms_talent::select(DB::raw("talent_id,CONCAT(`first_legal_name`, ' ', `last_legal_name`) as full_name,CONCAT(`first_name`, ' ', `last_name`) as stage_name,talent_id, first_legal_name,last_legal_name, first_name, last_name, superfund_id, tfn"))->whereIn('talent_status', ['1','2'])->orderBy('first_name', 'asc')->orderBy('last_name', 'asc')->get()->toArray();

        $newTalents = array();

        foreach($talents as $objTalent) {
            $error = false;
            $objTalent['error'] = '';

            if(empty($objTalent['first_legal_name'])) {
                $error = true;
                $objTalent['error'] .= "Hasn't set the First Legal Name<br />";
            }

            if(empty($objTalent['last_legal_name'])) {
                $error = true;
                $objTalent['error'] .= "Hasn't set the Last Legal Name<br />";
            }

            if(empty($objTalent['superfund_id']) || $objTalent['superfund_id'] < 1) {
                $error = true;
                $objTalent['error'] .= "Hasn't set a superfund<br />";
            }

            $tfn = getJustNumbersFromString($objTalent['tfn']);
            if(!self::isTfnValid($tfn)) {
                $error = true;
                $objTalent['error'] .= "has an invalid TFN (typed: {$objTalent['tfn']})<br />";
            }

            if($error) {
                $newTalentsErrorArray[]=$objTalent;
            }
        }


        return $newTalentsErrorArray;



        /*foreach($arrTalents as $objTalent) {
            $error = false;

            if(empty($objTalent->first_legal_name)) {
                $error = true;
                echo "{$objTalent->getIdentifierString()} hasn't set the First Legal Name<br>";
            }
            if(empty($objTalent->last_legal_name)) {
                $error = true;
                echo "{$objTalent->getIdentifierString()} hasn't set the Last Legal Name<br>";
            }

            if(empty($objTalent->super_provider) || $objTalent->super_provider < 1) {
                $error = true;
                echo "{$objTalent->getIdentifierString()} hasn't set a superfund<br>";
            }

            $tfn = getJustNumbersFromString($objTalent->tfn);
            if(!self::isTfnValid($tfn)) {
                $error = true;
                echo "{$objTalent->getIdentifierString()} has an invalid TFN (typed: {$objTalent->tfn})<br>";
            }

            if($error) {
                echo "<br>";
            }
        }*/


        //$datas = Paginator::make($articles, count($paginator), $perPage);



        // $arrTalents = \ms_talent::where('talent_status', '3')->orderBy('first_legal_name', 'asc')->orderBy('first_name', 'asc')->get();

        /* foreach($arrTalents as $objTalent) {
             $error = false;

             if(empty($objTalent->first_legal_name)) {
                 $error = true;
                 echo "{$objTalent->getIdentifierString()} hasn't set the First Legal Name<br>";
             }
             if(empty($objTalent->last_legal_name)) {
                 $error = true;
                 echo "{$objTalent->getIdentifierString()} hasn't set the Last Legal Name<br>";
             }

             if(empty($objTalent->super_provider) || $objTalent->super_provider < 1) {
                 $error = true;
                 echo "{$objTalent->getIdentifierString()} hasn't set a superfund<br>";
             }

             $tfn = getJustNumbersFromString($objTalent->tfn);
             if(!self::isTfnValid($tfn)) {
                 $error = true;
                 echo "{$objTalent->getIdentifierString()} has an invalid TFN (typed: {$objTalent->tfn})<br>";
             }

             if($error) {
                 echo "<br>";
             }
         }*/
    }

    public static function isTfnValid($tfnOnlyNumbers) {
        $arrAtoModulo11Checking = [1, 4, 3, 7, 5, 8, 6, 9, 10];

        if(strlen($tfnOnlyNumbers) != 9) {
            return false;
        }

        $sum = 0;
        for($i = 0; $i < 9; $i++) {
            $sum += $tfnOnlyNumbers[$i] * $arrAtoModulo11Checking[$i];
        }

        if($sum % 11 !== 0) {
            return false;
        }

        return true;
    }

    public static function boot() {
        parent::boot();
        self::observe(new \App\Libraries\Observers\TalentObserver());
    }

}
