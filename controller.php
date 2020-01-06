<?php

class TalentController extends WinkmumController {

    /*
    |--------------------------------------------------------------------------
    | Default Admin Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function __construct()
    {
        return parent::__construct();
    }

    /* =======================
        // INDEX PAGE
    ======================= */
    public function get_index()
    {
        //check if it has 't' in post, it is ajax mode. it will output table.
        if( !Input::has('t') || Input::get('t') != 'ajax')
        {
            $message = (Session::get('message')) ? Session::get('message') : '';

            // Compose breadcrumb data
            $breadcrumb_data = array(
                'search' => View::make('winkmum.partials.search'),
                'current_page' => 'Talents'
            );

            // Instantiate breadcrumb view object
            $breadcrumb = View::make(
                'winkmum.partials.breadcrumb',
                $breadcrumb_data
            );

            // Compose navigation data
            $navigation_data = array(
                'active_talents' => 1,
            );

            // Instantiate navigation object
            $navigation = View::make(
                'winkmum.partials.navigation',
                $navigation_data
            );

        }
        //get filters and display fields
        //----------------------------------------------------------------------
        if( !Input::has('t') || Input::get('t') != 'ajax')
        {
            $fields = ms_talent::get_display_fields();
        }
        else{
            $fields = ms_talent::get_comm_display_fields();
        }

        if(Input::has('p') && Input::get('p') != '')
        {
            Session::set('p', Input::get('p'));
        }

        $filters = ms_talent::get_filters_new($this->state,$this->status,$this->rating,$this->talent_category, $this->talent_height_new, $this->talent_eye_colour, $this->talent_bra, $this->shoe_size,$this->hair_colour, $this->collar_size, $this->dress_size, $this->pant_size, $this->t_shirt, $this->talent_chest_bust, $this->availability_option,$this->suit_size,$this->waist_size, $this->hip_size);//

        $orderby = '';
        if(Input::has('orderby') && Input::get('orderby') != '')
        {
            $orders = explode('|',Input::get('orderby'));
            foreach($orders as $order)
            {
                $order_data = explode('-',$order);

                //build list base on input
                $orderby .= '<li class="'.(($order_data[1] == 0 )? 'btn btn-info':'btn btn-warning').'" data-field="'.$order_data[0].'" data-order="'.$order_data[1].'"><i class="'.(($order_data[1] == 0 )? 'icon-sort-by-alphabet ':'icon-sort-by-alphabet-alt').'"></i>&nbsp;&nbsp;'. $fields[$order_data[0]]['title'].' | <span class="order_delete"><i class="icon-remove"></i></span></li>';

                //set field off from dropdown
                $fields[$order_data[0]]['option'] = 0;
            }
        }
        else{
            $orderby .= '<li class="btn btn-info" data-field="first_name" data-order="asc"><i class="icon-sort-by-alphabet"></i>&nbsp;&nbsp;Firstname | <span class="order_delete"><i class="icon-remove"></i></span></li>';
            $orderby .= '<li class="btn btn-info" data-field="last_name" data-order="asc"><i class="icon-sort-by-alphabet"></i>&nbsp;&nbsp;Lastname | <span class="order_delete"><i class="icon-remove"></i></span></li>';
        }

        $search = new Seacrh('talent', Input::all(), $this->paginate);
        $talents_arr = $search->getResult();

        $es_attribute_data = new es_attribute_data();

        //add eav data
        if(!empty($talents_arr))
        {
            foreach($talents_arr['data'] as $key=>$talent)
            {
                $eav_data = es_attribute_data::get_attributes_data($talent['talent_id'],'ms_talent');

                if(!empty($eav_data))
                {
                    foreach($eav_data as $index=>$data)
                    {
                        $talents_arr['data'][$key][$index] = $data;
                    }
                }

                //availability data
                // for display availability column for today only.
                if(Input::get("startDate") == null || Input::get("endDate") == null) {
                    $talents_arr['data'][$key]['availability'] = fs_talent_availability::get_talent_availability($talent['talent_id']);
                }
                else {
                    $talents_arr['data'][$key]['availability'] = fs_talent_availability::get_talent_availability($talent['talent_id'], Input::get("startDate"), Input::get("endDate"));
                }
            }
        }
        //end build sql query.
        //print_r(BaseModel::lastQuery());
        /* echo '<pre>';
       print_r($talents_arr);
       echo '</pre>';
       die();*/
        //

        if(Input::has('t') && Input::get('t') == 'ajax')
        {
            echo View::make(
                'winkmum.pages.talent_ajax',
                array(
                    'talents' => $talents_arr,
                    'fields' => $fields,

                )
            );
        }
        else
        {
            // Compose content data
            $content_data = array(
                'breadcrumb' => $breadcrumb,
                'title' => 'Talent',
                'title_description' => "search",
                'custom_scripts' => View::make('winkmum.layout.custom.talent-list'),
                'topnav' => View::make('winkmum.partials.topnav'),
                'navigation' => $navigation,
                'content' => View::make(
                    'winkmum.pages.talents',
                    array(
                        'message' => $message,
                        'talents' => $talents_arr,
                        'fields' => $fields,
                        'filter_panel' => View::make('winkmum.partials.filter_new',array(
                                'fields'    =>  $fields,
                                'filters'   =>  $filters,
                                'orderby'   =>  $orderby
                            )
                        ),
                    )
                )
            );

            // Instantiate content object
            $content = View::make('winkmum.layout.content', $content_data);

            // Compose template data
            $data = array(
                'page_title' => "Wink Administration Panel",
                'body_class' => "talents",
                'template' => $content
            );

            // Render final template
            return View::make('winkmum.layout.template', $data);
        }
    }


