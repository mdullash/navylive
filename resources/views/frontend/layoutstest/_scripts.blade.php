<script type="text/javascript" src="{!! asset('public/frontend/js/select2.min.js')!!}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.your-class').slick({
            autoplay: true,
            infinite: true,
            speed: 300,
            slidesToScroll: 1,
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.openinon-class').slick({
            autoplay: true,
            infinite: true,
            speed: 300,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
        });
    });
</script>
<script>
    $( function() {
        $( "#tabs" ).tabs();
    } );
</script>

<script>
    $(function () {
        function searchResult($i){
            $(".searchResult"+$i).mouseover(function () {
                $("#searchValue").val($(".searchResult"+$i).text());
            });
        }


        $("#searchValue").keyup(function () {
            $('.removeClass').remove();
            var searchValue=$("#searchValue").val();
            var url='{!! URL::to('getSearchValue') !!}'+'/'+searchValue;
            var $i=1;
            $.ajax({
                type: "GET",
                url:url,
                success:
                    function (data) {
                        $.each(data['name'], function(index, singleObj){
                            $('.result').append('<div class="removeClass"> <p class="searchResult'+$i+'"><a href="{!! url('/?product_name=') !!}'+singleObj.name+'"> '+singleObj.name+'</a></p></div>');
                            searchResult($i);
                            $i++;
                        });

                    }
            });
            return false;
        });
    });

    $(document).on('ready', function() {
        $('.regular').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {

                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
        $('.regular2').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

    });
</script>
<script>

    $(document).ready(function($) {
        $( "#registration").mouseover(function() {
            var base = "880";
            var regex = new RegExp("^" + base, "i");
            $('.query').on("keyup paste", function(ev) {
                var query = $(this).val();
                if (!regex.test(query)) {
                    //ev.preventDefault();
                    $(this).val(base);
                }
            });
            $('#alreadySignup').click(function (){
                $('#login').modal('show');
                $('#registration').modal('hide');
            });

            $('#forgetpass').click(function (){
                $('#login').modal('hide');
                $('#forgetpassword').modal('hide');
            });

            $('.submit').on("click",function() {

                $('.removeMobileMessage').remove();
                $('.removenameMessage').remove();
                $('.removeemailMessage').remove();
                $('.removepasswordMessage').remove();
                $('.removeconfirm_passwordMessage').remove();
                $("#name").prop('required',true);
                $("#email").prop('required',true);
                $("#password").prop('required',true);
                $("#confirm_password").prop('required',true);

                if($('#mobile').val() == 880){

                    $('.mob').append('<div class="removeMobileMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{
                    $('.removeMobileMessage').remove();
                }

                if($('#name').val() == ''){

                    $('.name').append('<div class="removenameMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{
                    $('.removenameMessage').remove();
                }

                if($('#email').val() == ''){

                    $('.emailMessage').append('<div class="removeemailMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{

                    $('.removeemailMessage').remove();
                }


                if($('#password').val() == ''){

                    $('.password').append('<div class="removepasswordMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{

                    $('.removepasswordMessage').remove();
                }

                if($('#confirm_password').val() == ''){

                    $('.confirm_password').append('<div class="removeconfirm_passwordMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{

                    $('.removeconfirm_passwordMessage').remove();
                }

                if($('#district_id').val() == 0){

                    $('.dist').append('<div class="removedistMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{
                    $('.removedistMessage').remove();
                }

            });

            $(document).on("keyup", '#mobile', function () {
                $('.removeMobileMessage').remove();
                if($('#mobile').val()!=880){
                    $('.removeMobileMessage').remove();
                }else{
                    $('.mob').append('<div class="removeMobileMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');

                }

            });


            $(document).on("keyup", '#name', function () {

                $('.removenameMessage').remove();
                if($('#name').val()!=''){
                    $('.removenameMessage').remove();
                }else{
                    $('.name').append('<div class="removenameMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');

                }

            });


            $(document).on("keyup", '#email', function () {
                $('.removeemailMessage').remove();
                if($('#email').val()!=''){
                    $('.removeemailMessage').remove();
                }else{
                    $('.email').append('<div class="removeemailMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');

                }

            });

            $(document).on("keyup", '#password', function () {
                $('.removepasswordMessage').remove();
                if($('#password').val()!=''){
                    $('.removepasswordMessage').remove();
                }else{
                    $('.password').append('<div class="removepasswordMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');

                }

            });

            $(document).on("keyup", '#confirm_password', function () {
                $('.removeconfirm_passwordMessage').remove();
                if($('#confirm_password').val()!=''){
                    $('.removeconfirm_passwordMessage').remove();
                }else{
                    $('.confirm_password').append('<div class="removeconfirm_passwordMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');

                }

            });

            $(document).on("change", '#district_id', function () {

                $('.removedistMessage').remove();
                if($('#district_id').val() == 0){
                    $('.dist').append('<div class="removedistMessage" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">This is a required field</span></div>');
                }else{
                    $('.removedistMessage').remove();
                }
            });

            $(document).on("focusout", '#email', function () {
                var email_address = $('#email').val();

                var email_regex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
                if(!email_regex.test(email_address)){
                    $('.removeEmail').remove();
                    $('.emailMessage').append('<div class="removeEmail" style="width:100%;text-align:center;"><span class="help-block form-error" style="color:#a94442">Please insert a valid email address.</span></div>')

                }else{

                    $('.removeEmail').remove();

                }
                if(email_address == ''){
                    $('.removeEmail').remove();
                }
            });

        });

    });


</script>
@yield('js')
