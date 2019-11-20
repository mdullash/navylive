jQuery(document).ready(function($) {
    'use strict';

  //News Update
    $(function() {
        $('.navy_news_update').jConveyorTicker({
            anim_duration: 200,
            reverse_elm: true,
            force_loop:true
        });
    });

    //Navigation
    if ($("#navigation").length) {
        $("#navigation").menumaker({
            title: "Menu",
            format: "multitoggle"
        });
    }
    //Responsive Datatable
    var table = $('.tenderTable').DataTable( {
        fixedHeader: true
    } );

    /* Calender jQuery **/

    if ($("#weddingdate,#businessStartDate,#contractDate,#regDate,#joinDate,#offenceDate,#spouseBirthDate, #taskdate, #birthDate").length) {

        $("#weddingdate,#businessStartDate,#contractDate,#regDate,#joinDate,#offenceDate,#spouseBirthDate, #taskdate, #birthDate").datepicker({  });
        $('#ui-datepicker-div').before('<div class="default-skin"></div>');
        $('#ui-datepicker-div').appendTo('.default-skin').contents();
    }
    //Preloader
    $(window).on ('load', function (){
        $("#spinningSquaresG1").delay(2000).fadeOut(500);
        $(".inTurnBlurringTextG").on('click',function() {
            $("#spinningSquaresG1").fadeOut(500);
        });
      });

    /*--- select option effect ----*/

    if ($('select').length) {
        $(document).ready(function() {
            $('select').niceSelect();
            FastClick.attach(document.body);
        });

    }

    /* Multiple Carousel **/
    if ($('.owl-venue-thumb, .owl-second, .owl-slider, .post-owl').length) {
        $('.owl-venue-thumb').owlCarousel({
            loop: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 3000,
            Item_Width: 100,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 3,
                },
                1200: {
                    items: 3,
                },
                1400: {
                    items: 3,
                },
                1600: {
                    items: 3,
                },
                1800: {
                    items: 3,
                }
            }
        });


        $('.owl-second').owlCarousel({

            loop: true,
            margin: 5,
            autoplay: true,
            autoplayTimeout: 3000,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 4
                }
            }
        });

        $('.owl-slider').owlCarousel({

            loop: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 3000,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });


        $('.post-owl').owlCarousel({

            loop: true,
            margin: 5,
            autoplay: true,
            autoplayTimeout: 3000,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });



    }

    /* POP UP Gallery jQuery **/


    if ($('#open-popup').length) {


        $('#open-popup').magnificPopup({
            items: [{
                    src: 'images/wedding-gallery-img-1.jpg',
                    title: 'Venue locations #1'
                },
                {
                    src: 'images/wedding-gallery-img-2.jpg',
                    title: 'Venue locations #2'
                },

                {
                    src: 'images/wedding-gallery-img-3.jpg',
                    title: 'Venue locations #3'
                },


            ],
            gallery: {
                enabled: true
            },
            type: 'image' // this is a default type
        });

    }
    /* accordions jQuery **/

    if ($('.collapse').length) {

        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
        });

        $('.panel-heading a').click(function() {
            $('.panel-heading').removeClass('active');

            //If the panel was open and would be closed by this click, do not active it
            if (!$(this).closest('.panel').find('.panel-collapse').hasClass('in'))
                $(this).parents('.panel-heading').removeClass("fa-plus").addClass('active');
        });

    }
    /* dashboard side menu show and open jQuery  **/
    if ($('#icon-toggle').length) {

        $(function() {
            var plus = $('#icon-toggle');
            var btn = $('button')
            $("button").click(function() {
                plus.toggleClass("fa fa-times fa fa-bars");
            });
        });





    }
    /* dashboard slide panel jQuery  **/
    if ($('#slide-panel').length) {

        $('#slide-panel').slideReveal({
            trigger: $("#trigger"),
            position: "right",
            push: false,
            overlay: true,
            width: 275,
            speed: 450,
             overlay: true
        });




    }
    /* dashboard summernote jQuery  **/
    if ($('#summernote').length) {

        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300

            });
        });


    }
}); // AND OF JQUERY