    /* =======================
        // TALENT PROFILE
    ======================= */
    public function get_talent_profile($id = null)
    {
        $message = (Session::get('message')) ? Session::get('message') : null;

        // Compose breadcrumb data
        $breadcrumb_data = array(
            'search' => View::make('winkmum.partials.search'),
            'current_page' => 'Talent Profile'
        );

        // Instantiate breadcrumb view object
        $breadcrumb = View::make(
            'winkmum.partials.breadcrumb',
            $breadcrumb_data
        );

        // Compose navigation data
        $navigation_data = array(
            'active_talent_profile' => isset($id) ? 0 : 1,
            'active_talents' => isset($id) ? 1 : 0
        );

        // Instantiate navigation object
        $navigation = View::make(
            'winkmum.partials.navigation',
            $navigation_data
        );

        //get eav fields
        $talent_profile_eav_fields = get_entity_attributes_taxonomy_depth('ms_talent');
        $email_fields = fs_emails::get_display_talent_fields();
        $sms_fields = fs_sms::get_display_talent_fields();

        if ($id) {
            $talent_profile_model_data = ms_talent::find($id);

            $talent_profile_model_data->talent_gmap_link = write_google_map(array(
                $talent_profile_model_data->address,
                $talent_profile_model_data->city,
                $talent_profile_model_data->state,
                $talent_profile_model_data->postcode,
                $talent_profile_model_data->country
            ));

            $eav_data = es_attribute_data::get_attributes_data($id,'ms_talent');

            //get edited protected fields
            $talent_edited_fields = ($talent_profile_model_data->wc_modified != null) ?unserialize($talent_profile_model_data->wc_modified): array();



            //website images: get all images belongs to this talent
            $talent_files = BaseModel::es_select('ms_talent','ms_file',array('talent_id'=>$id),null,2);

            $images = array();
            $files = array();

            foreach($talent_files as $file)
            {
                switch($file->file_type)
                {
                    case 'photo':
                        //add dimensions to image
                        if(file_exists(public_path().$file->file_path.$file->file_name.'.'.$file->file_ext))
                        {
                            list($width,$height) = getimagesize(public_path().$file->file_path.$file->file_name.'.'.$file->file_ext);
                            $file->width = $width;
                            $file->height = $height;
                        }
                        else{
                            $file->width = 0;
                            $file->height = 0;
                        }
                        $images[] = $file;
                        break;
                    case 'talent_file':
                        $files[] = $file;
                        break;
                }
            }

            //get talent image
            $profile_image = ms_talent::get_profile_image($talent_profile_model_data->primary_image_id);

            //get talent web images
            $website_images = BaseModel::es_select_entity_related_files('ms_talent',$id,null,'web_image',true);

            //get talent digi images
            $digi_images = BaseModel::es_select_entity_related_files('ms_talent',$id,null,'digi_image',true);

            //get talent compcard images:
            $comp_images = BaseModel::es_select_entity_related_files('ms_talent',$id,null,'compcard',true);

            $emails = fs_emails::get_email_by_talent($id);

            $sms_list = fs_sms::get_sms_by_talent($id);

            $talent_client_list = ms_client::select(DB::raw('DISTINCT ms_client.client_id, ms_client.client_title, MAX(fs_shift.date) as max_shift_date'))
                ->leftJoin('ms_job', 'ms_job.client_id', '=', 'ms_client.client_id')
                ->leftJoin('fs_roster_row', 'fs_roster_row.job_id', '=', 'ms_job.job_id')
                ->leftJoin('fs_roster_shift', 'fs_roster_row.roster_row_id', '=', 'fs_roster_shift.roster_row_id')
                ->leftJoin('fs_shift', 'fs_roster_shift.shift_id', '=', 'fs_shift.shift_id')
                ->leftJoin('ms_talent', 'fs_roster_row.talent_id', '=', 'ms_talent.talent_id')
                ->where('fs_roster_row.talent_id', '=', $id)
                ->whereNotNUll('ms_client.client_id')
                ->groupBy('ms_client.client_id')
                ->orderBy('ms_client.client_title', 'asc')
                ->get();

            $talent_job_list = ms_job::select(DB::raw('ms_job.*, ms_client.client_title, t_user.user_first_name, t_user.user_last_name, max(fs_shift.date) as max_shift_date, min(fs_shift.date) as min_shift_date'))

                ->leftJoin('t_user', 't_user.user_id', '=', 'job_manager')
                ->leftJoin('ms_client', 'ms_job.client_id', '=', 'ms_client.client_id')
                ->leftJoin('fs_roster_row', 'fs_roster_row.job_id', '=', 'ms_job.job_id')
                ->leftJoin('fs_roster_shift', 'fs_roster_row.roster_row_id', '=', 'fs_roster_shift.roster_row_id')
                ->leftJoin('fs_shift', 'fs_roster_shift.shift_id', '=', 'fs_shift.shift_id')
                ->leftJoin('ms_talent', 'fs_roster_row.talent_id', '=', 'ms_talent.talent_id')
                ->where('fs_roster_row.talent_id', '=', $id)
                ->where('fs_roster_row.roster_confirm', '=', 1)
                ->groupBy('ms_job.job_id')
                ->orderBy('ms_job.job_id', 'desc')
                ->get();


            if(!empty($talent_job_list)){
                foreach($talent_job_list as $key => $jobs){

                    $talent_job_list[$key]->job_type_title = $this->job_type[$jobs->job_type];
                    $talent_job_list[$key]->job_status_title = $this->job_status[$jobs->job_status];

                }

            }
            /* $queries = DB::getQueryLog();
                $last_query = end($queries);
                print_r($last_query );
            */

            /*if(!empty($talent_client_list)){
                  foreach($talent_client_list as $key => $client){

                      (!empty($talent->talent_category)) ? $client_talent_list[$key]->talent_category_title = $this->talent_category[$talent->talent_category] : $client_talent_list[$key]->talent_category_title =  'n/a';

                  }
              }*/
            $talent_profile_model_data['category_influencers']=unserialize($talent_profile_model_data['category_influencers']);

        } else {
            $talent_profile_model_data = null;
            $eav_data = null;
            $talent_image = null;
            $images = null;
            $website_images = null;
            $digi_images = null;
            $comp_images = null;
            $files = null;
            $profile_image = null;
            $talent_edited_fields = array();
            $emails = null;
            $sms_list = null;
            $talent_client_list = array();
            $talent_job_list = array();
        }
        //talent availability data
        //var_dump($talent_profile_model_data->availabilities->toArray());


        //get talent profile image

        // Compose content data
        $content_data = array(
            'breadcrumb' => $breadcrumb,
            'title' => 'Talent',
            'title_description' => "profile",
            'custom_scripts' => View::make('winkmum.layout.custom.talent-profile'),
            'topnav' => View::make('winkmum.partials.topnav'),
            'navigation' => $navigation,
            'url' => 'type=talent&id='.$id,
            'content' => View::make(
                'winkmum.pages.talentprofile',
                array(
                    'profile_image'  =>  $profile_image,
                    'images'    => $images,
                    'files'     =>  $files,
                    'website_images'    =>  $website_images,
                    'digi_images'    =>  $digi_images,
                    'comp_images'   =>$comp_images,
                    'talent_profile_model_data' => $talent_profile_model_data,
                    'eav_fields' => $talent_profile_eav_fields,
                    'eav_data' => $eav_data,
                    'talent_edited_fields' => $talent_edited_fields,
                    'message' => $message,
                    'email_fields'  =>  $email_fields,
                    'sms_fields'    =>  $sms_fields,
                    'emails'    =>  $emails,
                    'sms_list'  =>  $sms_list,
                    'talent_client_list' => $talent_client_list,
                    'talent_job_list' => $talent_job_list
                )
            ),
        );

        // Instantiate content object
        $content = View::make('winkmum.layout.content', $content_data);

        // Compose template data
        $data = array(
            'page_title' => "Wink Administration Panel",
            'body_class' => "talent-profile",
            'template' => $content
        );

        //before render page. reset highlight class
        if($id != null)
        {
            $talent_model = ms_talent::find($id);
            if($talent_model->wc_modified != null)
            {
                $talent_model->wc_modified = NULL; //reset alert

                $talent_model->save();
            }
        }

        // Render final template
        return View::make('winkmum.layout.template', $data);

    }

    /*==============================
    //login to winkcentral as talent
    ==============================*/
    public function post_login_wc($id=null)
    {
        $is_auth = false;
        if(Session::has('user'))
        {
            //check session.

            $is_auth = true;
        }

        if($id != null && $is_auth)
        {
            $talent = ms_talent::find($id);
            $user =  array(
                'firstname' =>  $talent->first_name,
                'lastname' =>  $talent->last_name,
                'role'      =>  'talent',
                'email'     =>  $talent->email,
                'login_id'  =>  $talent->talent_id
            );
            Session::forget('talent');
            Session::put('talent',(object) $user);

            return Redirect::to('winkcentral/home');
        }
        else
        {
            echo 'Access denied.';
        }
    }

    /* ===========================
        // TALENT PROFILE PROCESS
    =========================== */
    public function post_process_talent_profile()
    {

        $id = Input::get('talent_id');
        $is_update = ($id != '') ? true : false;

        $error = false;
        $message = false;

        $data = Input::except('_token');
        $talent_validation_result = ms_talent::validate($data);

        if ($is_update) {
            $talent_model = ms_talent::find($id);
        } else  {
            $talent_model = new ms_talent;
        }

        $induction = $talent_model->induction_complete;

        //get fields.
        $native_fields = $talent_model->get_table_fields();
        $eav_fields = $talent_model->get_eav_table_fields();


        try {
            if ($talent_validation_result->fails()) { //validation fails
                throw new Exception('Validation Fails');
            } else { // validation successful

                // process native data
                foreach ($data as $key => $data_elem) {
                    if( in_array($key,$native_fields))
                    {
                        if($key == 'password' )
                        {
                            if(isset($data_elem) && $data_elem != '')
                            {
                                $talent_model->$key = Hash::make($data_elem);
                            }
                        }
                        elseif( $key == 'email')
                        {
                            //check email exists
                            if(ms_talent::check_email($id,$data_elem))
                            {

                                $talent_model->$key = $data_elem;
                            }
                            else
                            {
                                throw new Exception('Email address already exists.');
                            }
                        }
                        elseif( $key == 'talent_start_date' || $key == 'dob' )
                        {
                            if($data_elem != null || $data_elem != '')
                            {
                                $str = DateTime::createFromFormat('d/m/Y', $data_elem);
                                $talent_model->$key = $str->format('Y-m-d');
                            }
                        }
                        elseif( $key == 'induction_complete_date')
                        {
                            if($data_elem != null || $data_elem != '')
                            {
                                $str = DateTime::createFromFormat('d/m/Y H:i:s', $data_elem);
                                $talent_model->$key = $str->format('Y-m-d H:i:s');
                            }
                            else{
                                $talent_model->$key = date('Y-m-d H:i:s');
                            }
                        }
                        else
                        {
                            $talent_model->$key = $data_elem;
                        }
                    }
                }


                if (is_null(Input::get('category_influencers')))
                {
                    $talent_model->category_influencers = serialize(array());
                } else
                {
                    $keys = array_keys(Input::get('category_influencers'));
                    $talent_model->category_influencers = serialize($keys);

                }
                if (is_null(Input::get('influential')))
                {
                    $talent_model->influential = 0;
                }
                else
                {
                    $talent_model->instagram_followers = InstagramController::get_followers($data['instagram']);
                }


                if ( Input::get('induction_complete') == 1 && Input::get('induction_complete') != $induction )
                {
                    $talent_model->induction_complete_date = date('Y-m-d H:i:s');
                    $talent_model->induction_complete = 1;
                }elseif( Input::get('induction_complete') == 0 && Input::get('induction_complete') != $induction ) {
                    $talent_model->induction_complete_date = null;
                    $talent_model->induction_complete = 0;
                }

                $talent_model->push_to_xero = Input::get('push_to_xero', 1);

                //start to deal with talent slug
                if(!$is_update)  $talent_model->talent_slug = ms_talent::check_talent_slug(strtolower($talent_model->first_name.'-'.$talent_model->last_name));

                $talent_model->wc_modified = NULL; //reset alert

                $talent_model->save();

                $inserted_id = $talent_model->talent_id;

                if ( !$is_update ) {
                    if($inserted_id != 0 )
                    {
                        if( isset($eav_fields['fields']) && !empty($eav_fields['fields']))
                        {
                            foreach ($data as $eav_key => $eav_data) {

                                if (in_array($eav_key, $eav_fields['fields'])) {

                                    $eav_model = new es_attribute_data;

                                    // Now save the attribute value
                                    $result = $eav_model->update_attribute_data($inserted_id,'ms_talent',$eav_fields['data'][$eav_key]->attribute_id, $eav_data);
                                    if(!$result)
                                    {
                                        throw new Exception('Error: '.$eav_key);
                                    }

                                } else {
                                    //echo '[SKIPPED] '. $eav_key . '<br/>';  //for debug only
                                }

                            } // loop ratecard data for EAV fields
                        }
                    }

                } else { // Must be in Update Mode

                    //delete skill eva data
                    if(Input::has('delete_data_arr') && Input::get('delete_data_arr') != '')
                    {
                        $del_eav_arr = json_decode( Input::get('delete_data_arr'));

                        foreach($del_eav_arr as $data_id)
                        {
                            ms_talent::remove_talent_attribute_data($data_id);
                        }
                    }

                    // Traverse input data
                    if( isset($eav_fields['fields']) && !empty($eav_fields['fields']))
                    {
                        foreach ($data as $eav_key => $eav_data) {

                            if (in_array($eav_key, $eav_fields['fields'])) {
                                $eav_model = new es_attribute_data;

                                $result = $eav_model->update_attribute_data($inserted_id,'ms_talent',$eav_fields['data'][$eav_key]->attribute_id, $eav_data);
                                if(!$result)
                                {
                                    throw new Exception('Error: '.$eav_key);
                                }
                            }
                        }
                    }
                }

                if ($is_update) {
                    $message = 'Talent <strong>'.$talent_model->first_name.' '.$talent_model->last_name.'</strong> has been updated';
                    $redirect_to = 'edit';
                } else {
                    $message = 'Talent <strong>'.$talent_model->first_name.' '.$talent_model->last_name.'</strong> has been created';
                    $redirect_to = 'add';
                    $id = $inserted_id;
                }
            }

        } catch (Exception $process_talent_profile_error) {
            $error = true;
            $message = $process_talent_profile_error->getMessage();
        }

        $panel =  (Input::has('tab_active') ?  Input::get('tab_active') : '');

        if ($error) {
            if(isset($id) && $id != '')
            {
                return Redirect::to("winkmum/talent/profile/edit/".$id.$panel)
                    ->withErrors($talent_validation_result->messages())
                    ->with('error',true)
                    ->with('message',$message)
                    ->withInput();
            }
            else
            {
                return Redirect::to("winkmum/talent/profile/")
                    ->withErrors($talent_validation_result->messages())
                    ->with('error',true)
                    ->with('message',$message)
                    ->withInput();
            }
        } else {
            return Redirect::to("winkmum/talent/profile/edit/".$id.$panel)
                ->with('message', $message);
        }

    }


