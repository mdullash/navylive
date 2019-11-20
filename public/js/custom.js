$('.delete').on('click', function (e) {
    e.preventDefault();
    var form = $(this).parents('form');
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm)
            form.submit();
    });
});

$(".cancel").click(function () {
    history.go(-1);
});

$(".js-source-states").select2();
$(".js-source-states-2").select2();

$('.datapicker2').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
});

// $('.datapicker4').datepicker({

// format: 'yyyy-mm-dd',
// endDate: '+0m',
// autoclose: true, 
// onSelect: function(dateText, inst) {
// alert("adf");
// }
// });

$('.datapicker4').datepicker({
    format: 'yyyy-mm-dd',
    endDate: '+0m',
    autoclose: true,
}).on('changeDate', function (ev) {
    showExprience(ev);
});

$(".interger-decimal-only").each(function () {
    $(this).keypress(function (e) {
        var code = e.charCode;

        if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
            return true;
        } else {
            return false;
        }
    });
});

function showExprience(ev) {
    var dt1 = ev.date;
    var dt2 = new Date();
    var ret = [{days: 0, months: 0, years: 0}];

    if (dt1 == dt2)
    {
        ret;
    }

    if (dt1 > dt2)
    {
        var dtmp = dt2;
        dt2 = dt1;
        dt1 = dtmp;
    }
    var year1 = dt1.getFullYear();
    var year2 = dt2.getFullYear();
    var month1 = dt1.getMonth();
    var month2 = dt2.getMonth();

    var day1 = dt1.getDate();
    var day2 = dt2.getDate();

    ret['years'] = year2 - year1;
    ret['months'] = month2 - month1;
    ret['days'] = day2 - day1;

    if (ret['days'] < 0)
    {
        var dtmp1 = new Date(dt1.getFullYear(), dt1.getMonth() + 1, 1, 0, 0, -1);

        var numDays = dtmp1.getDate();

        ret['months'] -= 1;
        ret['days'] += numDays;
    }

    if (ret['months'] < 0)
    {
        ret['months'] += 12;
        ret['years'] -= 1;
    }
    $("#joining").html("ISSL Experience: " + ret.years + "  Year " + ret.months + " Month " + ret.days + " Day");
    //console.log(ret);
}


$(".datapicker3").datepicker({
    format: "mm-yyyy",
    startView: "months",
    minViewMode: "months",
    autoclose: true,
});

// Toastr options
toastr.options = {
    "debug": false,
    "newestOnTop": false,
    "positionClass": "toast-top-center",
    "closeButton": true,
    "debug": false,
            "toastClass": "animated fadeInDown",
};

$(".datepicker").datepicker({
    format: "mm-yyyy",
    endDate: '+0m',
    startView: "months",
    minViewMode: "months"
});

$(document).on("changeDate", ".datepicker", function (event) {
    alert('test');
    $(".my_hidden_input").val($(".datepicker").datepicker('getFormattedDate'));
});

$('.supplierCategory').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        name: {
            validators: {
                notEmpty: {
                    message: "Category Name is required "
                }
            }
        },
        'zones[]': {
            validators: {
                notEmpty: {
                    message: "Zone Name is required "
                }
            }
        },
        
    }
});

$('.denos').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        name: {
            validators: {
                notEmpty: {
                    message: "Category Name is required "
                }
            }
        },
        
    }
});

$('.registred_nsd_name').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        name: {
            validators: {
                notEmpty: {
                    message: "Category Name is required "
                }
            }
        },
        'zones[]': {
            validators: {
                notEmpty: {
                    message: "Zone Name is required "
                }
            }
        },
        
    }
});

