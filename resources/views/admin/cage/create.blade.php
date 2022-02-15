@extends('layouts.admin.master')

@section('title')
	{{trans('cage.new_cage')}}	
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/mycss.css') }}">
     <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')

@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>{{trans('cage.new_cage')}}</h3>
		@endslot
		<li class="breadcrumb-item">{{trans('cage.cage')}}</li>
		<li class="breadcrumb-item active">{{trans('cage.add_cage')}}</li>
	@endcomponent
	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
			
			@if ($errors->any())
			<div class="alert alert-danger dark alert-dismissible fade show" role="alert">{{trans('couple.failed_to_create_couple')}}
                      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
                    </div>
			@endif
	            <div class="">
	                <div class="card">
	                    <div class="card-body">
	                        <form class="theme-form profile-form" method="post" enctype="multipart/form-data" action="{{ route('cage.save') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />    
								<div class="row mb-2">
	                                <div class="profile-title">
	                                    <div class="media" style="position: relative;">
											<img class="img-70 rounded-circle" alt="" 
												src = ""
												onerror="onErrorImage(this)"
												id="profileDisplay"  />
											<a href="javascript:triggerClick()"><i class="fa fa-pencil circle-icon"
											></i></a>
											@if ($errors->has('profileImage'))
                                    			<div><span class="text-danger text-left">{{ $errors->first('profileImage') }}</span></div>
                                    		@endif
												<input type="file" name="profileImage" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">
											
	                                    </div>
	                                </div>
	                            </div>
								<div class="mb-3">
	                                <label class="form-label">{{trans('parrot.name')}}</label>
	                                <input class="form-control" name="name" maxlength=30 placeholder = "{{trans('cage.friendly_name_of_cage')}}" value="{{old('name')}}" />
									@if ($errors->has('name'))
                                    	<div><span class="text-danger text-left">{{ $errors->first('name') }}</span></div>
                                    @endif
								</div>
								    
								<div class="mb-3">
	                            	<label class="form-label">Dimensioni</label>

								    <div class="input-group mb-3 ms-3"><span class="input-group-text">cm</span>
										<input class="form-control " name="width" type="number"  min=1 value="{{old('width')}}" >
										<span class="input-group-text">&nbsp;larghezza</span>
									</div>
									<div class="input-group mb-3 ms-3"><span class="input-group-text">cm</span>
	                                	<input class="form-control" name="height" type="number" min=1 value="{{old('height')}}" >
										<span class="input-group-text">&nbsp;&nbsp;&nbsp;{{'altezza'}}&nbsp;&nbsp;&nbsp; </span>
									</div>
									<div class="input-group mb-3 ms-3"><span class="input-group-text">cm</span>
	                                
										<input class="form-control " name="depth" type="number"  min=1 value="{{old('depth')}}" >									
										<span class="input-group-text">profondità</span>
									</div>
								</div>

								<div class="mb-3">
	                            	<label class="form-label">Quanti pappagalli può contenere la gabbia?</label>
									<input class="form-control " name="max_parrot" type="number"  value="{{old('max_parrot',1)}}" min=1 >
								</div>
								
								<div class="mb-3">
									<label class="form-label">{{trans('couple.note')}}</label>
									<textarea class="form-control" name="note" rows="3" maxlength=1000></textarea>
								</div>
								<div class="mb-3">	
									<input  type="checkbox"  name="possibility_add_parrot" value="1" >
									<label class="text-muted" for="possibility_add_parrot" >Aggiungi pappagalli al termine della creazione</label>
								</div>	
	                            <div class="form-footer">
	                                <button class="btn btn-primary btn-block">{{trans('parrot.save')}}</button>
	                            </div>
	                        </form>
	                    </div>
	                </div>
	            </div>
	           
	        </div>
	    </div>
	</div>
	
	@push('scripts')
	<script>
		$(document).ready(function(){

				$('select[name=male_id]').select2({lang:'it'});
				$('select[name=female_id]').select2({lang:'it'});
				$('input[name=birth_date_of_couple]').datepicker({
				language: 'en',
				dateFormat: 'mm/dd/yyyy',
					maxDate: new Date() // Now can select only dates, which goes after today
				})

				$('input[name=expected_date_of_birth]').datepicker({
					language: 'en',
					dateFormat: 'mm/dd/yyyy',
					minDate: new Date() // Now can select only dates, which goes after today
				})


				$('#couple_made_today').click(function() {
					if ($(this).is(':checked')) {
						// $('input[name=birth_date_of_couple]').datepicker('setDate','12/12/2022');
						var now = new Date();
						var dateString = moment(now).format('MM/DD/YYYY');

						$('input[name=birth_date_of_couple]').val(dateString);
						
						
					}
				});

			});

	</script>
	<script src="{{ asset('assets/js/cage/cage.js') }}"></script>

	@endpush

@endsection