    /* =======================
        // CLIENT CONTACT FORM
    ======================= */
    public function get_client_contact_form($id = null)
    {
        $client_contact_model_data = null;
        if ($id) {
            $client_contact_model_data = ms_Contact::find($id);
        }

        $data = array(
            'operation' => ($id) ? 'Update' : 'Add',
            'message' => (Session::get('message')) ? Session::get('message') : false,
            'client_contact_model_data' => $client_contact_model_data
        );

        Session::forget('message');

        return View::make(
            'winkmum.partials.clientprofile.contactform',
            $data
        );
    }

    /* =======================
        // CLIENT CONTACT LIST
    ======================= */
    public function get_client_contact_list($id)
    {
        $data = array();

        $client_contact_model = new ms_Contact();
        $client_contact_model_data = $client_contact_model
            ->where(ms_Contact::FLD_CLIENT_ID, '=', $id)
            ->get();

        $data = array(
            'success' => true,
            'client_list' => View::make(
                'winkmum.partials.clientprofile.clientcontactlist',
                array(
                    'client_contact_model_data' => $client_contact_model_data,
                    'client_basic_select' => Config::get('client-form.basic')
                )
            )->render()
        );

        return Response::json($data);
    }

    /* ==========================
        // CONTACT CLIENT PROCESS
    =========================== */
    public function process_client_contacts()
    {

        $contact_data = Input::all();
        $errors = array();
        $contact_model = false;
        $id = $contact_data['contact-id'];
        $is_update = ($id) ? true : false;
        $message = false;

        $contact_validation_result = ms_Contact::validate($contact_data);

        try {

            if ($contact_validation_result->fails()) {
                throw new Exception('');
            }

            if (!$id) {
                $contact_model = new ms_Contact();
            } else {
                $contact_model = ms_Contact::find($id);
            }

            $contact_model->{ms_Contact::FLD_FIRST_NAME} = $contact_data['contact-first-name'];
            $contact_model->{ms_Contact::FLD_LAST_NAME} = $contact_data['contact-last-name'];
            $contact_model->{ms_Contact::FLD_CLIENT_ID} = $contact_data['account-id'];
            $contact_model->{ms_Contact::FLD_ROLE_TITLE} = $contact_data['contact-role-title'];
            $contact_model->{ms_Contact::FLD_EMAIL} = $contact_data['contact-email'];
            $contact_model->{ms_Contact::FLD_MOBILE} = $contact_data['contact-mobile'];
            $contact_model->{ms_Contact::FLD_PHONE} = $contact_data['contact-phone'];
            $contact_model->{ms_Contact::FLD_SKYPE} = $contact_data['contact-skype'];
            $contact_model->{ms_Contact::FLD_NOTES} = $contact_data['contact-notes'];

            $contact_model->save();

            if ($is_update) {
                $contact_model = admin_Contacts::find($id);
                $message = 'Contact has been updated.';
            } else {
                $contact_model = false;
                $message = 'Contact has been created.';
            }

        } catch (Exception $e) {
            $errors = $contact_validation_result->messages();
            return Redirect::to('winkmum/contact/contact-form')
                ->withErrors($contact_validation_result)
                ->withInput();
        }

        return Redirect::to("winkmum/contact/contact-form/{$id}")
            ->with('message', $message);
    }

    /* =======================
	// TALENT CALENDAR
	======================= */
    public function get_talent_calendar($id)
    {
        $talent =  ms_talent::find($id);
        $profile_image = ms_file::find($talent->primary_image_id);
        if(empty($profile_image))
        {
            $profile_image_str = '';
        }
        else
        {
            $profile_image_str = $profile_image->file_path.$profile_image->file_name.'_thumbnail.'.$profile_image->file_ext;

        }
        // Compose breadcrumb data
        $breadcrumb_data = array(
            'search' => View::make('winkmum.partials.search'),
            'current_page' => 'Talent Calendar'
        );

        // Instantiate breadcrumb view object
        $breadcrumb = View::make(
            'winkmum.partials.breadcrumb',
            $breadcrumb_data
        );

        // Compose navigation data
        $navigation_data = array(
            'active_talent_calendar' => 1,
        );

        // Instantiate navigation object
        $navigation = View::make(
            'winkmum.partials.navigation',
            $navigation_data
        );

        // Compose content data
        $content_data = array(
            'breadcrumb' => $breadcrumb,
            'title' => 'Talent',
            'title_description' => "calendar",
            'custom_scripts' => View::make('winkmum.layout.custom.talent-calendar'),
            'topnav' => View::make('winkmum.partials.topnav'),
            'navigation' => $navigation,
            'content' => View::make('winkmum.pages.talentcalendar',
                array(
                    'talent_image' => $profile_image_str,
                    'talent'    =>  $talent
                )
            )
        );

        // Instantiate content object
        $content = View::make('winkmum.layout.content', $content_data);

        // Compose template data
        $data = array(
            'page_title' => "Wink Administration Panel",
            'body_class' => "talent-calendar",
            'template' => $content
        );

        // Render final template
        return View::make('winkmum.layout.template', $data);
    }

    /* ==========================
    // Get Job & Availability for talent
    =========================== */
    public function get_calendar_info()
    {
        /*  if(Input::has('talent_id')
          {
              $info_arr = array();



          }*/
    }


    /* ==========================
    // DELETE TALENT PROCESS
    =========================== */
    public function process_talent_delete()
    {
        $result = array();

        if( Input::has('talent_id'))
        {
            $talent = ms_talent::find(Input::get('talent_id'));
            if( count($talent) > 0)
            {
                $talent_name = $talent->first_name . ' '. $talent->last_name;

                $talent->delete();

                $result['message'] = $talent_name . ' has been deleted successfully.';
                $result['error'] = 0;
            }
            else{
                $result['message'] = 'Talent can not be found.';
                $result['error'] = 1;
            }

        }
        else
        {
            $result['message'] = 'Input is not valid.';
            $result['error'] = 1;
        }

        echo json_encode($result);
    }

