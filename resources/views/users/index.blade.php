@extends('layouts.default')
@section('content')

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.USER_MANAGEMENT')!!}
            </h2>
        </div>
         @include('layouts.flash')
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading hbuilt">
                   <?php  if(!empty(Session::get('acl')[3][2])){ ?>
                    <div class="pull-right">
                        <a class="btn btn-info btn-effect-ripple" href="{!! URL::to('users/create') !!}"><i class="fa fa-plus"></i> {!!trans('english.CREATE_A_USER')!!}</a>
                    </div>
                   <?php } ?>
                    <h4>{!!trans('english.USER_MANAGEMENT')!!}</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('role' => 'form', 'url' => 'users/filter', 'class' => 'form-horizontal', 'id' => 'accountHeadCreate')) !!}
                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                {!! Form::text('username', old('username'), array('id'=> 'username', 'class' => 'form-control','placeholder'=>'Username')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="col-md-12">
                            {!!Form::select('userRole', $userRole, old('userRole'), array('class' => 'form-control js-source-states', 'id' => 'userRole'))!!}
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="col-md-12">
                            {!!Form::select('status',['0'=>'Select Status','1'=>'Active','2'=>'Inactive'], old('userRole'), array('class' => 'form-control js-source-states', 'id' => 'userRole'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-5">
                            <button type="submit" class="btn btn-primary"> {!!trans('english.FILTER')!!}</button>
                        </div>
                    </div>

                    {!!Form::close()!!}

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped middle-align">
                            <thead>
                                <tr class="center">
                                    <th>{!!trans('english.SL_NO')!!}</th>
                                    <th>{!!trans('english.USERNAME')!!}</th>
                                    <th>{!!trans('english.USER_ROLE')!!}</th>
                                    <th>Full name</th>

                                    <th class='text-center'>Email</th>
                                    <th class='text-center'>Contact</th>
                                    {{--<th class='text-center'>Operator</th>--}}
                                    <th class='text-center'>Zones</th>
                                    <th class='text-center'>Organization</th>
                                    <th class='text-center'>Categories</th>
                                    <th class='text-center'>Rank</th>
                                    <th class='text-center'>Designation</th>
                                    <th>{!!trans('english.STATUS')!!}</th>
                                    <?php  if(!empty(Session::get('acl')[3][3]) || !empty(Session::get('acl')[3][4]) || !empty(Session::get('acl')[3][7])){ ?>
                                    <th class='text-center'>{!!trans('english.ACTION')!!}</th>
                                    <?php  } ?>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$usersArr->isEmpty())
                                <?php
                                    $sl=1;

                                    function categoriesName($id){
                                     $supplyCatName = \App\SupplyCategory::where('id',$id)->value('name');
                                     return $supplyCatName;
                                    }
                                ?>
                                @foreach($usersArr as $value)

                                <tr class="contain-center">
                                    <td>{!!$sl++ !!}</td>
                                    <td>{!! $value->username !!}</td>
                                    <td>{!!$value->Role['name']!!}</td>
                                    <td>{!! ucfirst($value->first_name) !!} {!! ucfirst($value->last_name) !!}</td>
                                    <td>{!! $value->email !!}</td>
                                    <td>{!! $value->contact_no !!}</td>
                                    {{--<td>{!! $value->operator_id !!}</td>--}}
                                    <td>
                                        <?php
                                        $zoneids = explode(',',$value->zones);
                                        foreach ($zoneids as $zids) {
                                            $valsss = \App\Http\Controllers\RegistredNsdNameController::zone_name($zids);
                                            echo "<li>".$valsss."</li>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $nsdbsdids = explode(',',$value->nsd_bsd);
                                        foreach ($nsdbsdids as $nbids) {
                                            $valsss = \App\Http\Controllers\UsersController::nsd_bsd_name($nbids);
                                            echo "<li>".$valsss."</li>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $suppCatIds = explode(',',$value->categories_id);
                                        foreach ($suppCatIds as $sci) {
                                            $valsssss = categoriesName($sci);
                                            echo "<li>".$valsssss."</li>";
                                        }
                                        ?>
                                    </td>
                                    <td>{!! $value->rank !!}</td>
                                    <td>{!! $value->designation !!}</td>
                                    <td>
                                        @if ($value->status_id == '1')
                                        <span class="label label-success">{!!trans('english.ACTIVE')!!}</span>
                                        @else
                                        <span class="label label-warning">{!!trans('english.INACTIVE')!!}</span>
                                        @endif
                                    </td>
                                    <?php  if(!empty(Session::get('acl')[3][3]) || !empty(Session::get('acl')[3][4]) || !empty(Session::get('acl')[3][5]) || !empty(Session::get('acl')[3][7])){ ?>
                                    <td class="action-center">
                                        <div class='text-center'>
                                            {!! Form::open(array('url' => 'users/' . $value->id)) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}

                                            <?php  if(!empty(Session::get('acl')[3][11])){ ?>
                                            <a class='btn btn-info btn-xs' href="{!! URL::to('users/activate/' . $value->id ) !!}@if(isset($param)){!!'/'.$param !!} @endif" data-rel="tooltip" title="@if($value->status_id == '1') Inactivate @else Activate @endif" data-placement="top">
                                                @if($value->status_id == '1')
                                                <i class='fa fa-remove'></i>
                                                @else
                                                <i class='fa fa-circle-o'></i>
                                                @endif
                                            </a>
                                            <?php  } ?>
                                            <?php  if(!empty(Session::get('acl')[3][3])){ ?>
                                            <a class='btn btn-primary btn-xs' href="{!! URL::to('users/' . $value->id . '/edit') !!}">
                                                <i class='fa fa-pencil'></i>
                                            </a>
                                            <?php  } ?>
                                            <?php  if(!empty(Session::get('acl')[3][7])){ ?>
                                            <a href="{!! URL::to('users/cp/' . $value->id) !!}@if(isset($param)){!!'/'.$param !!} @endif" data-original-title="Change Password">
                                                <span class="btn btn-success btn-xs"> 
                                                    <i class="fa fa-key"></i>
                                                </span>
                                            </a>
                                            <?php  } ?>
                                            <?php  if(!empty(Session::get('acl')[3][4])){ ?>
                                            <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class='fa fa-trash'></i>
                                            </button>
                                            <?php } ?>

                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                    <?php  } ?>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="9">{!!trans('english.EMPTY_DATA')!!}</td>
                                </tr>
                                @endif 
                            </tbody>
                        </table><!---/table-responsive-->
                        {!! $usersArr->render()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop