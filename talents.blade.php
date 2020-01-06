@section('content')

<div class="row">
<div class="col-sm-12">
<a class="btn btn-bricky btn-margin-bot pull-right" href="{{url('winkmum/talent/profile') }}">
<i class="fa fa-plus-circle"></i>
  Add New Talent
</a>
</div>
</div>

<!-- start: TABLE FILTERS PANEL -->
{{ $filter_panel }}
<!-- end: TABLE FILTERS PANEL -->

<!-- start: DYNAMIC TABLE PANEL -->
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="clip-stats"></i>
        Talent List
        <div class="panel-tools">
            <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
            </a>
        </div>
    </div>
    <div class="panel-body">
        <!-- <div class="responsive-table-wrap"> -->
            <table class="table table-striped table-hover table-bordered table-full-width responsive-table responsive-sticky" data-images="{{ asset('images') }}/winkmum/" data-talents="{{ asset('lib')}}/json-talents.php" id="talent_list">
                <thead>
                    <tr>
                        <th class="center">Photo</th>
        				@foreach($fields as $field)
        				<th class="center">{{ $field['title']}} </th>
        				@endforeach
        				 <th class="center">Alerts</th>
        				<th class="no-sort">Actions</th>
        			</tr>
                </thead>
                <tbody>
					
					@foreach($talents['data'] as $talent)
                    <tr id="row-{{ $talent['talent_id'] }}">
                         <!-- start: table display -->
                          <th class="center">
                            <a href="{{url('winkmum/talent/profile/edit/'.$talent['talent_id']) }}">
							@if( isset($talent['file_name']) && $talent['file_name'] != '')
							<img src="{{ URL::asset($talent['file_path'].$talent['file_name'].'_thumbnail.'.$talent['file_ext']) }}" width="50px" height="50px"/>
							@else
							<img src="{{ URL::asset('images/default_profile_pic_thumbnail.jpg') }}" width="50px" height="50px"/>
							@endif
							</a>
                        </th>
        			    @foreach($fields as $key=>$field)
        			        @if( $key == 'first_name'  || $key == 'last_name' )
        			            <td class="talent_{{$key}} {{ $field['class']}}"><a href="{{url('winkmum/talent/profile/edit/'.$talent['talent_id']) }}">{{ $talent[$key] }}</a></td>
        			        @elseif( $key == 'dob')
        			             <td class="talent_{{$key}} {{ $field['class']}}">{{ $talent['ageInYears'] }}</td>
        			        @elseif( $key == 'email')
        			             <td class="talent_{{$key}} {{ $field['class']}}">{{ $talent[$key] }}</td>
        			        @elseif ( $key == 'gender')
        			            <td class="talent_{{$key}} {{ $field['class'] }}">
    			                @if($talent[gender] == 1) 
    			                    Male 
    			                @elseif( $talent[gender] == 2)
    			                    Female
    			                @endif
    			                </td>
    			            @elseif( $key == 'rating')
								 <td class="talent_{{$key}} {{ $field['class']}}">{{ $view_data->rating[$talent[$key]] }}</td>
							@elseif( $key == 'height')
								 <td class="talent_{{$key}} {{ $field['class']}}">{{ $talent[$key]['data']. 'cm - '. get_inch($talent[$key]['data']) }}</td>
    			            @elseif( $key == 'availability')
    			                <td class="talent_{{$key}} {{ $field['class'] }}">
    			                @if( !empty($talent['availability']) )
    			                     <span class="badge badge-warning popovers" data-trigger="click" data-placement="top" data-original-title="Talent Not Available"><i class="{{ $talent['availability']['type'] == 0 ? 'icon-plane':'icon-coffee' }}"></i></span>
    			                @else
    			                    <span class="badge badge-success"><i class="clip-checkmark-2"></i></span>
    			                @endif
    			                </td>
        			        @else
        			    	    <td class="talent_{{$key}} {{ $field['class']}}">{{ ucwords(is_array($talent[$key]) ? $talent[$key]['data'] : $talent[$key] )}}</td>
        			    	@endif
        			    @endforeach
        			    <td class="center"> 
                            <ul class="talent-misc-list">
                         @if($talent['wc_modified'] != null)
                            <li><a class="btn btn-orange btn-xs" href="talent/profile/edit/{{ $talent['talent_id']}}"><i class="clip-wrench-2"></i>&nbsp;&nbsp;{{count(unserialize($talent['wc_modified']))}}</a></li>
                         @endif
                         
                         @if($talent['img_expired'] > 0)
                            <li><a class="btn btn-bricky btn-xs" href="talent/profile/edit/{{ $talent['talent_id']}}#panel_image"><i class="icon-camera"></i>&nbsp;&nbsp;{{ $talent['img_expired'] }}</a></li>
                         @endif
                         @if($talent['img_review'] > 0)
                            <li><a class="btn btn-warning btn-xs" href="talent/profile/edit/{{ $talent['talent_id']}}#panel_image"><i class="clip-images"></i>&nbsp;&nbsp;{{ $talent['img_review'] }}</a></li>
                         @endif
                         </ul>
                         </td>
						<td class="center">
        					<div class="">
        						<a href="talent/calendar/{{ $talent['talent_id'] }}" class="btn btn-xs btn-info tooltips todo-view" data-placement="top" data-original-title="View Calendar" data-talent_id="{{ $talent['talent_id'] }}"><i class="fa icon-calendar "></i></a>
        						<a href="talent/profile/edit/{{ $talent['talent_id'] }}" class="btn btn-xs btn-teal tooltips talent-update" data-placement="top" data-original-title="Edit" data-talent_id="{{ $talent['talent_id'] }}"><i class="fa fa-edit"></i></a>
        						
        					</div>
        					
        				</td>
                    </tr>
					@endforeach
                </tbody>
            </table>
        <!-- </div> -->
    	<!-- //.table-responsive -->
		<!-- start: output pagination -->
		<div class="row">
			<div class="col-md-6">
    		    <div class="dataTables_perpage">
    			         {{
        			        Form::select(
        			            'p',
        			            $view_data->perpage,
        			            Session::has('p')? Session::get('p') : $view_data->paginate,
        			            array(
        			                'class' =>  'form-control perpage-control',
        			                'id'    =>  'p',
        			                'data-url'	=> build_url(Request::url(),Input::except('page'))
        			            )
        			        )
        			     }}
        		</div>
			</div>
			<div class="col-md-6">
				 {{ build_dropdown_pagination($talents['last_page'],$talents['current_page'],Request::url(),Input::except('page')); }}
			</div>
	   </div>
	   <!-- end: output pagination -->
    </div>
</div>
<!-- end: DYNAMIC TABLE PANEL -->




<!-- start: delete-talent CONFIRMATION MODAL -->
<div id="delete-talent-confirmation" class="modal fade" tabindex="-1" data-width="480" style="display: none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title"><i class="fa fa-exclamation-circle bricky"></i> Notification</h4>
    </div>
    <div class="modal-body text-center">
        <p>Are you sure you want to delete the talent from the list?</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-delete-talent-confirm">
            Yes
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-light-grey">
            No
        </button>
    </div>
</div>  
<!-- end: delete-talent CONFIRMATION MODAL -->

<!-- start: succes CONFIRMATION MODAL -->
<div id="succes-confirmation" class="modal fade" tabindex="-1" data-width="480" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title"><i class="fa fa-exclamation-circle bricky"></i><i class="fa fa-check-circle success"></i> Notification</h4>
	</div>
	<div class="modal-body text-center">
	    <p>You've successfully deleted the talent from the list.</p>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
			Close
		</button>
	</div>
</div>	
<!-- end: succes CONFIRMATION MODAL -->
@stop