    /* =========================================================================
    // Add file via Ajax call
    ==========================================================================*/
    public function add_file()
    {
        if( Input::has('talent_id') && Input::get('talent_id') != '' )
        {
            $file = Input::file('add-file');

            $result = array();

            $ext = strtolower($file->getClientOriginalExtension());
            $talent = ms_talent::find(Input::get('talent_id'));
            $filename = strtolower($talent->first_name)[0].strtolower($talent->last_name);
            $filename = preg_replace('/\s+/','',$filename);
            $filename = preg_replace('/[^a-z]+/i', '-', $filename).'_'.Input::get('talent_id').'_'.strtotime(date('Y-m-d H:i:s'));

            $is_upload = Input::file('add-file')->move( public_path().$this->upload_file_path.'/files/', $filename.'.'.$ext);

            $file_title = (Input::has('new-file-title') ? Input::get('new-file-title') : $filename);
            $recorded_at = (Input::has('new-file-date') ? date_reformat(Input::get('new-file-date')) : date('m/d/Y'));

            $new_file = new ms_file;
            $new_file->file_name = $filename;
            $new_file->file_ext = $ext;
            $new_file->file_title =  $file_title;
            $new_file->recorded_at = date('Y-m-d', strtotime($recorded_at));
            $new_file->file_path = $this->upload_file_path.'/files/';
            $new_file->file_type = 'file';
            $new_file->status = 1;

            $new_file->save();

            //create realationship between talent and file
            BaseModel::es_create_entity_relationship('ms_talent','ms_file',Input::get('talent_id'),$new_file->file_id);

            $result['talent_id'] = Input::has('talent_id');
            $result['file_id'] = $new_file->file_id;
            $result['url'] = URL::asset('uploads/files').'/'.$filename;
            $result['name'] = $filename.'.'.$ext;
            $result['ext'] = strtoupper($ext);
            $result['title'] =  $file_title;
            $result['date'] = $recorded_at;

            echo json_encode($result);

        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
    // Add Image via Ajax call
    ==========================================================================*/
    public function add_image()
    {
        if( Input::has('talent_id') && Input::get('talent_id') != '' )
        {
            $file = Input::file('add-image-file');

            $result = array();

            $ext = strtolower($file->getClientOriginalExtension());
            if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
            {
                $talent = ms_talent::find(Input::get('talent_id'));
                $filename = strtolower($talent->first_name)[0].strtolower($talent->last_name);
                $filename = preg_replace('/\s+/','',$filename);
                $filename = preg_replace('/[^a-z]+/i', '-', $filename).'_'.Input::get('talent_id').'_'.strtotime(date('Y-m-d H:i:s'));

                $is_upload = Input::file('add-image-file')->move( public_path().$this->upload_file_path.'/talents/'.date('Y/m/'), $filename.'.'.$ext);

                $file_title = (Input::has('new-img-title') ? Input::get('new-img-title') : $filename);
                $recorded_at = (Input::has('new-img-date') ? date('d M Y',strtotime(Input::get('new-img-date'))) : date('d M Y'));

                $new_image = new ms_file;
                $new_image->file_name = $filename;
                $new_image->file_ext = $ext;
                $new_image->file_title =  $file_title;
                $new_image->recorded_at = date('Y-m-d', strtotime($recorded_at));
                $new_image->file_path = $this->upload_file_path.'/talents/'.date('Y/m/');
                $new_image->status = Input::get('img-status');
                $new_image->file_type = 'photo';

                $new_image->save();

                //add alert need to review.
                if(Input::get('img-status') == 0)
                {
                    $talent->img_review =  $talent->img_review +1;
                    $talent->save();
                }
                //create all alt images
                if(!check_file_exist(public_path().$this->upload_file_path.'/talents/'.date('Y/m/').$filename.'.'.$ext))
                {
                    echo 0;
                    exit;
                }

                //Queue::push('ProcessImage@generate_images_from_upload',array('image_name'=>$filename,'ext'=>$ext,'path'=>public_path().$this->upload_file_path.'/talents/'.date('Y/m/')));
                //create all alt images
                generate_images_from_upload($this->image_type, public_path().$this->upload_file_path.'/talents/'.date('Y/m/'), $filename, $ext);

                //create realationship between talent and file
                BaseModel::es_create_entity_relationship('ms_talent','ms_file',Input::get('talent_id'),$new_image->file_id);

                list($width,$height) = getimagesize(public_path().$this->upload_file_path.'/talents/'.date('Y/m/').$filename.'.'.$ext);

                $result['width'] = $width;
                $result['height'] = $height;

                $result['image_id'] = $new_image->file_id;
                $result['url'] = URL::asset($new_image->file_path).'/'.$filename;
                $result['ext'] = $ext;
                $result['name'] =  $file_title;
                $result['date'] = $recorded_at;
                $result['status'] = $this->image_status[Input::get('img-status')]['title'];
                $result['class'] = $this->image_status[Input::get('img-status')]['class'];
                $result['talent_id']    =   Input::get('talent_id');

                echo json_encode($result);

                //update images status to talent
                ms_talent::update_talent_images_status(Input::get('talent_id'));
            }
            else{
                echo -1; //file type limited.
            }
        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
    // delete Image via Ajax call
    ==========================================================================*/
    public function delete_image()
    {
        if(Input::has('file_id') && Input::get('file_id') != '')
        {
            $image = ms_file::find(Input::get('file_id'));

            $talent_img = BaseModel::es_select('ms_file','ms_talent',array('file_id'=>Input::get('file_id')));

            //soft delete file
            $result = $image->delete();

            //remove any related web & comp meta
            DB::table('fs_file_meta')->where('file_id',Input::get('file_id'))->delete();

            //update images status to talent
            if(!empty($talent_img))
            {
                ms_talent::update_talent_images_status($talent_img[0]->talent_id);
            }

            echo $result;
        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
   // update profile image via Ajax call
   ==========================================================================*/
    public function update_talent_profile_image()
    {
        if(Input::has('talent_id') && Input::has('image_id'))
        {
            //add to profile image.
            $talent = ms_talent::find(Input::get('talent_id'));
            $orig_image = ms_file::find(Input::get('image_id'));

            if(count($orig_image)>0)
            {
                $img = Image::make(public_path().$orig_image->file_path.$orig_image->file_name.'.'.$orig_image->file_ext);

                $filename = strtolower($talent->first_name)[0].strtolower($talent->last_name);
                $filename = preg_replace('/\s+/','',$filename);
                $filename = preg_replace('/[^a-z]+/i', '-', $filename).'_'.Input::get('talent_id').'_'.strtotime(date('Y-m-d H:i:s'));

                if(Input::get('r') != 0)
                {
                    $w = (int) round(Input::get('w')*Input::get('r'));
                    $h = (int) round(Input::get('h')*Input::get('r'));
                    $x = (int) round(Input::get('x')*Input::get('r'));
                    $y = (int) round(Input::get('y')*Input::get('r'));
                }
                else
                {
                    $w = (int) round(Input::get('w'));
                    $h = (int) round(Input::get('h'));
                    $x = (int) round(Input::get('x'));
                    $y = (int) round(Input::get('y'));

                }

                $img->crop($w,$h,$x,$y)->save(public_path().$orig_image->file_path.$filename.'.'.$orig_image->file_ext);

                foreach($this->profile_image_type as $key=>$var)
                {

                    $img = Image::make(public_path().$orig_image->file_path.$filename.'.'.$orig_image->file_ext)->resize($var[0],$var[1]);

                    $img->save(public_path().$orig_image->file_path.$filename.'_'.$key.'.'.$orig_image->file_ext,$var[2]);
                }

                $new_image = new ms_file;
                $new_image->file_path = $orig_image->file_path;
                $new_image->file_ext = $orig_image->file_ext;
                $new_image->file_name = $filename;
                $new_image->file_title  = $filename;
                $new_image->file_type = 'profile';
                $new_image->status = '1';
                $new_image->recorded_at = date('Y-m-d');

                if( $new_image->save())
                {
                    $talent->primary_image_id =  $new_image->file_id;
                    $talent->save();

                    echo json_encode(array('url'=>URL::asset($new_image->file_path.$new_image->file_name.'_thumbnail.'.$orig_image->file_ext),'url2'=>URL::asset($new_image->file_path.$new_image->file_name.'_admin.'.$orig_image->file_ext)));
                }
                else
                {
                    echo 0;
                }
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
    // update profile image via Ajax call
    ==========================================================================*/
    public function update_talent_website_image()
    {
        if(Input::has('talent_id') && Input::has('file_id_arr'))
        {
            $is_update = true;
            //add to profile image.
            $file_id_arr = json_decode(Input::get('file_id_arr'));
            $index = 1;
            foreach($file_id_arr as $file_id)
            {
                $file = ms_file::find($file_id);

                $meta = fs_file_meta::where('file_id',$file_id)->where('meta_key','web_image')->first();
                if(!$meta) $meta = new fs_file_meta;

                $meta->file_id = $file_id;
                $meta->meta_key = 'web_image';
                //$image = array('url'=>URL::asset($file->file_path).'/'.$file->file_name.'_large.'.$file->file_ext)
                $meta->meta_data = $file->file_path.$file->file_name.'_medium.'.$file->file_ext;
                $meta->list_order = $index;
                $index++;

                $result = $meta->save();

                if(!$result) $is_update = false;
            }

            echo $is_update;
        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
    // delete profile image via Ajax call
    ==========================================================================*/
    public function delete_talent_website_image()
    {
        if(Input::has('file_id'))
        {
            $meta = fs_file_meta::where('file_id',Input::get('file_id'))->where('meta_key','web_image')->delete();
            if($meta)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }


    public function update_talent_digi_image()
    {
        if(Input::has('talent_id') && Input::has('file_id_arr'))
        {
            $is_update = true;
            //add to profile image.
            $file_id_arr = json_decode(Input::get('file_id_arr'));
            $index = 1;
            foreach($file_id_arr as $file_id)
            {
                $file = ms_file::find($file_id);

                $meta = fs_file_meta::where('file_id',$file_id)->where('meta_key','digi_image')->first();
                if(!$meta) $meta = new fs_file_meta;

                $meta->file_id = $file_id;
                $meta->meta_key = 'digi_image';
                //$image = array('url'=>URL::asset($file->file_path).'/'.$file->file_name.'_large.'.$file->file_ext)
                $meta->meta_data = $file->file_path.$file->file_name.'_medium.'.$file->file_ext;
                $meta->list_order = $index;
                $index++;

                $result = $meta->save();

                if(!$result) $is_update = false;
            }

            echo $is_update;
        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
    // delete profile image via Ajax call
    ==========================================================================*/
    public function delete_talent_digi_image()
    {
        if(Input::has('file_id'))
        {
            $meta = fs_file_meta::where('file_id',Input::get('file_id'))->where('meta_key','digi_image')->delete();
            if($meta)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }

    /* =========================================================================
   // update profile image via Ajax call
   ==========================================================================*/
    public function update_talent_compcard_image()
    {
        set_time_limit(0);

        if(Input::has('talent_id') && Input::has('image_id') && Input::has('index'))
        {
            $talent = ms_talent::find(Input::get('talent_id'));
            $compcard_image_size = $this->compcard_image[Input::get('index')];
            if(count($talent) >0)
            {
                $compcard_images = BaseModel::es_select_entity_related_files('ms_talent',$talent->talent_id,null,'compcard');

                if(count($compcard_images)>0)
                {
                    foreach($compcard_images as $image)
                    {
                        if(Input::get('index') == $image->list_order)
                        {
                            //if record exists, then delete
                            fs_file_meta::where('file_id',$image->file_id)->where('meta_key','compcard')->delete();
                        }
                    }
                }


                $file = ms_file::find(Input::get('image_id'));

                $filename = strtolower($talent->first_name)[0].strtolower($talent->last_name);
                $filename = preg_replace('/\s+/','',$filename);
                $filename = preg_replace('/[^a-z]+/i', '-', $filename).'_'.Input::get('talent_id').'_'.Input::get('index').'_compcard';


                $r = Input::get('r');

                if($r != 0)
                {
                    $w = (int) round(Input::get('w')*$r);
                    $h = (int) round(Input::get('h')*$r);
                    $x = (int) round(Input::get('x')*$r);
                    $y = (int) round(Input::get('y')*$r);
                }
                else
                {
                    $w = (int) Input::get('w');
                    $h = (int) Input::get('h');
                    $x = (int) Input::get('x');
                    $y = (int) Input::get('y');

                }


                $img = Image::make(public_path().$file->file_path.$file->file_name.'.'.$file->file_ext);
                /*$img->crop($w,$h,$x,$y)->resize((int) 2*round(Input::get('w')), (int) 2*round(Input::get('h')),function ($constraint) {
                                    $constraint->aspectRatio();
                                 })->save(public_path().$file->file_path.$filename.'.jpg',70);*/
                $img->crop($w,$h,$x,$y)->resize($compcard_image_size[0],$compcard_image_size[1],function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path().$file->file_path.$filename.'.jpg',70);

                $meta = new fs_file_meta;
                $meta->file_id = $file->file_id;
                $meta->meta_key = 'compcard';
                $meta->meta_data = $file->file_path.$filename.'.jpg';
                $meta->list_order = Input::get('index');

                $meta->save();

                echo json_encode(array('index'=>Input::get('index'),'url'=>asset($meta->meta_data)));

            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }

    /*============================================================
    // DELETE COMP CARD IMAGE
    ============================================================*/
    public function delete_talent_compcard_image()
    {
        $result =  array();
        try
        {
            $talent = ms_talent::find(Input::get('talent_id'));
            if(count($talent) == 0)
            {
                throw new exception('No talent record can be found.');
            }

            $compcard_images = BaseModel::es_select_entity_related_files('ms_talent',$talent->talent_id,null,'compcard');

            if(count($compcard_images) == 0)
            {
                throw new exception('No compcard image can be found.');
            }

            foreach($compcard_images as $image)
            {
                if(Input::get('index') == $image->list_order)
                {
                    //if record exists, then delete
                    fs_file_meta::where('file_id',$image->file_id)->where('meta_key','compcard')->delete();
                    $result['message'] = 'Compcard image has been deleted successfully.';
                    $result['error'] = 0;
                }
            }


        }
        catch(Exception $e)
        {
            $result['message'] = $e->getMessage();
            $result['error'] = 1;
        }

        echo json_encode($result);
    }

    /* =========================================================================
   // update talent image via Ajax call
   ==========================================================================*/
    public function update_talent_image()
    {
        if( Input::has('talent_id') && Input::has('image_id') )
        {
            $talent = ms_talent::find(Input::get('talent_id'));
            $orig_image = ms_file::find(Input::get('image_id'));

            $image_file = $orig_image->file_name.'.'.$orig_image->file_ext;
            $ext = $orig_image->file_ext;

            $img = Image::make(public_path().$orig_image->file_path.$image_file);

            if( Input::get('x') != 0 && Input::get('y') != 0 && Input::get('w') != 0 && Input::get('h') != 0)
            {
                if(Input::get('r') != 0)
                {
                    $w = (int) round(Input::get('w')*Input::get('r'));
                    $h = (int) round(Input::get('h')*Input::get('r'));
                    $x = (int) round(Input::get('x')*Input::get('r'));
                    $y = (int) round(Input::get('y')*Input::Get('r'));
                }
                else
                {
                    $w = (int) Input::get('w');
                    $h = (int) Input::get('h');
                    $x = (int) Input::get('x');
                    $y = (int) Input::get('y');

                }

                if(Input::get('type') == 1)
                {
                    //create new image
                    $filename = strtolower($talent->first_name)[0].strtolower($talent->last_name);
                    $filename = preg_replace('/\s+/','',$filename);
                    $filename = preg_replace('/[^a-z]+/i', '-', $filename).'_'.Input::get('talent_id').'_'.strtotime(date('Y-m-d H:i:s'));

                    $img->crop($w,$h,$x,$y);
                    if( Input::has('rotate') && Input::get('rotate') != 0)
                    {
                        $img->rotate(floatval(Input::get('rotate')));
                    }

                    $img->save(public_path().$orig_image->file_path.$filename.'.'.$orig_image->file_ext);
                    //register this image and create all variations.
                    $recorded_at = (Input::has('edit-img-date') ? Input::get('edit-img-date'): date('m/d/Y'));
                    $date_str = DateTime::createFromFormat('d/m/Y', $recorded_at);

                    $new_image = new ms_file;
                    $new_image->file_name = $filename;
                    $new_image->file_ext = $ext;
                    $new_image->file_title =  (Input::has('edit-img-title')) ? Input::get('edit-img-title') : $filename;
                    $new_image->recorded_at = $date_str->format('Y-m-d');
                    $new_image->file_path = $orig_image->file_path;
                    $new_image->status = Input::get('img-status');
                    $new_image->file_type = 'photo';

                    $new_image->save();

                    //create all alt images
                    if(!check_file_exist(public_path().$orig_image->file_path.$filename.'.'.$orig_image->file_ext))
                    {
                        echo 0;
                        exit;
                    }

                    // Queue::push('ProcessImage@generate_images_from_upload',array('image_name'=>$filename,'ext'=>$orig_image->file_ext,'path'=>public_path(). $this->upload_file_path.'/talents/'.date('Y/m/')));

                    generate_images_from_upload($this->image_type, public_path().$orig_image->file_path, $filename, $orig_image->file_ext);

                    //create realationship between talent and file
                    BaseModel::es_create_entity_relationship('ms_talent','ms_file',Input::get('talent_id'),$new_image->file_id);

                    $result['image_id'] = $new_image->file_id;
                    $result['url'] = URL::asset($orig_image->file_path).'/'.$filename;
                    $result['ext'] = $ext;
                    $result['name'] =  $new_image->file_title;
                    $result['date'] = $recorded_at;
                    $result['status'] = $this->image_status[Input::get('img-status')]['title'];
                    $result['class'] = $this->image_status[Input::get('img-status')]['class'];
                    $result['talent_id'] = Input::get('talent_id');
                    $result['width']    =  $w;
                    $result['height']   = $h;

                    echo json_encode($result);

                }
                else if( Input::get('type') == 0)
                {
                    //create new image
                    $filename = $orig_image->file_name;

                    $img->crop($w,$h,$x,$y);
                    if( Input::has('rotate') && Input::get('rotate') != 0)
                    {
                        $img->rotate(floatval(Input::get('rotate')));
                    }

                    $img->save();

                    //register this image and create all variations.
                    $recorded_at = (Input::has('edit-img-date') ? Input::get('edit-img-date') : date('m/d/Y'));

                    $orig_image->file_title =  (Input::has('edit-img-title')) ? Input::get('edit-img-title') : $filename;
                    $date_str = DateTime::createFromFormat('d/m/Y', $recorded_at);
                    $orig_image->recorded_at =   $date_str->format('Y-m-d');

                    $orig_image->status = Input::get('img-status');

                    $orig_image->save();

                    if(!check_file_exist(public_path().$orig_image->file_path.$filename.'.'.$orig_image->file_ext))
                    {
                        echo 0;
                        exit;
                    }
                    // echo public_path().$orig_image->file_path.$filename.'.'.$orig_image->file_ext;
                    //Queue::push('ProcessImage@generate_images_from_upload',array('image_name'=>$filename,'ext'=>$orig_image->file_ext,'path'=>public_path().$orig_image->file_path));
                    generate_images_from_upload($this->image_type, public_path().$orig_image->file_path, $filename, $orig_image->file_ext);

                    $result['image_id'] = $orig_image->file_id;
                    $result['url'] = URL::asset($orig_image->file_path).'/'.$filename;
                    $result['ext'] = $ext;
                    $result['name'] =  $orig_image->file_title;
                    $result['date'] = $recorded_at;
                    $result['status'] = $this->image_status[Input::get('img-status')]['title'];
                    $result['class'] = $this->image_status[Input::get('img-status')]['class'];
                    $result['talent_id'] = Input::get('talent_id');
                    $result['width']    =  $w;
                    $result['height']   = $h;

                    echo json_encode($result);

                }
            }
            else
            {
                //no image changes . just update file info
                $filename = $orig_image->file_name;

                $recorded_at = (Input::has('edit-img-date') ? Input::get('edit-img-date') : date('m/d/Y'));
                $date_str = DateTime::createFromFormat('d/m/Y', $recorded_at);
                $orig_image->file_title =  (Input::has('edit-img-title')) ? Input::get('edit-img-title') : $filename;

                $orig_image->recorded_at = $date_str->format('Y-m-d');
                $orig_image->status = Input::get('img-status');

                $orig_image->save();

                $result['image_id'] = $orig_image->file_id;
                $result['url'] = URL::asset($orig_image->file_path).'/'.$filename;
                $result['ext'] = $ext;
                $result['name'] =  $orig_image->file_title;
                $result['date'] = $recorded_at;
                $result['status'] = $this->image_status[Input::get('img-status')]['title'];
                $result['class'] = $this->image_status[Input::get('img-status')]['class'];
                $result['talent_id'] = Input::get('talent_id');

                echo json_encode($result);
            }

            //update images status to talent
            ms_talent::update_talent_images_status($talent->talent_id);
        }
        else
        {
            echo 0;
        }
    }

    /*==========================================================================
    // comp card pdf output
    ===========================================================================*/
    public function comp_card_output($talent_slug=null)
    {
        if($talent_slug != null)
        {
            $talent = ms_talent::where('talent_slug',$talent_slug)->first();
        }
        else
        {
            $talent = ms_talent::find(Input::get('comp_card_talent_id'));
        }

        if(count($talent) > 0 && $talent->talent_status != 3)
        {
            $eav_data = es_attribute_data::get_attributes_data($talent->talent_id,'ms_talent');

            $images = BaseModel::es_select_entity_related_files('ms_talent',$talent->talent_id,null,'compcard');

            chdir(dirname(__DIR__));
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "FOOLSCAP", true, 'UTF-8', false);

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // get Museo Font
            $fontname = $pdf->addTTFfont( public_path() . '/fonts/compcard/MuseoSans-100.ttf', 'TrueTypeUnicode', '', 32);

            // set margins
            $pdf->SetMargins(0, 0, 0);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, 0);

            // set image scale factor
            $pdf->setImageScale(2);

            // set font
            $pdf->SetFont( $fontname, '', 10,'',false);

            $pdf->AddPage();
            // $pdf->SetFont('Arial','B',16);

            $html = View::make('winkmum.pages.talentcompcard',array('talent'=>$talent,'eav_data'=>$eav_data,'images'=>$images));
            /* return $html;
             die();*/

            $pdf->writeHTML($html, true, false, true, false, '');
            // reset pointer to the last page
            $pdf->lastPage();
            //Close and output PDF document
            $output = 'FD';
            if(Input::has('preview'))
            {
                $output = 'I';
            }

            $pdf->Output(public_path('uploads/pdf/'.strtolower($talent->first_name.'_'.$talent->last_name).'_compcard.pdf'), $output);
        }
        else
        {
            echo 0;
        }

    }

    /*==========================================================================
    // ajax load talent calendar data
    ===========================================================================*/
    public function get_talent_calendar_data($id)
    {
        $start = date('Y-m-d',Input::get('start'));
        $end = date('Y-m-d', Input::get('end'));

        $availability = fs_talent_availability::get_talent_availability_list($id,$start,$end);

        $events = array();

        if(!empty($availability))
        {
            foreach($availability as $data)
            {
                $allday = false;
                $title = '';

                //rule for all day
                if( $data['start_time'] == '0000' && $data['end_time'] == '0000')
                {
                    $allday = true;
                    $title = 'All day';
                }

                if($data['availability_type'] == 1)
                {
                    $events[] = array(
                        "id" => 'talent_'.$id.'_'.$data['availability_id'],
                        "title" => $title,
                        "start" => $data['date'] . ' '. date('H:i:s', strtotime(convert_time_format($data['start_time']))),
                        "end" =>  $data['date'] . ' '. date('H:i:s', strtotime(convert_time_format($data['end_time']))),
                        "className" => "talent label-warning talent-break calendar-modal",
                        "allDay" => $allday, // all day or not,
                        "url" => ''
                    );
                }
                elseif($data['availability_type'] == 0)
                {
                    $events[] = array(
                        "id" => 'talent_'.$id.'_'.$data['availability_id'],
                        "title" => $title,
                        "start" => $data['date'] . ' '. date('H:i:s', strtotime(convert_time_format($data['start_time']))),
                        "end" =>  $data['date'] . ' '. date('H:i:s', strtotime(convert_time_format($data['end_time']))),
                        "className" => "talent label-red talent-unavailable calendar-modal",
                        "allDay" => $allday, // all day or not,
                        "url" => ''
                    );
                }
            }
        }


        $jobs = DB::table('ms_job')
            ->leftJoin('fs_roster_row','fs_roster_row.job_id', '=', 'ms_job.job_id')
            ->leftJoin('fs_roster_shift','fs_roster_shift.roster_row_id','=','fs_roster_row.roster_row_id')
            ->leftJoin('fs_shift','fs_shift.shift_id','=','fs_roster_shift.shift_id')
            ->leftJoin('fs_location','fs_location.location_id','=','fs_shift.location_id')
            ->where('fs_shift.date','>=',$start)
            ->where('fs_shift.date','<=',$end)
            ->where('fs_roster_row.talent_id', '=', $id)
            ->whereNull('fs_roster_shift.deleted_at')
            ->get(array('ms_job.job_id','ms_job.job_title','ms_job.job_status', 'ms_job.job_option','ms_job.completed_roster','fs_location.location_title','fs_shift.date','fs_roster_shift.shift_start_time','fs_roster_shift.shift_end_time'));

        if(count($jobs)>0)
        {
            foreach($jobs as $key=>$job)
            {
                $result['id'] = $job->job_id;
                $result['title'] = ' '.$job->location_title.': '.$job->job_title;
                $result['start'] = $job->date.' '. convert_time_format($job->shift_start_time);
                $result['end'] = $job->date.' '.convert_time_format($job->shift_end_time);
                $result['allDay'] = false;
                $result['url'] = url('winkmum/job/profile/edit/'.$job->job_id);
                $job_status = '';
                switch($job->job_status)
                {
                    case 'active':
                    case 'cast':
                    case 'rollover':
                        $job_status = 'label-green';
                        break;
                    case 'draft':
                        $job_status = 'label-purple';
                        break;
                    case 'quote':
                        $job_status = 'label-orange';
                        break;
                    case 'hold':
                        $job_status = 'label-red';
                        break;
                    case 'closed':
                        $job_status = 'label-grey';
                        break;
                    default:
                        $job_status = 'label-default';
                        break;
                }

                $result['className'] = 'job '. $job_status. ( $job->job_option == 0 ? ' type-yes' : ' type-no'). ( $job->completed_roster == 0 ? ' roster-no' : ' roster-yes');

                $events[] = $result;
            }
        }


        //start job events here

        echo json_encode($events);
    }

    /*==========================================================================
    // ajax update availability time
    ===========================================================================*/
    public function update_talent_availability()
    {
        $result = array();

        try
        {
            if( Input::has('availability_id') )
            {
                $availability = fs_talent_availability::find(Input::get('availability_id'));
            }
            else{
                $availability = new fs_talent_availability;

                if(!Input::has('talent_id') || (Input::has('talent_id') && Input::get('talent_id') == '') )
                {
                    throw new exception('Talent id is not existed.');
                }

                $availability->talent_id = Input::get('talent_id');
            }

            if( Input::get('recur') != 0 && (!Input::has('date_start') || !Input::has('date_end')) )
            {
                throw new exception('Recurring Mode, Time range is not selected.');
            }

            if(Input::has('start'))
            {
                $availability->start_time = roster_time_format(Input::get('start'));
            }
            else{
                $availability->start_time = '0000';
            }

            if(Input::has('end'))
            {
                $availability->end_time = roster_time_format(Input::get('end'));
            }
            else{
                $availability->end_time = '0000';
            }

            if(Input::has('type'))
            {
                $availability->availability_type = Input::get('type');
            }

            if( Input::has('date'))
            {
                $date_str = DateTime::createFromFormat('d/m/Y', Input::get('date'));
                $availability->date = $date_str->format('Y-m-d');
            }

            $availability->save();

            //start recurring here
            if(Input::has('recur') && Input::get('recur') != 0 )
            {

                $start_date_str = DateTime::createFromFormat('d/m/Y', Input::get('date_start'));
                $start_date = $start_date_str->format('Y-m-d');

                $end_date_str = DateTime::createFromFormat('d/m/Y', Input::get('date_end'));
                $end_date = $end_date_str->format('Y-m-d');

                $lastday = strtotime($end_date);
                $day = strtotime($availability->date);

                switch(Input::get('recur'))
                {
                    case 1:
                        //everyday.
                        while($day < $lastday)
                        {
                            $day += 86400;
                            //echo date('Y-m-d', $day);

                            $record = new fs_talent_availability;
                            $record->talent_id = $availability->talent_id;
                            $record->start_time = $availability->start_time;
                            $record->end_time = $availability->end_time;
                            $record->date =  date('Y-m-d', $day);
                            $record->availability_type = $availability->availability_type;

                            $record->save();
                        }
                        break;
                    case 2:
                        //same day each week.
                        while($day < $lastday)
                        {
                            $day += 7 * 86400;
                            //echo date('Y-m-d', $day);

                            $record = new fs_talent_availability;
                            $record->talent_id = $availability->talent_id;
                            $record->start_time = $availability->start_time;
                            $record->end_time = $availability->end_time;
                            $record->date =  date('Y-m-d', $day);
                            $record->availability_type = $availability->availability_type;

                            $record->save();
                        }
                        break;
                    case 3:
                        //same day each fortnight.
                        while($day < $lastday)
                        {
                            $day += 14 * 86400;
                            //echo date('Y-m-d', $day);

                            $record = new fs_talent_availability;
                            $record->talent_id = $availability->talent_id;
                            $record->start_time = $availability->start_time;
                            $record->end_time = $availability->end_time;
                            $record->date =  date('Y-m-d', $day);
                            $record->availability_type = $availability->availability_type;

                            $record->save();
                        }
                        break;
                    case 4:
                        //same day each month.
                        while($day < $lastday)
                        {
                            $day = strtotime(date('Y-m-d',$day)."+1 month");
                            //echo date('Y-m-d', $day);

                            $record = new fs_talent_availability;
                            $record->talent_id = $availability->talent_id;
                            $record->start_time = $availability->start_time;
                            $record->end_time = $availability->end_time;
                            $record->date =  date('Y-m-d', $day);
                            $record->availability_type = $availability->availability_type;

                            $record->save();
                        }
                        break;
                }
            }
            $result['message'] = 'Talent availability record(s) have been added successfully.';
            $result['error'] = 0;
        }
        catch(Exception $e)
        {
            $result['message'] = $e->getMessage();
            $result['error'] = 1;
        }

        echo json_encode($result);

    }

    /*==========================================================================
    // ajax update availability time
    ===========================================================================*/
    public function delete_talent_availability()
    {
        $result = array();

        try{
            if( !Input::has('availability_id'))
            {
                throw new exception('Input is not valid.');
            }

            $availability = fs_talent_availability::find(Input::get('availability_id'));
            if( count($availability) == 0)
            {
                throw new exception('Record can not be found.');
            }

            $availability->delete();

            $result['message'] = 'Availability record has been deleted successfully.';
            $result['error'] = 0;
        }
        catch(Exception $e)
        {
            $result['message'] = $e->getMessage();
            $result['error'] = 1;
        }

        echo json_encode($result);
    }

    public function delete_talent_all_images()
    {
        if(Input::has('talent_id'))
        {
            $files = BaseModel::es_select('ms_talent','ms_file',array('talent_id'=>Input::get('talent_id')),array(),2);

            foreach($files as $file)
            {

                //remove relationship
                BaseModel::es_remove_entity_relationship('ms_talent','ms_file',Input::get('talent_id'),$file->file_id);

                //delete image
                $img = ms_file::find($file->file_id);


                fs_file_meta::where('file_id',$file->file_id)->forceDelete();
                $img->forceDelete();
            }

            $talent = ms_talent::find(Input::get('talent_id'));
            $talent->forceDelete();
        }
    }

    public function import_talent()
    {
        set_time_limit(0);

        if(Input::has('id'))
        {
            $talents = DB::select("SELECT * FROM talent_info where id = '".Input::get('id')."'");
            foreach($talents as $talent)
            {

                $new_talent = new ms_talent;


                $new_talent->induction_complete = 0;
                $new_talent->talent_status = 1;
                switch($talent->category)
                {
                    case 'Sports':
                        $new_talent->talent_category = 2;
                        break;
                    case 'Promotions':
                        $new_talent->talent_category = 4;
                        break;
                    case 'Classics':
                        $new_talent->talent_category = 3;
                        break;
                    default:
                        $new_talent->talent_category = 1;
                        break;
                }

                // $new_talent->password = Hash::make($);
                if($talent->gender != 'female' )
                {
                    $new_talent->talent_title = 'Mr';
                    $new_talent->gender = 1;
                }
                else
                {
                    $new_talent->talent_title = 'Miss';
                    $new_talent->gender = 2;
                }

                if($talent->dob != '')
                {
                    $new_talent->dob = date('Y-m-d',strtotime($talent->dob));
                }

                $new_talent->state = strtoupper($talent->state);

                $new_talent->first_name = $talent->first_name;
                $new_talent->last_name = $talent->last_name;
                $new_talent->talent_slug = ms_talent::check_talent_slug(strtolower($new_talent->first_name.'-'.$new_talent->last_name));

                $new_talent->rating = 5;

                $new_talent->country = 'Australia';

                $new_talent->talent_start_date = date('Y-m-d',strtotime('2014-05-27'));

                $new_talent->email = $talent->email;

                $new_talent->mobile = $talent->mobile;

                $new_talent->tfn = $talent->tfn;

                $new_talent->save();

                DB::table('talent_info')->where('id',$talent->id)->update(array('talent_id'=>$new_talent->talent_id ));

                //add eav fields
                $eav_fields = $new_talent->get_eav_table_fields();
                $eav= array('height','eye_colour','hair_colour','shoe','dress','chest_bust','waist','nationality','ethnicity','bra','t_shirt','hips','suit_size','pant_size');
                foreach($eav as $key)
                {
                    if(isset($talent->{$key}) && $talent->{$key} != '' && $talent->{$key} != NULL)
                    {
                        $eav_model = new es_attribute_data;

                        $eav_model->update_attribute_data($new_talent->talent_id,'ms_talent',$eav_fields['data'][$key]->attribute_id, strtolower($talent->{$key}));
                    }
                }

                //start all image
                $file_path = public_path().$this->upload_file_path.'/talents/'.date('Y/m/');
                $data_path =  public_path().$this->upload_file_path.'/data/';

                $filename = strtolower($new_talent->first_name)[0].strtolower($new_talent->last_name).'_'.$new_talent->talent_id.'_'.strtotime(date('Y-m-d H:i:s'));
                $filename = preg_replace('/\s+/','',$filename);
                $filename = preg_replace('/[^a-z]+/i', '-', $filename);

                $ext = explode('.',$talent->default_image);
                $ext = strtolower($ext[count($ext)-1]);
                //profile image
                if(file_exists($data_path.$talent->default_image))
                {
                    copy($data_path.$talent->default_image, $file_path.$filename.'.'.$ext);
                    foreach($this->profile_image_type as $key=>$var)
                    {
                        $img = Image::make($data_path.$talent->default_image);

                        if($img->width() > $img->height())
                        {
                            $img->resize(null,$var[1],function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        else
                        {
                            $img->resize($var[0],null,function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }


                        $xpos = (int) floor(($img->width() / 2) - ($var[0]/2));
                        $ypos = (int) floor(($img->height() / 2) - ($var[1]/2));

                        $img->crop($var[0],$var[1],$xpos,$ypos);

                        $img->save($file_path.$filename.'_'.$key.'.'.$ext,$var[2]);

                    }
                }


                $new_image = new ms_file;
                $new_image->file_path = $this->upload_file_path.'/talents/'.date('Y/m/');
                $new_image->file_ext = $ext;
                $new_image->file_name = $filename;
                $new_image->file_title  = $filename;
                $new_image->file_type = 'profile';
                $new_image->status = '1';
                $new_image->recorded_at = date('Y-m-d');

                $new_image->save();

                $new_talent->primary_image_id = $new_image->file_id;
                $new_talent->save();

                $images = explode('|',$talent->image);
                $index = 1;
                foreach($images as $image)
                {

                    $ext = explode('.',$image);
                    $ext = strtolower($ext[count($ext)-1]);

                    $filename = strtolower($talent->first_name)[0].strtolower($talent->last_name).'_'.$new_talent->talent_id.'_'.(strtotime(date('Y-m-d H:i:s'))+$index);
                    $filename = preg_replace('/\s+/','',$filename);
                    $filename = preg_replace('/[^a-z]+/i', '-', $filename);

                    if(file_exists($data_path.$image))
                    {
                        copy($data_path.$image, $file_path.$filename.'.'.$ext);

                        $new_image = new ms_file;
                        $new_image->file_name = $filename;
                        $new_image->file_ext = $ext;
                        $new_image->file_title =  $filename;
                        $new_image->recorded_at = date('Y-m-d');
                        $new_image->file_path = $this->upload_file_path.'/talents/'.date('Y/m/');
                        $new_image->file_type = 'photo';
                        $new_image->status = 1;

                        $new_image->save();

                        //create all alt images
                        foreach($this->image_type as $key=>$var)
                        {
                            if($key != 'small' && $key != 'thumbnail')
                            {
                                if($var[0] != 0 && $var[1] != 0)
                                {
                                    $img = Image::make($data_path.$image)->resize($var[0],$var[1],function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }
                                elseif($var[0] == 0  && $var[1] != 0 )
                                {
                                    $img = Image::make($data_path.$image)->resize(null,$var[1],function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }
                                elseif($var[0] != 0  && $var[1] == 0 )
                                {
                                    $img = Image::make($data_path.$image)->resize($var[0],null,function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }
                                else
                                {
                                    $img = Image::make($data_path.$image);
                                }
                            }
                            else
                            {
                                $img = Image::make($data_path.$image);

                                if($img->width() > $img->height())
                                {
                                    $img->resize(null,$var[1],function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }
                                else
                                {
                                    $img->resize($var[0],null,function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }

                                $xpos = (int) floor(($img->width() / 2) - ($var[0]/2));
                                $ypos = (int) floor(($img->height() / 2) - ($var[1]/2));

                                $img->crop($var[0],$var[1],$xpos,$ypos);
                            }

                            $img->save($file_path.$filename.'_'.$key.'.'.$ext,$var[2]);

                        }



                        //create realationship between talent and file
                        BaseModel::es_create_entity_relationship('ms_talent','ms_file',$new_talent->talent_id,$new_image->file_id);

                        if($image != $talent->default_image)
                        {
                            $meta = new fs_file_meta;
                            $meta->file_id = $new_image->file_id;
                            $meta->meta_key = 'web_image';
                            $meta->meta_data =$this->upload_file_path.'/talents/'.date('Y/m/').$filename.'_medium.'.$ext;
                            $meta->list_order =$index;
                            $meta->save();

                            echo $new_talent->talent_id . ' -> '. $new_talent->first_name . ' '. $new_talent->last_name.' image: '.$filename.'_'.$key.'.'.$ext.' inserted.<br/>';
                        }
                        else
                        {
                            //create realationship between talent and file
                            BaseModel::es_create_entity_relationship('ms_talent','ms_file',$new_talent->talent_id,$new_image->file_id);
                            $new_talent->save();

                            echo $new_talent->talent_id . ' -> '. $new_talent->first_name . ' '. $new_talent->last_name.' inserted.<br/>';

                        }

                        $index++;
                    }
                    else
                    {
                        echo '<p style="color:red">'.$talent->first_name.' '.$talent->last_name . ' ===> '. $image . ' can not be found.</p>';
                    }

                }
            }
        }
    }

    public function batch_talent_insert()
    {
        //this is the ajax controller.
        //you don't need view. echo any html tag to display it on screen.
        //data table: talent_info. all talents information from wordpress
        //ex. $result = DB::select(Query); it returns object: $result[0]->id
        //images target folder: public/uploads/talents/2014/04/
        //images source profile image:　public/uploads/talents/img  all other images: public /uploads/talents/set

        //js file is public/js/tool.js
        //access page : http://wink.dbhost.com.au/public/index.php/winkmum/talent/batch-import
        //function:  public function import_talent()
        //ajax call: http://wink.dbhost.com.au/public/index.php/winkmum/import-talent?id=xxxx
        //function:  public function delete_talent_all_images()
        //if any error. you can delete the record by call : http://wink.dbhost.com.au/public/index.php/winkmum/talent/edit/delete-all-images?talent_id=xxxx
        //it will delete this talent record and any related image file and relationship mapping data. this is not soft delete.
        $data_set = DB::select("SELECT id FROM talent_info where id > 571");
        $data = array();
        foreach($data_set as $var)
        {
            $data[] = $var->id;
        }

        $content = '<script type="text/javascript">';
        $content .= 'var obj = ['.implode(',',$data).']; ';
        $content .= '</script>';

        $content .='<h4>Talent Batch Import</h4>';
        $content .='<button class="btn" id="start">Start</button>';
        $content .='<hr/><div class="result"></div>';
        // Compose template data
        $data = array(
            'body_class' => "dashboard",
            'content' => $content
        );

        // Render final template
        return View::make('winkmum.layout.tool', $data);
    }

    public function batch_admin_image()
    {

        $data_set = ms_file::where('file_type','photo')->where('file_id','>',2000)->where('file_id','<>',4920)->where('file_id','<>',5341)->where('file_id','<>',5603)->where('file_id','<>',5873)->get();
        $data = array();
        foreach($data_set as $var)
        {
            $data[] = $var->file_id;
        }

        $content = '<script type="text/javascript">';
        $content .= 'var obj = ['.implode(',',$data).']; ';
        $content .= '</script>';

        $content .='<h4>Talent Batch Admin Convert</h4>';
        $content .='<button class="btn" id="start">Start</button>';
        $content .='<hr/><div class="result"></div>';
        // Compose template data
        $data = array(
            'body_class' => "dashboard",
            'content' => $content
        );

        // Render final template
        return View::make('winkmum.layout.tool', $data);
    }

    public function convert_admin_img()
    {
        set_time_limit(0);

        /*$p = Input::get('p') * 40;

        $images = DB::select('SELECT * FROM ms_file WHERE file_type = "profile" LIMIT '.$p.',40 ');

        foreach($images as $image)
        {
            foreach($this->profile_image_type as $key=>$var)
    		{
    					 $img = Image::make(public_path().$image->file_path.$image->file_title.'.'.$image->file_ext);

    					 if($img->width() > $img->height())
                         {
                              $img->resize(null,$var[1],function ($constraint) {
											$constraint->aspectRatio();
										 });
                         }
                         else
                         {
                              $img->resize($var[0],null,function ($constraint) {
											$constraint->aspectRatio();
										 });
                         }


    					$xpos = (int) floor(($img->width() / 2) - ($var[0]/2));
                        $ypos = (int) floor(($img->height() / 2) - ($var[1]/2));

                        $img->crop($var[0],$var[1],$xpos,$ypos);

    					$img->save(public_path().$image->file_path.$image->file_title.'_'.$key.'.'.$image->file_ext,$var[2]);

    		}
        }*/
        $talents = DB::select('SELECT talent_id,dob FROM talent_dob');
        foreach($talents as $talent)
        {
            $old_talent = ms_talent::find($talent->talent_id);
            if(count($old_talent) > 0)
            {
                if($talent->dob != '')
                {
                    $str = DateTime::createFromFormat('d/m/Y', $talent->dob);

                    print_R($str->format('Y-m-d'));
                    echo '<br/>';
                    $old_talent->dob = $str->format('Y-m-d');

                    $old_talent->save();
                }
            }
        }
    }

    public function claw_image()
    {
        set_time_limit(0);

        $start = Input::get('p') * 20;

        $images = DB::select("SELECT * FROM talent_info LIMIT $start,20");
        $url ='http://www.winkmodels.com.au/wp-content/uploads/';
        $path = public_path().'/uploads/data/';


        foreach($images as $image)
        {
            $image_set = explode('|',$image->image);
            foreach($image_set as $img)
            {
                if(!file_exists($path.$img))
                {
                    downloadFile($url.$img, $path.$img);
                }
            }
        }

        //check file exist or not
        /*	$images = DB::select("SELECT * FROM talent_info");
            $url ='http://www.winkmodels.com.au/wp-content/uploads/';
            $path = public_path().'/uploads/data/';

            $index=0;
            foreach($images as $image)
            {

                if(file_exists($path.$image->default_image))
                {
                    echo $index.' => '.$path.$image->default_image.'<br/>';
                    $index++;
                    DB::table('talent_info')->where('id',$image->id)->update(array('claw'=>1));
                }
            }*/

    }

    public function claw_image_250()
    {
        /* print_r(Session::all());
         die();*/
        set_time_limit(0);

        $images = DB::select("SELECT * FROM talent_info");
        $url ='http://www.winkmodels.com.au/wp-content/uploads/';
        $path = public_path().'/uploads/profile/';

        foreach($images as $image)
        {
            $talent = ms_talent::find($image->talent_id);
            if($talent)
            {
                $profile_img = ms_file::find($talent->primary_image_id);
                if($profile_img)
                {
                    $img_var =  explode('.',$image->default_image);
                    $ext = $img_var[count($img_var)-1];

                    $img_file = str_replace('.'.$ext,'-250x250.'.$ext,$image->default_image);

                    //Session::put('error_track',$image->talent_id);
                    if( file_exists( $path.$profile_img->file_name.'.'.$profile_img->file_ext))
                    {
                        downloadFile($url.$img_file, $path.$profile_img->file_name.'.'.$profile_img->file_ext);
                    }
                }
            }

        }
    }

    public function create_variation()
    {
        $path = public_path().'/uploads/profile/';
        $images = scandir($path);

        unset($images[0],$images[1]);

        foreach($images as $image)
        {

            $data = explode('.',$image);
            $filename = $data[0];
            $ext = $data[1];

            foreach($this->image_type as $key=>$var)
            {

                if($var[0] != 0 && $var[1] != 0)
                {
                    $img = Image::make($path.$filename.'.'.$ext)->resize($var[0],$var[1],true,true);
                }
                elseif($var[0] == 0  && $var[1] != 0 )
                {
                    $img = Image::make($path.$filename.'.'.$ext)->resize(null,$var[1],true,true);
                }
                elseif($var[0] != 0  && $var[1] == 0 )
                {
                    $img = Image::make($path.$filename.'.'.$ext)->resize($var[0],null,true,true);
                }
                else
                {
                    $img = Image::make($path.$filename.'.'.$ext);
                }


                $img->save($path.$filename.'_'.$key.'.'.$ext,$var[2]);

            }
        }
    }

}

//eof
/* End of Talent Controller Class */
?>