$('.suppliers').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        company_name: {
            validators: {
                notEmpty: {
                    message: "Company Name is required "
                }
            }
        },
        mobile_number: {
            validators: {
                notEmpty: {
                    message: "Mobile Number is required "
                }
            }
        },
        vat_registration_number: {
            validators: {
                notEmpty: {
                    message: "Vat Registration Number is required "
                }
            }
        },
        nid_number: {
            validators: {
                notEmpty: {
                    message: "NID Number is required "
                }
            }
        },
        bank_account_number: {
            validators: {
                notEmpty: {
                    message: "Bank Account Number "
                }
            }
        },
        bank_name_and_branch: {
            validators: {
                notEmpty: {
                    message: "Bank Branch "
                }
            }
        },
        date_of_enrollment: {
            validators: {
                notEmpty: {
                    message: "Date Of Enrollment "
                }
            }
        },
        company_regi_number_nsd: {
            validators: {
                notEmpty: {
                    message: "Company Reg. Number "
                }
            }
        },
        supply_cat_id: {
            validators: {
                notEmpty: {
                    message: "Supply Category "
                }
            }
        },
        tin_number: {
            validators: {
                notEmpty: {
                    message: "TIN Number "
                }
            }
        },
        trade_license_number: {
            validators: {
                notEmpty: {
                    message: "Trade License Number "
                }
            }
        },
        company_bank_account_name: {
            validators: {
                notEmpty: {
                    message: "Company Bank Account "
                }
            }
        },
        'registered_nsd_id[]': {
            validators: {
                notEmpty: {
                    message: "Registered NSD Name "
                }
            }
        },
        // bsti_certification: {
        //     validators: {
        //         notEmpty: {
        //             message: "BSTI Certification"
        //         }
        //     }
        // },
        // iso_certification: {
        //     validators: {
        //         notEmpty: {
        //             message: "ISO Certification "
        //         }
        //     }
        // },
        // password: {
        //     validators: {
        //         notEmpty: {
        //             message: "Password Name is required "
        //         },
        //         stringLength: {
        //             min: 6,max: 16,message: 'Password range between 6 to 16 characters'
        //         }
        //     }
        // },
        //  email: {
        //     validators: {
                
        //         email: {
        //                 message: 'Email should contain @ symbol ' 
        //             },
        //         regexp: {
        //                 regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
        //                 message: 'Enter valid email address'
        //             },
        //         stringLength: {
        //             max: 64,
        //             message: 'Maximum 64 characters allowed'
        //         }
        //     }
        // },

        
    }
});

$('.tender').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        tender_title: {
            validators: {
                notEmpty: {
                    message: "Tender title is required "
                }
            }
        },
        tender_opening_date: {
            validators: {
                notEmpty: {
                    message: "Tender Opening Date is required "
                }
            }
        },
        tender_cat_id: {
            validators: {
                notEmpty: {
                    message: "Tender Category is required "
                }
            }
        },
        nsd_id: {
            validators: {
                notEmpty: {
                    message: "NSD Name is required "
                }
            }
        },
        tender_number: {
            validators: {
                notEmpty: {
                    message: "Tender Number is required "
                }
            }
        },
        // ref_tender_id: {
        //     validators: {
        //         callback: {
        //             message: 'Demand Number is required',
        //             callback: function (value, validator, $field) {
        //                 // Determine the numbers which are generated in captchaOperation
        //                 var approval_letter_number = $('#approval_letter_number').val();
        //                 var ref_tender_id = $('#ref_tender_id').val();
        //                     if(ref_tender_id != ''){
        //                         return true;
        //                     }
        //                     if(approval_letter_number == ''){
        //                         return false;
        //                     }
                        
        //             }
        //         }
        //     }
        // },
        // approval_letter_number: {
        //     validators: {
        //         callback: {
        //             message: 'Approval Letter Number is required',
        //             callback: function (value, validator, $field) {
        //                 var ref_tender_id = $('#ref_tender_id').val();
        //                 var approval_letter_number = $('#approval_letter_number').val();
        //                     if(approval_letter_number != ''){
        //                         return true;
        //                     }
        //                     if(ref_tender_id == ''){
        //                         return false;
        //                     }

                        
        //             }
        //         }
        //     }
        // },
        ref_tender_id: {
            validators: {
                notEmpty: {
                    message: "Demand Number is required"
                }
            }
        },
        // approval_letter_number: {
        //     validators: {
        //         notEmpty: {
        //             message: "Approval Letter Number is required"
        //         }
        //     }
        // },
        valid_date_from: {
            validators: {
                notEmpty: {
                    message: "Valid Date From"
                }
            }
        },
        valid_date_to: {
            validators: {
                notEmpty: {
                    message: "Valid Date To"
                }
            }
        },
        tender_type: {
            validators: {
                notEmpty: {
                    message: "Tender Type"
                }
            }
        },
        // tender_priority: {
        //     validators: {
        //         notEmpty: {
        //             message: "Tender Priority"
        //         }
        //     }
        // },
        tender_nature: {
            validators: {
                notEmpty: {
                    message: "Tender Nature"
                }
            }
        },
        nhq_ltr_no: {
            validators: {
                notEmpty: {
                    message: "Reference Number is required"
                }
            }
        },
        invitation_for: {
            validators: {
                notEmpty: {
                    message: "Invitation For is required"
                }
            }
        },
        location: {
            validators: {
                notEmpty: {
                    message: "Location is required"
                }
            }
        },

        
    }
});

