@section('content')

<input type="hidden" id="modal_talent" value="{{ isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id : 0 }}" />
<!-- start: PAGE CONTENT -->
<div class="row">
	<div class="col-sm-6">
		<div class="calendar-user-image pull-left">
			@if(isset($talent_profile_model_data))
			<div class="thumbnail">
			    <span class="profile-thumb-overflow">
					  <img src="{{ asset($profile_image['file_path'].$profile_image['file_name'].'_thumbnail.'.$profile_image['file_ext'])}}" alt="">
				</span>
			</div>
			@endif
		</div>
		<div class="calendar-name">
			<h4>
			    {{ isset($talent_profile_model_data) ? ucwords($talent_profile_model_data->first_name) .' '.ucwords($talent_profile_model_data->last_name):'' }} &nbsp;&nbsp;&nbsp; @if( isset($talent_profile_model_data) && $talent_profile_model_data->talent_status == 1 )<a href="{{ $view_data->wink_site.'/model/'.$talent_profile_model_data->talent_slug }}" target="_blank" class="btn btn-xs btn-default"><i class="clip-images-2"></i> Web Page</a>	<a href="{{url('winkmum/talent/profile/comp-card-output')}}?preview=1&comp_card_talent_id={{(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_id : null }}" class="btn btn-default btn-xs btn-pdf-comp-card" target="_blank"><i class="clip-file-pdf"></i> Comp Card</a>@endif
			   
    	        <br/>
    		    <small>{{  isset($talent_profile_model_data) ?'Joined:'.date('d M Y', strtotime($talent_profile_model_data->created_at)):'' }}</small>
			</h4>
	
		</div>
	</div>
	<div class="col-sm-6">
		<p class="pull-right">
		@if(isset($talent_profile_model_data))
	    <a class="btn btn-info" href="{{ url('winkmum/talent/calendar').'/'.$talent_profile_model_data->talent_id}}"><i class="fa icon-calendar "></i> View Talent Calender</a>
	    @endif
	    </p>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
        {{ 
            Form::open(
                array(
                    'url' => 'winkmum/talent/profile/process-talent-profile', 
                    'id' => 'form-talent-general-tab'
                )
            ) 
        }}
        {{
            Form::hidden('talent_id',(isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:''))
        }}
        <input type="hidden" name="tab_active" id="tab_active" />
		<div class="tabbable">
			<ul class="nav nav-tabs tab-padding tab-space-3 tab-bricky" id="talent_profile_tab_nav">
				<li class="active">
					<a data-toggle="tab" href="#panel_general">
						General Info
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#panel_contact">
						Contact Info
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#panel_profile">
						Details
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#panel_influrences">
						Influencers
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#panel_skill">
						Skills
					</a>
				</li>
				@if(isset($talent_profile_model_data))
				<li class="">
					<a data-toggle="tab" href="#panel_file">
						Files
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#panel_image">
						Photos
					</a>
				</li>
				<li class="">
					<a data-toggle="tab" href="#panel_comms">
						Comms Log
					</a>
				</li>
				
				<li>
						<a data-toggle="tab" href="#panel_history">
							Work History
						</a>
					</li>
					<li class="">
					<a data-toggle="tab" href="#panel_wc_access">
						WinkCentral Access
					</a>
				</li>
				@endif
			</ul>
			<div class="tab-content">
				<div id="panel_general" class="tab-pane fade in active">
                    	<div class="row">
							<div class="col-sm-6">
								<h4>Profile Details</h4>
								<hr>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent_status" class="control-label">Talent Status</label>
											{{
												Form::select(
													'talent_status',
												    $view_data->status,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_status : 2,
													array(
														'id' => 'talent_status',
														'class' => 'form-control'
													)
												)
											}}
										</div>
										
									</div>
								</div>
                                                                
								<label for="induction_complete" class="control-label">Induction</label>
								<div class="row">
									<div class="col-sm-4">
								
										<label class="checkbox-inline">
											{{
												Form::checkbox(
													'induction_complete',
													'1',
													(isset($talent_profile_model_data) && $talent_profile_model_data->induction_complete == 1) ? true : false,
													array(
														'class' => 'square-red'
													)
												)
											}}
											Induction Complete
										</label>
									</div>
									<div class="col-sm-5">
										{{
											Form::text(
												'induction_complete_date',
												(isset($talent_profile_model_data) && $talent_profile_model_data->induction_complete_date != null) ? date('d/m/Y H:i:s',strtotime($talent_profile_model_data->induction_complete_date)) : '',
												array(
													'id' => 'induction_complete_date',
													'class' => 'form-control',
													'placeholder'	=>	'DD/MM/YYYY H:i:s',
													'disabled'	=>	'disabled'
												)
											)
										}}
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent_category" class="control-label">Talent Type</label>
											{{
												Form::select(
													'talent_category',
													  $view_data->talent_category,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_category : false ,
													array(
														'id' => 'talent_category',
														'class' => 'form-control'
													)
												)
											}}
										</div>
										
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="first_name" class="control-label">
												Title <span class="symbol required"></span>
											</label>
											{{
												Form::select(
													'talent_title',$view_data->talent_title,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_title : null,
													array(
														'id' => 'talent_title',
														'class' => 'form-control',
													)
												)
											}}
										</div>
									</div>
								</div>

                                                                
                                                                <div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="first_legal_name" class="control-label">
												Legal First Name
											</label>
											{{
												Form::text(
													'first_legal_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->first_legal_name : null,
													array(
														'id' => 'first_legal_name',
														'class' => 'form-control '.(in_array('first_legal_name',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder' => 'Legal First Name'
													)
												)
											}}
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="last_legal_name" class="control-label">
												Legal Last Name
											</label>
											{{
												Form::text(
													'last_legal_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->last_legal_name : null,
													array(
														'id' => 'last_legal_name',
														'class' => 'form-control '.(in_array('last_legal_name',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder' => 'Legal Last Name'
													)
												)
											}}
										</div>
										
									</div>
								</div>
                                                                
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="first_name" class="control-label">
												Display First Name <span class="symbol required"></span>
											</label>
											{{
												Form::text(
													'first_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->first_name : null,
													array(
														'id' => 'first_name',
														'class' => 'form-control '.(in_array('first_name',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder' => 'Display First Name',
														'data-validation'	=> 'required'
													)
												)
											}}
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="last_name" class="control-label">
												Display Last Name <span class="symbol required"></span>
											</label>
											{{
												Form::text(
													'last_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->last_name : null,
													array(
														'id' => 'last_name',
														'class' => 'form-control '.(in_array('last_name',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder' => 'Display Last Name',
														'data-validation'	=> 'required'
													)
												)
											}}
										</div>
										
									</div>
								</div>
                                                                
                                                                
								<div class="row">
									<div class="col-sm-12">
										<label for="">Gender <span class="symbol required"></span> &nbsp;</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'gender',
													'1',
													( !isset($talent_profile_model_data) || (isset($talent_profile_model_data) && $talent_profile_model_data->gender == 1)) ? true : false,
													array(
														'id' => 'gender_male',
														'class' => 'flat-red',
													)
												)
											}}
											Male
										</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'gender',
													'2',
													(isset($talent_profile_model_data) && $talent_profile_model_data->gender == 2) ? true : false,
													array(
														'id' => 'gender_female',
														'class' => 'flat-red'
													)
												)
											}}
											Female
										</label>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="form-inline">
											<div class="form-group">
												<label for="dob" class="control-label">
													Date of Birth <span class="symbol required"></span> &nbsp;
												</label>											
											</div>
											<div class="form-group">
												{{
													Form::text(
														'dob',
														(isset($talent_profile_model_data) && $talent_profile_model_data->dob != null) ? date_reformat($talent_profile_model_data->dob) : null,
														array(
															'id' => 'dob',
															'class' => 'form-control bs-datepicker '.(in_array('dob',$talent_edited_fields) ? 'wc_modified':''),
															'placeholder'	=> 'DD/MM/YYYY',
															'data-validation'	=>	'date',
															'data-validation-format'	=>	'dd/mm/yyyy'
														)
													)
												}}
											</div>
											<div class="form-group">
												<label id="current-age">Current Age: <strong>-</strong> </label>
											</div>
										</div>
										
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="rating" class="control-label">Rating 1-5</label>
											{{
												Form::select(
													'rating',
													$view_data->rating,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->rating : null,
													array(
														'id' => 'rating',
														'class' => 'form-control'
													)
												)
											}}
										</div>
										
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="rating" class="control-label">Rating Notes</label>
											{{
												Form::textarea(
													'rating_notes',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->rating_notes : null,
													array(
														'id' => 'rating_notes',
														'class' => 'form-control',
														'rows'   =>  2
													)
												)
											}}
										</div>
										
									</div>
								</div>
								
								<div class="row radio_check_container">
									<div class="col-sm-5">
										<label for="">Drivers License &nbsp;</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_license',
													'0',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_license == 0 ) ? true : false,
													array(
														'id' => 'license_no',
														'class' => 'flat-red'
													)
												)
											}}
											No
										</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_license',
													'1',
												(isset($talent_profile_model_data) && $talent_profile_model_data->is_license == 1 ) ? true : false,
													array(
														'id' => 'license_yes',
														'class' => 'flat-red'
													)
												)
											}}
											Yes
										</label>
								
									</div>
									<div class="col-sm-4">
										<label for="">Drivers License No.</label>
											{{
										    Form::text(
										        'license',
										        (isset($talent_profile_model_data)) ? $talent_profile_model_data->license : '',
										        array(
														'id' => 'license',
														'class' => 'form-control',
														'placeholder'   =>  'License No.'
													)
										    )
										
										}}
									</div>
								</div>
                                <br/>
                                <div class="row radio_check_container">
									<div class="col-sm-5">
										<label for="">RSA  &nbsp;</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_rsa',
													'0',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_rsa == 0) ? true : false,
													array(
														'id' => 'rsa_no',
														'class' => 'flat-red'
													)
												)
											}}
											No
										</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_rsa',
													'1',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_rsa == 1) ? true : false,
													array(
														'id' => 'rsa_yes',
														'class' => 'flat-red'
													)
												)
											}}
											Yes
										</label>
								
									</div>
									<div class="col-sm-4">
										<label for="">RSA No.</label>
											{{
										    Form::text(
										        'rsa',
										        (isset($talent_profile_model_data)) ? $talent_profile_model_data->rsa : '',
										        array(
														'id' => 'rsa',
														'class' => 'form-control',
														'placeholder'   =>  'RSA No.'
													)
										    )
										
										}}
									</div>
								</div>
								<br/>
								<div class="row radio_check_container">
									<div class="col-sm-5">
										<label for="">RCG  &nbsp;</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_rcg',
													'0',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_rcg == 0 ) ? true : false,
													array(
														'id' => 'rcg_no',
														'class' => 'flat-red'
													)
												)
											}}
											No
										</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_rcg',
													'1',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_rcg == 1) ? true : false,
													array(
														'id' => 'rcg_yes',
														'class' => 'flat-red'
													)
												)
											}}
											Yes
										</label>
								
									</div>
									<div class="col-sm-4">
									<label for="">RCG No.</label>
											{{
										    Form::text(
										        'rcg',
										        (isset($talent_profile_model_data)) ? $talent_profile_model_data->rcg : '',
										        array(
														'id' => 'rcg',
														'class' => 'form-control',
														'placeholder'   =>  'RCG No.'
													)
										    )
										
										}}
									</div>
								</div>
								<br/>
								<div class="row radio_check_container">
									<div class="col-sm-5">
										<label for="">Passport  &nbsp;</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_passport',
													'0',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_passport == 0 ) ? true : false,
													array(
														'id' => 'passport_no',
														'class' => 'flat-red'
													)
												)
											}}
											No
										</label>
										<label class="radio-inline">
											{{
												Form::radio(
													'is_passport',
													'1',
													(isset($talent_profile_model_data) && $talent_profile_model_data->is_passport == 1) ? true : false,
													array(
														'id' => 'passport_yes',
														'class' => 'flat-red'
													)
												)
											}}
											Yes
										</label>
								
									</div>
									<div class="col-sm-4">
									<label for="">Passport No.</label>
											{{
										    Form::text(
										        'passport',
										        (isset($talent_profile_model_data)) ? $talent_profile_model_data->passport : '',
										        array(
														'id' => 'passport',
														'class' => 'form-control',
														'placeholder'   =>  'Passport No.'
													)
										    )
										
										}}
									</div>
								</div>

								<hr>
								<h4>Other Information</h4>
								<hr>
								@foreach($eav_fields as $key=>$data) 
								@if( $data->parent == 'talent_info')
									<div class="row">

										<div class="col-sm-9">
											@foreach($data->eav_fields as $field)
											<div class="form-group">
												<!--<label for="{{$field->attribute_name}}" class="control-label">{{ $field->attribute_title }}</label>-->
												{{ output_field($field,((isset($eav_data) && isset($eav_data[$field->attribute_name]))? $eav_data[$field->attribute_name]:''), $talent_edited_fields,$output=false) }}
											</div>
											@endforeach
										</div>
									</div>
								@endif
							@endforeach

							</div>
							<div class="col-sm-6">
								<h4>Payment Info</h4>
								<hr>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="pay_method" class="control-label">Employment basis <span class="symbol required"></span></label>
											{{
												Form::select(
													'pay_method',
													$view_data->talent_payment_method,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->pay_method : null,
													array(
														'id' => 'pay_method',
														'class' => 'form-control '.(in_array('pay_method',$talent_edited_fields) ? 'wc_modified':'')
													)
												)
											}}
										</div>
										
									</div>
								</div>
                                                                
                                                                <div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="push_to_xero" class="checkbox-inline">
											{{
												Form::checkbox(
													'push_to_xero',
													'1',
													((isset($talent_profile_model_data) && $talent_profile_model_data->push_to_xero == 1) || !isset($talent_profile_model_data)) ? true : false,
													array(
														'class' => 'square-red'
													)
												)
											}}
											Push to Xero
                                                                                        </label>
										</div>
										
									</div>
								</div>
                                                                
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent_start_date" class="control-label">Talent Start Date <span class="symbol required"></span> (DD/MM/YYYY)</label>
											{{
												Form::text(
													'talent_start_date',
													(isset($talent_profile_model_data) && $talent_profile_model_data->talent_start_date != null) ? date_reformat($talent_profile_model_data->talent_start_date) : null,
													array(
														'id' => 'talent_start_date',
														'class' => 'form-control bs-datepicker2 '.(in_array('talent_start_date',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'=> 'DD/MM/YYYY',
														'data-validation'	=>	'required'
													)
												)
											}}
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="pay_method" class="control-label">Payroll Calendar <span class="symbol required"></span></label>
											{{
												Form::select(
													'payroll_calendar',
													$view_data->talent_payroll_calendar,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->payroll_calendar : null,
													array(
														'id' => 'payroll_calendar',
														'class' => 'form-control '.(in_array('payroll_calendar',$talent_edited_fields) ? 'wc_modified':'')
													)
												)
											}}
										</div>
										
									</div>
								</div>
							<!--	<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="abn" class="control-label">
												ABN
											</label>
											{{
												Form::text(
													'abn',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->abn : null,
													array(
														'id' => 'abn',
														'class' => 'form-control '.(in_array('abn',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'Contractor ABN number'
													)
												)
											}}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="trading_name" class="control-label">
												Trading Name
											</label>
											{{
												Form::text(
													'trading_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->trading_name : null,
													array(
														'id' => 'trading_name',
														'class' => 'form-control '.(in_array('tarding_name',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'Contractor Business Name'
													)
												)
											}}
										</div>
									</div>
								</div>-->
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="tfn" class="control-label">
												TFN 
											</label>
											{{
												Form::text(
													'tfn',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->tfn : null,
													array(
														'id' => 'tfn',
														'class' => 'form-control '.(in_array('tfn',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'ex. 111-111-111'
													)
												)
											}}
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="tfn" class="control-label">
												TFN Exemption
											</label>
											{{
												Form::select(
													'tfn_exemption',
													$view_data->tfn_exemption,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->tfn_exemption : null,
													array(
														'id' => 'tfn_exemption',
														'class' => 'form-control '.(in_array('tfn_exemption',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'TFN Exemption'
													)
												)
											}}
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="bank" class="control-label">
												Bank 
											</label>
											{{
												Form::text(
													'bank',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->bank : null,
													array(
														'id' => 'bank',
														'class' => 'form-control '.(in_array('bank',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'Bank Name'

													)
												)
											}}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="bank" class="control-label">
												Account Name 
											</label>
											{{
												Form::text(
													'bank_account_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->bank_account_name : null,
													array(
														'id' => 'bank_account_name',
														'class' => 'form-control '.(in_array('bank_account_name',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'Bank Account Name'
													)
												)
											}}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="bank_bsb" class="control-label">
												BSB 
											</label>
											{{
												Form::text(
													'bank_bsb',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->bank_bsb : null,
													array(
														'id' => 'bank_bsb',
														'class' => 'form-control '.(in_array('bank_bsb',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'ex. 111-111'
													)
												)
											}}
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="bank_account" class="control-label">
												Account # 
											</label>
											{{
												Form::text(
													'bank_account',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->bank_account : null,
													array(
														'id' => 'bank_account',
														'class' => 'form-control '.(in_array('bank_account',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'ex. 1111111111',
														'data-validation' => "number",
														'data-validation-optional'	=> 'true',
														'data-validation-error-msg' => "Please enter a valid account number."
													)
												)
											}}
										</div>
									</div>
								</div>
                                                                <div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="super_provider" class="control-label">
												Deprecated Free text Super
											</label>
											{{
												Form::text(
													'super_provider',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->super_provider : null,
													array(
														'id' => 'super_provider',
														'class' => 'form-control '.(in_array('super_provider',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'Superannuation Provider',
                                                                                                                'disabled' => 'disabled'
													)
												)
											}}
										</div>
										
									</div>
								</div>
		
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="superfund_id" class="control-label">
												Superannuation Fund 
											</label>
                                                                                        {{
                                                                                            Form::select(
                                                                                                'superfund_id',
                                                                                                \ms_superfund::getFormSelectArray(),
                                                                                                (isset($talent_profile_model_data) && ($talent_profile_model_data->superfund_id)) ? $talent_profile_model_data->superfund_id : 0,
                                                                                                array(
                                                                                                    'id' => 'superfund_id',
                                                                                                    'class' => 'form-control '.(in_array('superfund_id',$talent_edited_fields) ? 'wc_modified':'')
                                                                                                )
                                                                                            )
											}}
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="pay-super-accntnum" class="control-label">
												Super Account # 
											</label>
											{{
												Form::text(
													'super_account',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->super_account : null,
													array(
														'id' => 'super_account',
														'class' => 'form-control '.(in_array('super_account',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder'	=>	'ex. 11111111',
														'data-validation'	=>	'number',
														'data-validation-optional'	=>	'true'
													)
												)
											}}
										</div>
									</div>
								</div>


							</div>
						</div>

						<hr>
						
						<div class="row">
							<div class="col-md-8">
								<p>
									<!-- By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions. -->
								</p>
							</div>
							<div class="col-md-4">
								<button class="btn btn-success btn-block" type="submit">
									Save Profile <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>
				</div>
				<!-- // GENERAL TAB PANEL -->
				

				<div id="panel_contact" class="tab-pane fade">
					<form action="#" ></form>
						<div class="row">
							<div class="col-sm-12">
								<h4>Contact Info</h4>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<!-- <h4>Contact Info</h4>
								<hr> -->

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="contact-email" class="control-label">
												Email <span class="symbol required"></span>
											</label>
											{{
												Form::text(
													'email',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->email : null,
													array(
														'id' => 'email',
														'class' => 'form-control '.(in_array('email',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder' => 'Email',
														'data-validation'	=>	'required'
													)
												)
											}}
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="contact-mobile" class="control-label">
												Mobile <span class="symbol required"></span>
											</label>
											{{
												Form::text(
													'mobile',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->mobile : null,
													array(
														'id' => 'mobile',
														'class' => 'form-control '.(in_array('mobile',$talent_edited_fields) ? 'wc_modified':''),
														'placeholder' => 'Mobile',
														'data-validation'	=>	'required'
													)
												)
											}}
								
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="contact-phone" class="control-label">
												Phone
											</label>
											{{
												Form::text(
													'phone',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->phone : null,
													array(
														'id' => 'phone',
														'class' => 'form-control',
														'placeholder' => 'Phone'
													)
												)
											}}
								
										</div>
										
									</div>
								</div>
								<hr/>
								<div class="row">
									<div class="col-sm-9">
										<label for="url-website">Website URL</label>
										<span class="input-icon form-group">
											{{
												Form::text(
													'website',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->website : null,
													array(
														'id' => 'website',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-globe"></i> 
										</span>
									</div>
								</div>
								<!--<div class="row">
									<div class="col-sm-9">
										<label for="url-profile">Profile Page URL</label>
										<span class="input-icon form-group">
											{{
												Form::text(
													'profile_page_url',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->profile_page_url : null,
													array(
														'id' => 'profile_page_url',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-user"></i> 
										</span>
									</div>
								</div>-->
								<div class="row">
									<div class="col-sm-9">
										<label for="id-skype">Skype</label>
										<span class="input-icon form-group">
											{{
												Form::text(
													'skype',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->skype : null,
													array(
														'id' => 'skype',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-skype"></i> 
										</span>
									</div>
								</div>

								<hr>
								<h5>Social Media Info</h5>
								<hr>

								<div class="row">
									<div class="col-sm-9">
										<label for="url-twitter">Twitter</label>
										<span class="input-icon form-group">
											{{
												Form::text(
													'twitter',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->twitter : null,
													array(
														'id' => 'twitter',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-twitter"></i> 
										</span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<label for="url-instagram">Instagram</label>
										<span class="input-icon form-group">
											{{
												Form::text(
													'instagram',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->instagram : null,
													array(
														'id' => 'instagram',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-instagram"></i> 
										</span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<label for="url-facebook">Facebook</label>
										<span class="input-icon form-group">
											{{
												Form::text(
													'facebook',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->facebook : null,
													array(
														'id' => 'facebook',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-facebook"></i> 
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<!-- <h4>Talent Address</h4>
								<hr> -->
	@if(isset($talent_profile_model_data->talent_gmap_link))
		<a class="btn btn-xs btn-default" href="{{ $talent_profile_model_data->talent_gmap_link }}" target="_blank"><i class="fa fa-map-marker"></i> View in Google Maps</a>
	@endif							
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent-address" class="control-label">
												Address 
											</label>
											{{
												Form::text(
													'address',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->address : null,
													array(
														'id' => 'address',
														'class' => 'form-control'
													)
												)
											}}
								
										</div>
										
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent-city" class="control-label">
												City/Suburb 
											</label>
											{{
												Form::text(
													'city',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->city : null,
													array(
														'id' => 'city',
														'class' => 'form-control'
													)
												)
											}}
										</div>
										
									</div>
								</div>


								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent-state" class="control-label">State <span class="symbol required"></span></label>
											{{
												Form::select(
													'state',
													$view_data->state,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->state : 'NSW',
													array(
														'id' => 'state',
														'class' => 'form-control',
														'data-validation'	=>	'required'
													)
												)
											}}
										</div>
										
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent-postal" class="control-label">
												Postcode 
											</label>
											{{
												Form::text(
													'postcode',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->postcode : null,
													array(
														'id' => 'postcode',
														'class' => 'form-control'
													)
												)
											}}
										</div>
										
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="talent-country" class="control-label">Country <span class="symbol required"></span></label>
											{{
												Form::select(
													'country',
													$view_data->country,
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->country : 'Australia',
													array(
														'id' => 'country',
														'class' => 'form-control'
													)
												)
											}}
										</div>
										
									</div>
								</div>

								<hr>
								<h5>Emergency Contact</h5>
								<hr>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="emergency-conact-name" class="control-label">
												Name
											</label>
											{{
												Form::text(
													'emergency_name',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->emergency_name : null,
													array(
														'id' => 'emergency_name',
														'class' => 'form-control'
													)
												)
											}}
								
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="emergency-conact-relationship" class="control-label">
												Relationship
											</label>
											{{
												Form::text(
													'emergency_rship',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->emergency_rship : null,
													array(
														'id' => 'emergency_rship',
														'class' => 'form-control',
														'placeholder' => 'Relationship'														
													)
												)
											}}
										</div>
										
									</div>
								</div>

								<div class="row">
									<div class="col-sm-9">
										<div class="form-group">
											<label for="emergency-conact-phone" class="control-label">
												Phone
											</label>
											{{
												Form::text(
													'emergency_phone',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->emergency_phone : null,
													array(
														'id' => 'emergency_phone',
														'class' => 'form-control',
														'placeholder' => 'Phone'
													)
												)
											}}
										</div>
										
									</div>
								</div>


							</div>
						</div>

						<hr>
						
						<div class="row">
							<div class="col-md-8">
								<p>
									<!-- By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions. -->
								</p>
							</div>
							<div class="col-md-4">
								<button class="btn btn-success btn-block" type="submit" data-tab="#panel_contact">
									Save Profile <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>
				</div>

				<!-- start: EAV fields -->
				<div id="panel_profile" class="tab-pane fade">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="alert alert-info">
								<i class="fa fa-info-circle"></i>
								<strong>Please note:</strong> Leave fields blank which are not applicable to the specific gender of the talent.
							</div>
						</div>
					    <?php $index = 0; ?>
						@foreach($eav_fields as $key=>$data) 
						    @if( $data->parent == 'profile')
    							@if( $index%3 == 0)
    								</div><div class="row">
    							@endif
    							<div class="col-sm-4">
    								<h4>{{$data->title}}</h4>
    								<hr>
    								@foreach($data->eav_fields as $field)
    								<div class="form-group">
    									<!-- <label for="talent-height" class="control-label">Height</label> -->
    									{{ output_field($field,((isset($eav_data) && isset($eav_data[$field->attribute_name]))? $eav_data[$field->attribute_name]:''), $talent_edited_fields, $output=false) }}
    								</div>
    								@endforeach
    							</div>
    							<?php $index++;?>
    						@endif
						@endforeach
					</div>

					<hr>
						
						<div class="row">
							<div class="col-md-8">
								<p>
									<!-- By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions. -->
								</p>
							</div>
							<div class="col-md-4">
								<button class="btn btn-success btn-block" type="submit" data-tab="#panel_profile">
									Save Profile <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>

				</div>

				<div id="panel_influrences" class="tab-pane">
								<div class="row">
									<div class="col-sm-12">
										<h4>Influencers</h4>
										<hr>

										<label for="influential" class="control-label">Influencers</label>
										<div class="row">
											<div class="col-sm-6">
												<label class="checkbox-inline">
													{{
                                                        Form::checkbox(
                                                            'influential',
                                                            '1',
                                                            (isset($talent_profile_model_data) && $talent_profile_model_data->influential == 1) ? true : false,
                                                            array(
                                                                'class' => 'square-red'
                                                            )
                                                        )
                                                    }}
													Check if the model is an influencer
												</label>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-9">
												<label for="url-instagram">Instagram</label>
												<span class="input-icon form-group">
											{{
												Form::text(
													'instagram',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->instagram : null,
													array(
														'id' => 'instagram',
														'class' => 'form-control'
													)
												)
											}}
											<i class="fa fa-instagram"></i>
										</span>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-9">
												<label for="url-instagram">Instagram Followers</label>
												<span class="input-icon form-group">
											{{
												Form::text(
													'instagram_followers',
													(isset($talent_profile_model_data)) ? $talent_profile_model_data->instagram_followers : null,
													array(
														'id' => 'instagram_followers',
														'class' => 'form-control',
														'disabled' => "disabled"
													)
												)
											}}
												</span>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label for="rating" class="control-label">About the influencer on Winkmodels</label>
													{{
                                                        Form::textarea(
                                                            'about',
                                                            (isset($talent_profile_model_data)) ? $talent_profile_model_data->about : null,
                                                            array(
                                                                'id' => 'rating_notes',
                                                                'class' => 'form-control',
                                                                'rows'   =>  2
                                                            )
                                                        )
                                                    }}
												</div>
											</div>
										</div>
										<label for="influential" class="control-label">Category Influencers</label>
										<div class="row">
											@foreach($view_data->category_influencers as $field)
												<div class="col-sm-4">
													<label class="checkbox-inline">
														{{
                                                            Form::checkbox(
                                                                'category_influencers['.$field.']',
                                                                '1',
                                                                (isset($talent_profile_model_data) && $talent_profile_model_data->category_influencers && in_array($field , $talent_profile_model_data->category_influencers) ? true : false),
                                                                array(
                                                                    'class' => 'square-red'
                                                                )
                                                            )
                                                        }}
														{{$field}}
													</label>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							<div class="row">
								<div class="col-md-8">
									<p>
										<!-- By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions. -->
									</p>
								</div>
								<div class="col-md-4">
									<button class="btn btn-success btn-block" type="submit" data-tab="#panel_influrences">
										Save Profile <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
				</div>

				<div id="panel_skill" class="tab-pane fade">
					<input type="hidden" name="delete_data_arr" id="delete_data_arr" />
					<div class="row">
					    <?php $index = 0; ?>
						@foreach($eav_fields as $key=>$data) 
						    @if( $data->parent == 'skill')
    							@if( $index%3 == 0 && $index != 0)
    								</div><div class="row">
    							@endif
    							<div class="col-sm-4">
    								<h4>{{$data->title}}</h4>
    								<hr>
    								<select id="{{ $data->taxonomy }}" class="form-control select-list">
    								    <option value=""> - - Select one - - </option>
    								    @foreach($data->eav_fields as $field)
    								        @if(!(isset($eav_data) && isset($eav_data[$field->attribute_name])) )
    								            <option value="{{ $field->attribute_name }}">{{ $field->attribute_title}}</option>
    								        @else
    								             <option value="{{ $field->attribute_name }}" style="display:none">{{ $field->attribute_title}}</option>
    								        @endif
    								    @endforeach
    								</select>
    								<br/>
    								<table id="{{$data->taxonomy}}_container" class="table table-striped table-hover">
    								<tr>
    								    <th>Skill</th>
    								    <th class="center">Novice</th>
    								    <th class="center">Intermediate</th>
    								    <th class="center">Expert</th>
    								    <th class="center">Action</th>
    								</tr>
    								@foreach($data->eav_fields as $field)
    							        @if((isset($eav_data) && isset($eav_data[$field->attribute_name])) )
    									{{ output_field($field,((isset($eav_data) && isset($eav_data[$field->attribute_name]))? $eav_data[$field->attribute_name]:''), $talent_edited_fields,$output=false) }}
    									@endif
    								@endforeach
    								</table>
    							</div>
    							<?php $index++; ?>
    						@endif
						@endforeach
					</div>

						<hr>
						
						<div class="row">
							<div class="col-md-8">
								<p>
									<!-- By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions. -->
								</p>
							</div>
							<div class="col-md-4">
								<button class="btn btn-success btn-block" type="submit" data-tab="#panel_skill">
									Save Profile <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>

				</div>
				
				<!--start: files panel-->
				<div id="panel_file" class="tab-pane fade">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<button type="button" class="btn btn-bricky btn-upload-new-file pull-right"><i class="fa fa-picture-o"></i> Add a new file</button>
								</div>
							</div>
						</div>
					    <hr>
					    <div class="row">
					        <div class="col-sm-12">
					            <table class="table table-striped table-bordered table-hover" id="file_list">
					                <tr>
					                    <th>File Name</th>
					                    <th>Description</th>
					                    <th>File Format</th>
					                    <th>Recored at</th>
					                    <th>Action</th>
					                </tr>
					                @if( !empty($files))
					                    @foreach($files as $file)
					                    <tr id="file_{{ $file->file_id}}">
					                    <td><a href="{{ url('file/'.$file->file_path).'/'.$file->file_name.'.'.$file->file_ext }}" target="_blank">{{ $file->file_name.'.'.$file->file_ext }}</a></td>
					                    <td>{{ $file->file_title }} </td>
					                    <td>{{ strtoupper($file->file_ext) }}</td>
					                    <td>{{ date_reformat($file->recorded_at) }}</td>
					                    <td>
					                       	<!--<button type="button" class="btn btn-xs btn-teal btn-edit-file" data-talent_id="{{(isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id:"")}}" data-file="{{ $file->file_id }}"><i class="fa fa-edit"></i></button>-->
											<button type="button" class="btn btn-xs btn-bricky btn-delete-file" data-talent_id="{{(isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id:"")}}" data-file_id="{{ $file->file_id }}"><i class="fa fa-trash-o"></i></button>
					                    </td>
					                    </tr>
					                    @endforeach
					                @endif
					            </table>
					        </div>
					    </div>
						
				</div>
				<!--end: files panel-->	

				<div id="panel_image" class="tab-pane fade">

					<div class="row">
						<div class="col-sm-5" style="z-index:1;">
							<div class="image-panel-filter">
								<h4>Images</h4>
								<div class="form-inline filter-upload">
															
									<div class="form-group"><label>Filter by:</label></div>	
									<div class="form-group">
										<select class="form-control" id="select-filter-images">
											<option value="all">All</option>
											<option value="0">Requires Review</option>
											<option value="1">Approved</option>
											<option value="2">Expired</option>
										</select>
									</div>	
									<input type="file" id="new-img-temp" style="display:block; position:absolute; visibility:hidden;">
    									<button type="button" class="btn btn-bricky btn-upload-new-img"><i class="fa fa-picture-o"></i> Add a new image</button>
    								
								</div>

								<div class="clearfix"></div>
								<div id="upload_progress_bar">
								    <div class="bar"></div>
								</div>
							</div>
							<!-- //.image-panel-filter -->
						</div>

						<div class="col-sm-7" style="z-index:2;">

							<div class="image-set-filter">
								<h4>Edit Image Set</h4>
								<div class="form-inline select-image-set-wrap">
															
									<div class="form-group"><label>Select Image set:</label></div>	
									<div class="form-group">
										<select class="form-control" id="select-image-set-pane">
											<option value="#website_image_set" selected>Website</option>
											<option value="#digi_image_set">Digis</option>
											<option value="#compcard_image_set">Comp Card</option>
											<option value="#profile_image_set">Profile Image</option>
										</select>
									</div>	
									
								</div>

								<div class="clearfix"></div>
							</div>
							<!-- //.image-set-filter -->
							
							<!-- <div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="input-group">
									<div class="form-control uneditable-input">
										<i class="icon-file fileupload-exists"></i>
										<span class="fileupload-preview"></span>
									</div>
									<div class="input-group-btn">
										<div class="btn btn-light-grey btn-file">
											<span class="fileupload-new"><i class="fa fa-picture-o"></i>&nbsp;Select file</span>
											<span class="fileupload-exists"><i class="fa fa-picture-o"></i>&nbsp;Change</span>
											<input type="file" class="file-input">
										</div>
										<a href="#" class="btn btn-light-grey fileupload-exists" data-dismiss="fileupload"><i class="icon-remove"></i> Remove</a>
									</div>
								</div>
							</div> -->
						</div>

					</div>
					<!-- //.row -->

					<div class="row image-row-panel">
						<div class="col-sm-5">
							<div class="image-list-selection-wrap" id="image-list-selection-container">
								@if( !empty($images))
                                @foreach($images as $image)
                                <div id="image_{{$image->file_id}}" class="img-item" data-talent_id= "{{(isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id:"")}}" data-file_id="{{$image->file_id}}" data-img="{{ asset($image->file_path).'/'.$image->file_name.'.'.$image->file_ext }}" data-name="{{ $image->file_title }}" data-date="{{ date_reformat($image->recorded_at) }}" data-status="{{ $image->status }}" data-width="{{ $image->width}}" data-height="{{ $image->height}}">
									<div class="item-wrap">
										<div class="prev-img">
											<div class="preview-wrap">
												<img src="{{ asset($image->file_path).'/'.$image->file_name.'_admin_thumbnail.'.$image->file_ext }}" alt="">
											</div>

											<a href="{{ asset($image->file_path).'/'.$image->file_name.'.'.$image->file_ext }}" class="prev-colorbox btn btn-link btn-prev-img">
												<i class="fa fa-search-plus"></i>
											</a>
											<button type="button" class="btn btn-xs btn-bricky btn-edit-img" data-talent_id="{{(isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id:"")}}" data-image_id="{{ $image->file_id }}" data-status="{{ $image->status }}"><i class="fa fa-edit"></i></button>
											<button type="button" class="btn btn-xs btn-bricky btn-delete-img" data-talent_id="{{(isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id:"")}}" data-image_id="{{ $image->file_id }}"><i class="fa fa-trash-o"></i></button>
											<span class="btn btn-xs btn-green btn-move-img item-handle" data-talent_id="{{(isset($talent_profile_model_data) ? $talent_profile_model_data->talent_id:"")}}" data-image_id="{{ $image->file_id }}"><i class="fa fa-arrows"></i></span>
										</div>

										<div class="img-details">
											<p><strong>Status:&nbsp;</strong> <span class="{{$view_data->image_status[$image->status]['class']}} img-status">{{ $view_data->image_status[$image->status]['title'] }}</span></p>
											<p><strong>ID:&nbsp;</strong> <span> {{ $image->file_id }}</span></p>
											<p><strong>Name:&nbsp;</strong><span class="img-title">{{ $image->file_title }}</span></p>
											<p><strong>Date:&nbsp;</strong><span class="img-recorded">{{ date_reformat($image->recorded_at) }}</span></p>
										</div>
									</div>
								</div>
                                @endforeach
                                @endif
                                
								<!-- //.img-item -->

								<!--<div class="img-item" data-img="{{ asset('images/') }}/winkmum/sample.jpg">
									<div class="item-wrap">
										<div class="prev-img">
											<div class="preview-wrap">
												<img src="{{ asset('images/') }}/winkmum/sample.jpg" alt="">
											</div>

											<a href="{{ asset('images/') }}/winkmum/sample.jpg" class="prev-colorbox btn btn-link btn-prev-img">
												<i class="fa fa-search-plus"></i>
											</a>
											<button type="button" class="btn btn-xs btn-bricky btn-edit-img"><i class="fa fa-edit"></i></button>
											<button type="button" class="btn btn-xs btn-bricky btn-delete-img"><i class="fa fa-trash-o"></i></button>
											<button type="button" class="btn btn-xs btn-green btn-move-img item-handle"><i class="fa fa-arrows"></i></button>
										</div>

										<div class="img-details">
											<p><strong>Status:&nbsp;</strong> <span class="warning img-status">Requires Review</span></p>
											<p><strong>Name:&nbsp;</strong>Cat Pool</p>
											<p><strong>Date:&nbsp;</strong>04/11/14</p>
											<p><strong>Dimensions:&nbsp;</strong>1200px x 800px</p>
											<p><strong>Size:&nbsp;</strong>853kbs</p>
										</div>
									</div>
								</div>-->

							</div>
							<!-- //.image-list-selection-wrap -->
						</div>
						<!-- //.col -->
						<div class="col-sm-7">
							<!-- Tab panes -->
							<div class="tab-content images-set-tabs-wrapper">
								<div class="tab-pane" id="website_image_set" style="display:block;">
									<!-- // this is a template element.. not included in the loop -->
									<div class="webimage-function">
										<button type="button" class="btn btn-bricky save-image-set-changes" data-talent_id="{{	(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_id : null }}">Save Changes</button>
								    </div>
								    <hr>
									<div class="img-website-item template-item">
										<div class="item-wrap">
											<div class="prev-img">
												<div class="preview-wrap">
													<img src="" alt="">
												</div>
												<button type="button" class="btn btn-xs btn-bricky btn-delete-img-website-set"><i class="fa fa-trash-o"></i></button>
												<span class="btn btn-xs btn-green btn-move-img item-handle"><i class="fa fa-arrows"></i></span>
											</div>
										</div>
									</div>
									<!-- // this is a template element.. not included in the loop -->									
									<!-- //.img-website-item -->
									@if($website_images != NULL)
									@foreach($website_images as $image)
                                    <div class="img-website-item" data-file_id="{{$image->file_id }}">
										<div class="item-wrap">
											<div class="prev-img">
												<div class="preview-wrap">
													<img src="{{str_replace('_medium','_admin_thumbnail',asset($image->meta_data)) }}" alt="">
												</div>
												<button type="button" class="btn btn-xs btn-bricky btn-delete-img-website-set"><i class="fa fa-trash-o"></i></button>
												<span class="btn btn-xs btn-green btn-move-img item-handle"><i class="fa fa-arrows"></i></span>
											</div>
										</div>
									</div>
									@endforeach
									@endif
								
								</div>
                                                            
                                                                <div class="tab-pane" id="digi_image_set" style="display:block;">
									<!-- // this is a template element.. not included in the loop -->
									<div class="webimage-function">
										<button type="button" class="btn btn-bricky save-image-set-changes" data-talent_id="{{	(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_id : null }}">Save Changes</button>
								    </div>
								    <hr>
									<div class="img-digi-item template-item">
										<div class="item-wrap">
											<div class="prev-img">
												<div class="preview-wrap">
													<img src="" alt="">
												</div>
												<button type="button" class="btn btn-xs btn-bricky btn-delete-img-digi-set"><i class="fa fa-trash-o"></i></button>
												<span class="btn btn-xs btn-green btn-move-img item-handle"><i class="fa fa-arrows"></i></span>
											</div>
										</div>
									</div>
									<!-- // this is a template element.. not included in the loop -->									
									<!-- //.img-website-item -->
									@if(isset($digi_images) && $digi_images != NULL)
									@foreach($digi_images as $image)
                                                                        <div class="img-digi-item" data-file_id="{{$image->file_id }}">
										<div class="item-wrap">
											<div class="prev-img">
												<div class="preview-wrap">
													<img src="{{str_replace('_medium','_admin_thumbnail',asset($image->meta_data)) }}" alt="">
												</div>
												<button type="button" class="btn btn-xs btn-bricky btn-delete-img-digi-set"><i class="fa fa-trash-o"></i></button>
												<span class="btn btn-xs btn-green btn-move-img item-handle"><i class="fa fa-arrows"></i></span>
											</div>
										</div>
									</div>
									@endforeach
									@endif
								
								</div>
                                                            
								<!-- //#website -->
								<div class="tab-pane" id="compcard_image_set">
									
									<div class="compcard-wrap">
										<div class="img-grid-row">
											<div class="img-grid-col grid-w1">
												<div class="img-grid-cell grid-h1">
													<div class="img-grid-pane" data-rwidth="330" data-rheight="480" data-comp_index="1">

														<button type="button" class="btn btn-xs btn-bricky btn-delete-img {{ isset($comp_images[1]) ? '': 'hide'}}"><i class="fa fa-trash-o"></i></button>
														<!--<button type="button" class="btn btn-xs btn-info btn-zoom-in"><i class="fa fa-plus"></i></button>
														<button type="button" class="btn btn-xs btn-info btn-zoom-out"><i class="fa fa-minus"></i></button>-->
											            
											            @if(isset($comp_images[1]))
											            <div class="panzoom-wrap" data-file_id="{{$comp_images[1]->file_id}}">
                                                            <img src="{{ asset($comp_images[1]->meta_data) }}" width="312px" height="473px">
                                                        </div>
                                                        @else
                                                        <div class="panzoom-wrap">
                                                            <img width="330px" height="480px" alt="">
                                                        </div>
                                                        @endif
                                                        <!-- // please add the previous values to the hidden fields -->
                                                       
                                                    </div>
												</div>
											</div>
											<!-- //.img-grid-col -->

											<div class="img-grid-col grid-3w2">
												<div class="img-grid-cell grid-h2">
													<div class="img-grid-pane" data-rwidth="180" data-rheight="245" data-comp_index="2">

														<button type="button" class="btn btn-xs btn-bricky btn-delete-img {{ isset($comp_images[2]) ? '': 'hide'}}"><i class="fa fa-trash-o"></i></button>
														<!--<button type="button" class="btn btn-xs btn-info btn-zoom-in"><i class="fa fa-plus"></i></button>
														<button type="button" class="btn btn-xs btn-info btn-zoom-out"><i class="fa fa-minus"></i></button>-->
											            
											            @if(isset($comp_images[2]))
											            <div class="panzoom-wrap" data-file_id="{{$comp_images[2]->file_id}}">
                                                            <img src="{{ asset($comp_images[2]->meta_data) }}" width="162px" height="227px">
                                                        </div>
                                                        @else
                                                        <div class="panzoom-wrap">
                                                            <img width="180px" height="245px" alt="">
                                                        </div>
                                                        @endif
                                                       
													</div>
													
												</div>

												<div class="img-grid-cell grid-h2">
													<div class="img-grid-pane" data-rwidth="180" data-rheight="245" data-comp_index="3">

														<button type="button" class="btn btn-xs btn-bricky btn-delete-img {{ isset($comp_images[3]) ? '': 'hide'}}"><i class="fa fa-trash-o"></i></button>
														<!--<button type="button" class="btn btn-xs btn-info btn-zoom-in"><i class="fa fa-plus"></i></button>
														<button type="button" class="btn btn-xs btn-info btn-zoom-out"><i class="fa fa-minus"></i></button>-->
														
													    @if(isset($comp_images[3]))
											            <div class="panzoom-wrap" data-file_id="{{$comp_images[3]->file_id}}">
                                                            <img src="{{ asset($comp_images[3]->meta_data) }}" width="162px" height="227px">
                                                        </div>
                                                        @else
                                                        <div class="panzoom-wrap">
                                                            <img width="180px" height="245px" alt="">
                                                        </div>
                                                        @endif
                                                        
													</div>
													
												</div>
												
											</div>
											<!-- //.img-grid-col -->
										</div>
										<!-- //.img-grid-row -->

										<div class="img-grid-row">
											<div class="img-grid-col grid-w3">
												<div class="img-grid-cell grid-h3">
													<div class="img-grid-pane" data-rwidth="250" data-rheight="160" data-comp_index="4">

														<button type="button" class="btn btn-xs btn-bricky btn-delete-img {{ isset($comp_images[4]) ? '': 'hide'}}"><i class="fa fa-trash-o"></i></button>
														<!--<button type="button" class="btn btn-xs btn-info btn-zoom-in"><i class="fa fa-plus"></i></button>
														<button type="button" class="btn btn-xs btn-info btn-zoom-out"><i class="fa fa-minus"></i></button>-->
														
														@if(isset($comp_images[4]))
											            <div class="panzoom-wrap" data-file_id="{{$comp_images[4]->file_id}}">
                                                            <img src="{{ asset($comp_images[4]->meta_data) }}" width="237px" height="152px">
                                                        </div>
                                                        @else
                                                        <div class="panzoom-wrap">
                                                            <img width="250px" height="160px" alt="">
                                                        </div>
                                                        @endif
                                                       
													</div>
													
												</div>
											</div>
											<!-- //.img-grid-col -->

											<div class="img-grid-col grid-w3">
												<div class="img-grid-cell grid-h3">
													<div class="img-grid-pane" data-rwidth="250" data-rheight="160" data-comp_index="5">

														<button type="button" class="btn btn-xs btn-bricky btn-delete-img {{ isset($comp_images[5]) ? '': 'hide'}}"><i class="fa fa-trash-o"></i></button>
														<!--<button type="button" class="btn btn-xs btn-info btn-zoom-in"><i class="fa fa-plus"></i></button>
														<button type="button" class="btn btn-xs btn-info btn-zoom-out"><i class="fa fa-minus"></i></button>-->
														
														@if(isset($comp_images[5]))
											            <div class="panzoom-wrap" data-file_id="{{$comp_images[5]->file_id}}">
                                                            <img src="{{ asset($comp_images[5]->meta_data) }}" width="237px" height="152px">
                                                        </div>
                                                        @else
                                                        <div class="panzoom-wrap">
                                                            <img width="250px" height="160px" alt="">
                                                        </div>
                                                        @endif
													</div>
													
												</div>
												
											</div>
											<!-- //.img-grid-col -->
										</div>
										<!-- //.img-grid-row -->
										
									</div>
									<!-- //.compcard-wrap -->

									<div class="text-center">
										<form action="comp-card-output" method="POST">
										<!--<button type="button" class="btn btn-primary btn-preview-comp-card"><i class="fa fa-search"></i> Preview Comp Card</button>-->
										<a href="{{url('winkmum/talent/profile/comp-card-output')}}?preview=1&comp_card_talent_id={{(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_id : null }}" class="btn btn-primary btn-pdf-comp-card" target="_blank"><i class="fa fa-search"></i> Preview Comp Card</a>
										<input type="hidden" name="comp_card_talent_id" value="{{ (isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:'') }}" />
										<a href="{{url('winkmum/talent/profile/comp-card-output')}}?comp_card_talent_id={{(isset($talent_profile_model_data)) ? $talent_profile_model_data->talent_id : null }}" class="btn btn-bricky btn-pdf-comp-card" target="_blank"><i class="clip-file-pdf "></i> Generate Comp Card</a>
									    </form>
									</div>


								</div>
								<!-- //#compard -->

								<div class="tab-pane" id="profile_image_set">
								    
								    <div class="current-img-wrap">
								        <h4>Current Profile Image</h4>
    								    <div class="profile-image-border">
    								        <div class="profile-image-display">
    								            <img src="{{ asset($profile_image['file_path'].$profile_image['file_name'].'.'.$profile_image['file_ext']) }}" class="talent-profile-image" alt="">
    								        </div>
    								    </div>
    								</div>
    								
									
									<div class="preview-wrapper" style="display:none;">
								        <h4>Preview Profile Image</h4>
    									<div class="profile-image-preview">
    								        <div class="profile-preview-container">
    								            <img src="" class="img-prev" alt="Preview">
    								        </div>
    								    </div>
    								</div>
								    <hr>
								    <h4 class="set-new-title">
								        Set New Profile Image 
								       
								    </h4>
									<div class="profle-image-wrap">
                                        
										
										<div class="profile-image-container">
                                             <img src="" id="talent-profile-image" class="talent-profile-image-crop" alt="">
                                        </div>
										
									</div>
								</div>
								<!-- //#profile_image_set -->
							</div>

							
						</div>
						<!-- //.col -->
						
					</div>
					<!-- //.row -->
				</div>
				<!-- //#panel_image -->
				
								<!--start: files panel-->
				<div id="panel_comms" class="tab-pane fade">
						<div class="tabbable">
                            <ul class="nav nav-tabs tab-padding tab-space-3 tab-bricky" id="myTab6">
                                <li class="active">
                                    <a data-toggle="tab" href="#panel_email_log">
                                        Email
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#panel_sms_log">
                                        Sms
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                 <div id="panel_email_log" class="tab-pane active">
                                    <div class="table-comms-wrap">
            							<table class="table table-hover table-striped table-full-width table-bordered data-table-comms">
											<thead>
												<tr>
													@foreach($email_fields as $field)
													    <th class="{{ $field['class'] }}">{{ $field['title'] }}</th>
													@endforeach
												    <th class="center">Action</th>
												</tr>
											</thead>
											<tbody>
												@if($emails)
											    @foreach($emails as $email)
											    <tr id="row_{{$email->email_id}}">
											         @foreach($email_fields as $key=>$field)
											            @if($key == 'category')
											                <td class="comm_{{$key}} {{ $field['class']}}">{{ $view_data->message_category[$email->{$key}]['title'] }}</td>
											            @elseif($key == 'updated_at')
											                <td class="comm_{{$key}} {{ $field['class']}}">{{ date('d M Y', strtotime($email->{$key})) }}</td>
											            @elseif( $key == 'sent')
											                <td  class="comm_{{$key}} {{ $field['class']}}"><span class="label {{ $email->sent ==1 ? 'label-success': 'label-danger' }}">{{ $email->sent ? 'Sent':'No' }} </span></td>
											            @elseif( $key == 'is_response')
											                <td  class="comm_{{$key}} {{ $field['class']}}">{{ ($email->is_response)? 'Yes':'No' }}</td>
											            @elseif( $key == 'job_title')
											                <td  class="comm_{{$key}} {{ $field['class']}}">
											                	@if($email->job_id != '')
											                	<a href="{{ url('winkmum/job/profile').'/'.$email->job_id }}" target="_blank">{{ $email->job_title }}</a>
											                	@else
											                	-
											                	@endif
											                </td> 
											            @else
			                        			    	    <td class="comm_{{$key}} {{ $field['class']}}">{{ $email->{$key} }}</td>
			                        			    	@endif
			                        			    @endforeach
			                                        <td class="center">
			                                    		 <!--<a href="#" class="btn btn-xs btn-bricky tooltips btn-resend-email-log" data-placement="top" data-original-title="Resend" data-email_id="{{ $email->email_id }}"><i class="fa fa-refresh"></i></a>-->
			                                            <a href="{{ url('winkmum/sys/comms/email/get-emails/').'/'.$email->email_id }}" class="btn btn-xs btn-teal tooltips " data-placement="top" data-original-title="Email List" target="_blank"><i class="clip-stack "></i><a>
			                                        </td>
											    </tr>
											    @endforeach
											    @endif
											</tbody>
										</table>
            						</div>
                                 </div>
                                 <div id="panel_sms_log" class="tab-pane">
                                    	<div class="table-comms-wrap">
                							<table class="table table-hover table-striped table-full-width table-bordered data-table-comms">
												<thead>
													<tr>
														@foreach($sms_fields as $field)
														    <th class="{{ $field['class'] }}">{{ $field['title'] }}</th>
														@endforeach
													    <th class="center">Action</th>
													</tr>
												</thead>
												<tbody>
													@if($sms_list)
												    @foreach($sms_list as $sms)
												    <tr id="row_{{$sms->sms_id}}">
												         @foreach($sms_fields as $key=>$field)
												            @if($key == 'category')
												                <td class="comm_{{$key}} {{ $field['class']}}"><?php (isset($view_data->message_category[$sms->{$key}]['title']) ? $view_data->message_category[$sms->{$key}]['title'] : ''); ?></td>
												            @elseif($key == 'updated_at')
												                <td class="comm_{{$key}} {{ $field['class']}}">{{ date('d M Y', strtotime($sms->{$key})) }}</td>
												            @elseif( $key == 'sent')
												                <td  class="comm_{{$key}} {{ $field['class']}}"><span class="label {{ $sms->sent ==1 ? 'label-success': 'label-danger' }}">{{ $sms->sent ? 'Sent':'No' }}</span></td>
												            @elseif( $key == 'is_response')
												                <td  class="comm_{{$key}} {{ $field['class']}}">{{ ($sms->is_response)? 'Yes':'No' }}</td>
												            @elseif( $key == 'job_title')
												            	<td  class="comm_{{$key}} {{ $field['class']}}">
												            	@if( $sms->job_id != '')
												                <a href="{{ url('winkmum/job/profile').'/'.$sms->job_id }}"  target="_blank">{{ $sms->job_title }}</a>
												                @else
												                -
												                @endif
												                </td> 
												            @else
				                        			    	    <td class="comm_{{$key}} {{ $field['class']}}">{{ $sms->{$key} }}</td>
				                        			    	@endif
				                        			    @endforeach
				                                        <td class="center">
				                                            <!--<a href="#" class="btn btn-xs btn-bricky tooltips btn-resend-sms-log" data-placement="top" data-original-title="Resend" data-sms_id="{{ $sms->sms_id }}"><i class="fa fa-refresh"></i></a>-->
				                                            <a href="{{ url('winkmum/sys/comms/sms/get-sms/').'/'.$sms->sms_id }}" class="btn btn-xs btn-teal tooltips " data-placement="top" data-original-title="Sms List" target="_blank"><i class="clip-stack "></i><a>
				                                        </td>
												    </tr>
												    @endforeach
												    @endif
												</tbody>
											</table>
                						</div>
                                 </div>
                            </div>
                         </div>
				</div>
				<!--end: files panel-->	
				
				<!--start: wink central access panel-->
				<div id="panel_wc_access" class="tab-pane fade">
				    <div class="row">
				        <div class="col-sm-6">
				            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Wink Central Login</h4>
                                    <hr>
                                </div>
                            </div>
				            <div class="row">
				                <div class="col-sm-8">
				                    Login as this talent:
				                </div>
				                <div class="col-sm-4">
				                <p>
				                    @if(isset($talent_profile_model_data))
    									<a href="{{ url('winkmum/talent/login-winkcentral/'.$talent_profile_model_data->talent_id) }}" class="btn btn-info btn-block" target="_blank">Login to Wink Central</a>
    									@endif
    							</p>
    							</div>
				            </div>
				            <hr/>
				            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Wink Central Access Update</h4>
                                    <hr>
                                </div>
                            </div>
    						<div class="row">
    							<div class="col-sm-9">
    								<div class="form-group">
    									<label for="username" class="control-label">
    										Username (Email Address)
    									</label>
    									@if(isset($talent_profile_model_data)) 
    									{{
    										Form::text(
    											'',
    											(isset($talent_profile_model_data)) ? $talent_profile_model_data->email : null,
    											array(
    												'id' => 'email',
    												'class' => 'form-control',
    												'placeholder' => 'Enter Username',
    												'readonly'=>''
    											)
    										)
    									}}
    									@endif
    								</div>
    								
    							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-9">
    								<div class="form-group">
    									<label for="password" class="control-label">
    										Update Password
    									</label>
    									{{
    										Form::password(
    											'password',
    											array(
    												'id' => 'password',
    												'class' => 'form-control',
    												'placeholder' => 'Enter Password'
    											)
    										)
    									}}
    								</div>
    								
    							</div>
    						</div>
    						<hr>
    						
    						<div class="row">
    							<div class="col-md-8">
    								<p>
    								
    								</p>
    							</div>
    							<div class="col-md-4">
    								<button class="btn btn-success btn-block" type="submit">
    									Update password <i class="fa fa-arrow-circle-right"></i>
    								</button>
    							</div>
    						</div>
    					</div>
    					
    					<div class="col-sm-6">
    					</div>
    				</div>
				</div>
				<!--end: wink central access panel-->	
<div id="panel_history" class="tab-pane fade">

						<div class="tabbable tabs-left">
							<ul id="talent_history" class="nav nav-tabs tab-bricky">
								<li class="active">
									<a href="#history_jobs" data-toggle="tab">
										<i class="clip-camera"></i> Jobs
									</a>
								</li>
								<li class="">
									<a href="#history_clients" data-toggle="tab">
										<i class="clip-t-shirt"></i> Client
									</a>
								</li>
							</ul>
							<div class="tab-content client-tab-content">
								<div class="tab-pane active" id="history_jobs">
									<div class="filters">
										Filter by:&nbsp;
									</div>
									<!-- //.filters -->
												
									<!--<label for="">Enter date range</label>-->
									<div class="date-range">
										<div class="col-sm-4">
											<span class="input-icon">
												<input type="text" class="form-control daterange-picker" placeholder="Date range">
												<i class="fa fa-calendar"></i> 
											</span>
											
										</div>
										<div class="clearfix"></div>
									</div>
									<!-- //.date-range -->



									<hr>

									<h4>Job List</h4>
									<hr>
									@if(!empty($talent_job_list))			
									<!-- Table -->
									<table class="table table-striped table-bordered table-hover table-full-width talent_history_table" data-pane="#history_jobs">
										<thead>
											<tr>
											    <th>Job ID</th>
												<th class="colSort">Title</th>
											<th class="filter-select">Status</th>
											<th class="filter-select">Type</th>
											<th class="filter-select">Client</th>
												
												<th class="">Date</th>
												<th>Budget</th>
												<th class="filter-select">Job Manager</th>
												
												<th class="no-sort">&nbsp;</th>
											</tr>
										</thead>
										<tbody>
										@foreach($talent_job_list as $jobs)
											<tr>
											    <td>{{ $jobs->job_id }}</td>
												<td>{{ $jobs->job_title }}</td>
													<td>{{ $jobs->job_status_title }}</td>
													<td>{{ $jobs->job_type_title }}</td>
												<td>{{ $jobs->client_title }}</td>
												<td class="">
												@if(!empty($jobs->min_shift_date) && !empty($jobs->max_shift_date))
												{{  date_reformat($jobs->min_shift_date).' - '.date_reformat($jobs->max_shift_date) }}
												@endif
												</td>
												<td>{{ $jobs->budget }}</td>
												<td>{{ $jobs->user_first_name.' '.$jobs->user_last_name }}</td>
												
												<td class="center">
													<a href="{{ url('winkmum/job/profile/edit/'.$jobs->job_id) }}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="View Job Profile"><i class="fa fa-reply"></i></a>

												</td>
											</tr>
										@endforeach
										
										</tbody>
									</table>
								@endif
								</div>
								<div class="tab-pane" id="history_clients">

												
									<div class="filters">
										Filter by:&nbsp;
									</div>
									<!-- //.filters -->

									

									<hr>
									
									<h4>Client List</h4>
									<hr>
									<!-- Table -->
										@if(!empty($talent_client_list))
									<table class="table table-striped table-bordered table-hover table-full-width talent_history_table" data-pane="#history_talents">
										<thead>
											<tr>
												<th class="colSort">Client Name</th>
												<th class="">Last Worked</th>
												
												<th class="no-sort">Client Profile</th>
											</tr>
										</thead>
										<tbody>
									
										@foreach($talent_client_list as $client)
										<tr>
												<td class="">{{ $client->client_title }}</td>
												
											<td class="">
											@if(!empty($client->max_shift_date))
											{{	date_reformat($client->max_shift_date) }}
											@endif
											</td>
											
												<td class="center">
													<a href="{{ url('winkmum/client/profile/edit/'.$client->client_id) }}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="View Job Profile"><i class="fa fa-reply"></i></a>
												</td>
											</tr>
										@endforeach	
										</tbody>
									</table>
	@endif
								</div>
							</div>				
			</div>
		</div>
		<!--end: wink central access panel-->
        {{
            Form::close();
        }}
	</div>
</div>
<!-- end: PAGE CONTENT-->




<!-- start: CROP IMAGE MODAL -->
<div id="edit-img-modal" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title">Edit Image</h4>
	</div>
		<div class="modal-body">
        <form id="edit-image-frm">
			<div class="row">
				<div class="col-md-12">
					<h4>Crop Image</h4>
		
					<hr/>
					<div class="panel-preview">
						
						<div class="image-holder">
							<img src="" id="crop-img" alt="" width="690">
							<p class="details">
								<strong>Image Dimensions:</strong> <span class="w">2200px</span> X <span class="h">800px</span>
							</p>
						</div>
						<div></div>

						<div id="preview-pane">
							<div class="preview-container">
								<img src="" id="jcrop-preview" class="jcrop-preview" alt="Preview" />
							</div>
						</div>

						<p class="preview-label">Preview</p>
					</div>
        			<!--<div class="row" style="position:relative; z-index:9;">	
        				<div class="col-md-3">
        					<div class="btn  btn-info btn-block btn_rotate" data-rotate_val="-90" id="btn_rotate_left" ><i class="clip-rotate-2"></i> 90</div>
        				</div>
        				<div class="col-md-6"></div>
        				<div class="col-md-3">
        					<div class="btn btn-info btn-block btn_rotate" data-rotate_val="90"  id="btn_rotate_right"><i class="clip-rotate"></i> 90 </div>
        				</div>
        			</div>-->
				</div>
			</div>

			<div class="row">

				<div class="col-md-12">
					<h4>Image Information</h4>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" name="edit-img-title" id="edit-img-title" class="form-control" placeholder="Enter Image Title">
							</div>
							<div class="form-group">
								<span class="input-icon">
									<input type="text" placeholder="Enter Date" id="edit-img-date" name="edit-img-date" class="form-control img-modal-datepicker">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
							
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label class="col-sm-4 control-label">Image Status:</label>
								<div class="col-sm-8">
									<select name="img-status" id="img-status" class="form-control">
								    @foreach($view_data->image_status as $key=>$option)
									    <option value="{{ $key }}">{{ $option['title']}}</option>
									@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					
					<input type="hidden" id="rotate" name="rotate" value="0"/>
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<input type="hidden" id="r" name="r" />
					<input type="hidden" id="image_id" name="image_id" />
					<input type="hidden" name="talent_id" value="{{ (isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:'') }}" />
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type='submit' class='btn btn-blue btn-add-edit-img'>
				Save over Existing Image
			</button>
			<button type='submit' class='btn btn-green save-changes'>
				Save as New Image
			</button>
		</div>
		</form>
</div>	
<!-- end: CROP IMAGE MODAL -->

<!-- start: File MODAL -->
<div id="new-file-modal" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title">New File</h4>
	</div>

	<form action="/public/index.php/upload-secured-file" id="new-file-form" method="POST" enctype="multipart/form-data">
	   	<input type="file" class="form-control" id="add-file" name="add-file">
	   	<input type="hidden" name="id" id="talent_id" value="{{ (isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:'') }}" />
        <input type="hidden" name="type" value="talent" />
        
		<div class="modal-body">
		
			<div class="row">

				<div class="col-md-12">
					<h4>File Information</h4>
					<div class="row">
					    <div class="col-md-12">
					        <div class="form-group">
					        <strong>File Name:</strong> <span id="add_file_name"></span> <button type="button" class="btn btn-bricky btn-xs" id="btn-add-new-file-modal"><i class="fa fa-plus"></i> Select a file</button>
					        </div>
					    </div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" name="new-file-title" id="new-file-title" class="form-control" placeholder="Enter File Description">
							</div>
						</div>
						<div class="col-md-6">
								<div class="form-group">
								<span class="input-icon">
									<input type="text" placeholder="Enter Date" id="new-file-date" class="form-control file-modal-datepicker">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<!-- <button type='submit' class='btn btn-blue btn-add-new-img'>
				Save over Existing Image
			</button> -->
			<button type='submit' class='btn btn-green save-changes'>
				Save
			</button>
		</div>
		
	</form>
</div>	
<!-- end: File MODAL -->

<!-- start: CROP IMAGE MODAL -->
<div id="new-img-modal" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title">New Image</h4>
	</div>

	<form action="add-image" id="new-img-form" method="POST" enctype="multipart/form-data">
	    
	   	<input type="hidden" name="talent_id" id="talent_id" value="{{ (isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:'') }}" />
	   
		<div class="modal-body">

			<div class="row">
				<div class="col-md-12">
				    <div id="new-image-message" class="alert alert-danger" style="display:none;">
                        <ul>                                                              
                        </ul>
                    </div>
                    	<div class="alert alert-info">Only upload high resolution or larger sized images. The maximum image size allowed for upload is 2mb.</div>
                    <div class="panel-add-image-nofr" style="display:none;">
                        <div class="nofr-upload-wrap">
                        	<div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
                        		<div class="input-group">
                        			<div class="form-control uneditable-input">
                        				<i class="fa fa-file fileupload-exists"></i>
                        				<span class="fileupload-preview"></span>
                        			</div>
                        			<div class="input-group-btn">
                        				<div class="btn btn-primary btn-file">
                        					<span class="fileupload-new"><i class="fa fa-folder-open-o"></i> Select file</span>
                        					<span class="fileupload-exists"><i class="fa fa-folder-open-o"></i> Change</span>
					                        <input type="file" class="file-input" id="add-image-file" name="add-image-file" data-allowing="jpg png" data-max-size="2MB">
                        					<!--<input type="file" class="file-input" id="add-image-file" name="add-image-file" data-allowing="jpg png" data-max-size="10MB">
                        				    -->
                        				</div>
                        				<a href="#" class="btn btn-bricky fileupload-exists" data-dismiss="fileupload">
                        					<i class="fa fa-times"></i> Remove
                        				</a>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    </div>
				    <div class="panel-add-image">
				        <button type="button" class="btn btn-bricky" id="btn-add-new-img-modal">
				            <i class="fa fa-plus fa-4x"></i>
				        </button>
				        <span class="help-block">Click the button to add new image here.</span>
				    </div>

					<div class="panel-preview" style="display:none;">
						
						<div class="image-holder">
							<img src="" id="new-crop-img" alt="">
							<p class="details">
								<strong>Image Dimensions:</strong> <span class="w">2200px</span> X <span class="h">800px</span>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="row">

				<div class="col-md-12">
					<h4>Image Information</h4>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" name="new-img-title" id="new-img-title" class="form-control" placeholder="Enter Image Title">
							</div>
							<div class="form-group">
								<span class="input-icon">
									<input type="text" placeholder="Enter Date" id="new-img-date" class="form-control img-modal-datepicker">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
							
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label class="col-sm-4 control-label">Image Status:</label>
								<div class="col-sm-8">
									<select name="img-status" id="img-status" class="form-control">
									@foreach($view_data->image_status as $key=>$option)
									    <option value="{{ $key }}">{{ $option['title']}}</option>
									@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<!-- <button type='submit' class='btn btn-blue btn-add-new-img'>
				Save over Existing Image
			</button> -->
			<button type='submit' class='btn btn-green save-changes'>
				Save as New Image
			</button>
		</div>
		
	</form>
</div>	
<!-- end: CROP IMAGE MODAL -->


<!-- start: COMP CARD IMAGE MODAL -->
<div id="comp-card-modal" class="modal fade" tabindex="-1" data-width="580" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
	</div>

	<div class="modal-body">
		<div class="compcard-header">
			<h1 class="compcard-logo"></h1>

			<ul class="contact-details">
				<li>
					<span>p:</span>
					<a href="#">(02) 8005 4388</a>
				</li>
				<li>
					<span>e:</span>
					<a href="#">info@winkmodels.com.au</a>
				</li>
				<li>
					<span>w:</span>
					<a href="#">winkmodels.com.au</a>
				</li>
			</ul>
		</div>
		<!-- //.compcard-header -->
    
        <div class="compcard-wrap-border">
    		<div class="compcard-details">
    			<div class="col-4">
    				<h1 class="compcard-model">
    				    @if(isset($talent_profile_model_data))
    				        {{ ucwords(strtolower($talent_profile_model_data->first_name)) }}
    				        <Br/>
    				        {{ ucwords(strtolower($talent_profile_model_data->last_name)) }}
    				    @endif
    				</h1>
    			</div>
    
    			<div class="col-2">
                     <?php 
                        $info_arr = array();
                        foreach($view_data->compcard as $key=>$title)
                        {
                           if(isset($eav_data[$key]) &&  $eav_data[$key]['data'] != "0")
                           {
                               $info_arr[] = array('title' => $title,'data' => ucwords(strtolower($eav_data[$key]['data']))); 
                           }
                        }
                        
                        $total = count($info_arr);
                        $index = 1;
                    ?>
                    @if($total <= 4)
    				    <ul class="compcard-model-meta full">
    				@else
    				    <ul class="compcard-model-meta first">
    				@endif
    					<li>
    						<strong>Location:</strong>
    						<span>{{ isset($talent_profile_model_data)? $talent_profile_model_data->state : ''}}</span>
    					</li>
    				    @foreach($info_arr as $key=>$data)
    				     @if($index == ceil($total/2))
							</ul><ul class="compcard-model-meta second">
						 @endif
                            <li>
        						<strong>{{ $data['title']}}</strong>
        						<span>{{ ($data['title'] == 'Eye Colour' || $data['title'] == 'Hair Colour') ?  ucwords($data['data']) : strtoupper($data['data']) }}
        								@if ($data['title'] == 'Height' || $data['title'] == 'Chest' || $data['title'] == 'Waist')
        								    cm
        								@endif
        						</span>
        					</li>
        					<?php $index++; ?>
        				@endforeach
                    </ul>
    			</div>
    		</div>
		


		    <div class="modal-compcard-wrap"></div>
		</div>	
    	<!-- //.compcard-wrap-border -->						

	</div>
</div>	
<!-- end: COMP CARD IMAGE MODAL -->


<!-- start: delete-img CONFIRMATION MODAL -->
<div id="delete-img-confirmation" class="modal fade" tabindex="-1" data-width="480" style="display: none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title"><i class="fa fa-exclamation-circle bricky"></i> Notification</h4>
    </div>
    <div class="modal-body text-center">
        <p>Are you sure you want to delete this image from the list?</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-delete-img-confirm">
            Yes
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-light-grey">
            No
        </button>
    </div>
</div>  
<!-- end: delete-img CONFIRMATION MODAL -->

<!-- start: delete-img CONFIRMATION MODAL -->
<div id="delete-file-confirmation" class="modal fade" tabindex="-1" data-width="480" style="display: none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title"><i class="fa fa-exclamation-circle bricky"></i> Notification</h4>
    </div>
    <div class="modal-body text-center">
        <p>Are you sure you want to delete this file from the list?</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-delete-file-confirm">
            Yes
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-light-grey">
            No
        </button>
    </div>
</div>  
<!-- end: delete-img CONFIRMATION MODAL -->

<!-- start: succes CONFIRMATION MODAL -->
<div id="succes-confirmation" class="modal fade" tabindex="-1" data-width="480" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title"><i class="fa fa-exclamation-circle bricky"></i><i class="fa fa-check-circle success"></i> Notification</h4>
	</div>
	<div class="modal-body text-center">
	    <p>You've successfully uploaded the image.</p>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
			Close
		</button>
	</div>
</div>	
<!-- end: succes CONFIRMATION MODAL -->

<!-- start: CROP COMPCARD MODAL -->
<div id="crop-imageset-modal" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title">Crop the image</h4>
	</div>
	<div class="modal-body">
	    <div class="row">
    		<div class="col-md-12" style="text-align: right; padding: 0 8px;">
        		<button type="button" class="btn btn-success crop-imgset-save">
        			Save Crop
        		</button>
        		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
        			Close
        		</button>
        		<hr>
        	</div>
        </div>
	    
	    <div class="row">
    		<div class="col-md-12">
    
    			<div class="panel-preview">
    				
    				<div class="image-holder crop-img-modal-elem">
    					<img src="{{ asset('images/') }}/winkmum/sample.jpg" id="new-crop-img" alt="">
    					<p class="details">
    						<strong>Image Dimensions:</strong> <span class="w">2200px</span> X <span class="h">800px</span>
    					</p>
    				</div>
    			</div>
    		</div>
    	</div>
    	
    	<div id="crop-img-compcard-vals">
        	<input type="hidden" id="x" name="x" />
    		<input type="hidden" id="y" name="y" />
    		<input type="hidden" id="w" name="w" />
    		<input type="hidden" id="h" name="h" />
    		<input type="hidden" id="r" name="r" />
    		<input type="hidden" id="index" name="index"/>    		
    		<input type="hidden" id="image_id" name="image_id" />
    		<input type="hidden" name="talent_id" value="{{ (isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:'') }}" />
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-success crop-imgset-save">
			Save Crop
		</button>
		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
			Close
		</button>
	</div>
</div>	
<!-- end:CROP COMPCARD MODAL -->

<!-- start: PROFILE IMAGE MODAL -->
<div id="profile-imageset-modal" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title">Crop the image</h4>
	</div>
	<div class="modal-body">
	    <div class="row">
    		<div class="col-md-12" style="text-align: right; padding: 0 8px;">
        		<button type="button" class="btn btn-success crop-imgset-save">
        			Save Crop
        		</button>
        		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
        			Close
        		</button>
        		<hr>
        	</div>
        </div>
	    
	    <div class="row">
    		<div class="col-md-12">
    
    			<div class="panel-preview">
    				
    				<div class="image-holder crop-img-modal-elem">
    					<img src="{{ asset('images/') }}/winkmum/sample.jpg" id="new-crop-img" alt="">
    					<p class="details">
    						<strong>Image Dimensions:</strong> <span class="w">2200px</span> X <span class="h">800px</span>
    					</p>
    				</div>
    			</div>
    		</div>
    	</div>
    	
    	<div id="crop-img-compcard-vals">
        	<input type="hidden" id="pimg_x" name="pimg_x" />
    		<input type="hidden" id="pimg_y" name="pimg_y" />
    		<input type="hidden" id="pimg_w" name="pimg_w" />
    		<input type="hidden" id="pimg_h" name="pimg_h" />
    		<input type="hidden" id="pimg_r" name="pimg_r" />
    		<input type="hidden" id="pimg_talent_id" name="pimg_talent_id" value="{{ (isset($talent_profile_model_data)? $talent_profile_model_data->talent_id:'') }}" />
    		<input type="hidden" id="pimg_file_id" name="pimg_file_id" />
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-success crop-imgset-save">
			Save Crop
		</button>
		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
			Close
		</button>
	</div>
</div>	
<!-- end:PROFILE IMAGE MODAL -->

@stop