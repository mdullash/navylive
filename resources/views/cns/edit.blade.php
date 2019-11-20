@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    CNS Image
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>

    <div class="content animate-panel">
        <div class="row">
            <div class="col-sm-6 col-md-12">
                <div class="hpanel">
                    <div class="panel-heading sub-title">
                        CNS Image Upload
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'cns-image', 'files'=> true, 'class' => 'form-horizontal items', 'id'=>'items')) }}
                    <div class="row">
                        <div class="form-group col-md-8 col-sm-8 col-md-offset-3 col-sm-offset-3" style="margin-left: 8%;">
                           <div class="upload_files" style="width: 30%;margin-left: 57%;">
                                <label >
                                    <img src="" id="profile-img-tag1" style="display: none;width: 70%;" /><br>
                                    <input type="file" accept="image/png, image/jpeg,image/jpg" class="profile-img1" name="image"style="display:none;"/>
                                    @if(!empty($setting->logo))
                                    <img src="{{ asset($setting->logo) }}" alt="" id="placehold_img1" style="width: 70%;margin-left: 10%;"/>
                                        <p>Click this image to change</p>
                                    @else
                                    <img src="{{ asset("/public/fileupload.png") }}" alt="" id="placehold_img1" style="width: 70%;margin-left: 10%;"/>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-7" style="margin-left: 2%;">
                                <a href="{{URL::previous()}}" class="btn btn-danger cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                
                            <button type="submit" class="btn btn-success pull-right">{!! "Send" !!}</button>
                        </div>
                    </div>    
    
                    <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
    "use strict";   
    //Display Uploaded image1
    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();              
            reader.onload = function (e) {
                $('#profile-img-tag1').show();
                $('#profile-img-tag1').attr('src', e.target.result);
                $('#placehold_img1').hide();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".profile-img1").change(function(){
        readURL1(this);
    });
    
});
</script>
@stop