$('.items').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
        item_name: {
            validators: {
                notEmpty: {
                    message: "Item Name is required "
                }
            }
        },
        item_acct_status: {
            validators: {
                notEmpty: {
                    message: "Item Acct Status is required"
                }
            }
        },
        // model_number: {
        //     validators: {
        //         notEmpty: {
        //             message: "Model Number is required "
        //         }
        //     }
        // },
        item_cat_id: {
            validators: {
                notEmpty: {
                    message: "Item Category is required "
                }
            }
        },
        // unit_price: {
        //     validators: {
        //         notEmpty: {
        //             message: "Unit Price is required "
        //         }
        //     }
        // },
        // source_of_supply: {
        //     validators: {
        //         notEmpty: {
        //             message: "Source Of Supply is required "
        //         }
        //     }
        // },
        item_deno: {
            validators: {
                notEmpty: {
                    message: "Deno is required "
                }
            }
        },
        'nsd_id[]': {
            validators: {
                notEmpty: {
                    message: "NSD Name is required "
                }
            }
        },
        // budget_code: {
        //     validators: {
        //         notEmpty: {
        //             message: "Budget Code is required "
        //         }
        //     }
        // },
        // currency_name: {
        //     validators: {
        //         notEmpty: {
        //             message: "Currency Name is required "
        //         }
        //     }
        // },
        // conversion: {
        //     validators: {
        //         notEmpty: {
        //             message: "Conversion is required "
        //         }
        //     }
        // },
        
    }
});

$('.item-to-tender').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        search_supplier_id: {
            validators: {
                notEmpty: {
                    message: "Supplier name is required "
                }
            }
        },
        po_number: {
            validators: {
                notEmpty: {
                    message: "PO Number is required "
                }
            }
        },
        
    }
});

$('.zones').bootstrapValidator({
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        name: {
            validators: {
                notEmpty: {
                    message: "Name is required "
                }
            }
        },
        alise: {
            validators: {
                notEmpty: {
                    message: "Alise is required "
                }
            }
        },
        status: {
            validators: {
                notEmpty: {
                    message: "Status is required "
                }
            }
        },

    }
});

$('.notice').bootstrapValidator({
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        'zones[]': {
            validators: {
                notEmpty: {
                    message: "Zones is required "
                }
            }
        },
        'nsds_bsds[]': {
            validators: {
                notEmpty: {
                    message: "NSD/BSD is required "
                }
            }
        },
        description: {
            validators: {
                notEmpty: {
                    message: "Description is required "
                }
            }
        },
        status: {
            validators: {
                notEmpty: {
                    message: "Status is required "
                }
            }
        },
        title: {
            validators: {
                notEmpty: {
                    message: "Title is required "
                }
            }
        },

    }
});

$('.contact').bootstrapValidator({
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        zones: {
            validators: {
                notEmpty: {
                    message: "Zones is required "
                }
            }
        },
        nsd_bsd: {
            validators: {
                notEmpty: {
                    message: "NSD/BSD is required "
                }
            }
        },
        // descriptions: {
        //     validators: {
        //         notEmpty: {
        //             message: "Description is required "
        //         }
        //     }
        // },
        status: {
            validators: {
                notEmpty: {
                    message: "Status is required "
                }
            }
        },

    }
});
$('.budget_code').bootstrapValidator({
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        code: {
            validators: {
                notEmpty: {
                    message: "Code is required "
                }
            }
        },
        // description: {
        //     validators: {
        //         notEmpty: {
        //             message: "Description is required "
        //         }
        //     }
        // },
        status_id: {
            validators: {
                notEmpty: {
                    message: "Status is required "
                }
            }
        },

    }
});

$('.currency').bootstrapValidator({
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        currency_name: {
            validators: {
                notEmpty: {
                    message: "Currency Name is required "
                }
            }
        },
        symbol: {
            validators: {
                notEmpty: {
                    message: "Symbol is required "
                }
            }
        },
        conversion: {
            validators: {
                notEmpty: {
                    message: "Conversion is required "
                }
            }
        },
        status_id: {
            validators: {
                notEmpty: {
                    message: "Status is required "
                }
            }
        },

    }
});

$('.group_name').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        name: {
            validators: {
                notEmpty: {
                    message: "Group Name is required "
                }
            }
        },
        'zones[]': {
            validators: {
                notEmpty: {
                    message: "Zone Name is required "
                }
            }
        },
        
    }
});

$('.demands').bootstrapValidator({
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        requester: {
            validators: {
                notEmpty: {
                    message: "Demande is required "
                }
            }
        },
        demand_no: {
            validators: {
                notEmpty: {
                    message: "Demand no is required "
                }
            }
        },
        // priority: {
        //     validators: {
        //         notEmpty: {
        //             message: "Priority is required "
        //         }
        //     }
        // },
        when_needed: {
            validators: {
                notEmpty: {
                    message: "Demand date is required "
                }
            }
        },
        pattern_or_stock_no: {
            validators: {
                notEmpty: {
                    message: "Authority Number is required "
                }
            }
        },

    }
});

$('.demande').bootstrapValidator({      
    ignore: ":hidden",
    excluded: ':disabled',
    feedbackIcons: {
        valid: 'glyphicon glyphicon',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {

        name: {
            validators: {
                notEmpty: {
                    message: "Name is required "
                }
            }
        },
        alise: {
            validators: {
                notEmpty: {
                    message: "Alise is required "
                }
            }
        },
        
    }
});


