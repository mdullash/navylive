<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>
    Issue
</title>


<head>
    <link rel="stylesheet" href=" {{ url('public/vendor/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('public/vendor/select2-bootstrap/select2-bootstrap.css')}}">
    <link rel="stylesheet" href="{{ url('public/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}">
    <link rel="stylesheet" href="{{ url('public/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">


    <style>
        body{
            font-family: 'bangla', sans-serif;
            padding: 100px;
            font-size: 12px !important;
        }
        .logo-div{
            text-align: center;
            /* -webkit-filter: grayscale(100%);
             filter: grayscale(100%); */
        }
        /*.text-red{
            color: red;
        }
        .bg-red{
            background-color: red;
        }*/
        .navy-logo{
            width: 100px;
        }
        .underline{
            text-decoration: underline;
        }
        .text-center{
            text-align: center;
        }
        h3.text-center{
            margin: 5px 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            /*font-family: Arial, Helvetica, sans-serif;*/
            font-weight: 500;
        }

        table, th, td {
            border: 1px solid black;
        }
        th{
            font-weight: bold;
        }
        th,td{
            padding: 3px 10px;
        }
        td p{
            margin: 0 10px;
        }
        @page {
            /*header: page-header;*/
            footer: page-footer;
        }

    </style>
</head>


<body style="margin-top: 0px; padding-top: 20px; margin-left: 15%; margin-right: 15%;">

<div class="logo-div">
    <img class="navy-logo" src="{{URL::to('/')}}/public/img/bd-navy.png">
</div>
<h4 class="underline text-center">Gate Pass<br/> NSSD Dhaka <span class="bg-red">
           </span>
</h4>

<table style="border: 0;">
    <tbody>
    <tr>
        {{--<td style="border: 0;"></td>--}}
        <td style="border: 0;padding-left: 0;"><b>Gate Pass No: </b> {!! $issue_datas->gate_pass_no !!}</td>
        <td style="border: 0;" width="435"></td>
        <td style="border: 0; "><b>Date: </b> {!! $issue_datas->date !!} </td>
        <td style="border: 0;"></td>
    </tr>
    <tr>
        {{--<td style="border: 0;"></td>--}}
        <td style="border: 0;padding-left: 0"><b>Group: </b>{!! $issue_datas['groupName']->name !!}</td>
        <td style="border: 0;" width="220"></td>
        <td style="border: 0;"><b>User: </b>{!! $issue_datas['demanding']->name !!}</td>
        <td style="border: 0;"></td>
    </tr>

    </tbody>
</table>

<br>
<br>

<table>
    <thead></thead>
    <tbody>
    <tr class="center">

        <th class="text-center">{!! 'Ser' !!}</th>
        <th class="text-center">{!! 'IMC No' !!}</th>
        <th class="text-center">{!! 'Item Name' !!}</th>
        <th class="text-center">{{ 'DNO' }}</th>
        <th class="text-center">{{ 'Quantity' }}</th>
    </tr>

    @if(!empty($qyeryResutl))
        <?php $i = 1;
        ?>
        @foreach($qyeryResutl as $qrl)


            <tr>

                <td>{!! $i++ !!}</td>
                <td>  {!! $qrl->serial_imc_no !!}</td>

                <td>
                    {!! $qrl->item_name !!}
                </td>

                <td>
                    {!! $qrl->denoName !!}
                </td>

                <td>
                    {!! $qrl->quantity !!}
                </td>


            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<br>
<br>

<table style="border: 0;">
    <tbody>
    <tr>
        <td style="border: 0;padding-left: 0;width: 42%"> <h4 style="text-decoration: underline;"><b>Issued By</b> <br></h4></td>
        <td style="border: 0;width: 40%;"> <h4 style="text-decoration: underline; "><b>Received By</b> <br></h4></td>
        <td style="border: 0;"> <h4 style="text-decoration: underline;"><b>Approved By</b> <br></h4></td>
    </tr>
    <tr>
        <td style="text-align: center;border: 0">@if(isset($issue_datas['issuedName']) && $issue_datas['issuedName']->digital_sign !=null)<img src="{!! asset('public/uploads/digital_sign/'.$issue_datas['issuedName']->digital_sign) !!}" alt="">@endif</td>
        <td style="text-align: center;border: 0"> </td>
        <td style="text-align: center;border: 0"> @if(isset($issue_datas['approvedName']) && $issue_datas['approvedName']->digital_sign !=null) <img src="{!! asset('public/uploads/digital_sign/'.$issue_datas['approvedName']->digital_sign) !!}" alt="">@endif</td>
    </tr>
    <tr>
        <td style="border: 0;padding-left: 0;">
            <b>Name: </b>

            @if(isset($issue_datas['issuedName'])) {!! $issue_datas['issuedName']->first_name !!} {!! $issue_datas['issuedName']->last_name !!}@endif
        </td>
        <td style="border: 0">
            <b>Name: </b>
            {!! $issue_datas->received_by !!}
        </td>
        <td style="border: 0">
            <b>Name: </b>
            @if(isset($issue_datas['approvedName']))  {!! $issue_datas['approvedName']->first_name !!} {!! $issue_datas['approvedName']->last_name !!}@endif
        </td>
    </tr>
    <tr>
        <td style="border: 0;padding-left: 0;">
            <b>Rank: </b>@if($issue_datas->issued_rank==null)  @if(isset($issue_datas['issuedName'])) {!! $issue_datas['issuedName']->rank !!} @endif @else {!! $issue_datas->issued_rank !!}  @endif
        </td>
        <td style="border: 0">
            <b>Rank:</b> {!! $issue_datas->received_rank !!}
        </td>
        <td style="border: 0">
            <b>Rank: </b>@if($issue_datas->approved_rank==null) @if(isset($issue_datas['approvedName'])) {!! $issue_datas['approvedName']->rank !!} @endif @else {!! $issue_datas->approved_rank !!}  @endif
        </td>
    </tr>

    </tbody>
</table>
<br>

<div>

    <a class="btn btn-warning" href="{{ URL::to('issue-view/'.$id) }}" title="Edit">
        <i class="glyphicon glyphicon-edit"></i> Edit
    </a>

    @if($issue_datas->status == 1)
        @if(!empty(Session::get('acl')[53][38]))

            <a class="btn btn-success" href="{{ URL::to('issue_voucher/'.$issue_datas->id) }}" title="Issue" onclick="return confirm('Are you sure want to Issue?')">
                <i class="glyphicon glyphicon-ok"></i> Issue

                @endif
                @endif

                @if($issue_datas->status == 2)
                    @if(!empty(Session::get('acl')[53][12]))

            </a> <a class="btn btn-success" href="{{ URL::to('issue_approve/'.$issue_datas->id) }}" title="Approve" onclick="return confirm('Are you sure want to approve?')">
                <i class="glyphicon glyphicon-ok"></i> Approve
            </a>
        @endif

        @if(!empty(Session::get('acl')[53][13]))
            <a class="btn btn-danger" href="{{ URL::to('issue-reject/'.$issue_datas->id) }}" title="Rejected" onclick="return confirm('Are you sure want to reject?')">
                <i class="glyphicon glyphicon-remove"></i> Reject
            </a>
        @endif
    @endif

    <a class="btn btn-warning" href="{{ URL::to('issue-pdf-view/'.$id.'&2') }}" title="Print" target="_blank">
        <i class="glyphicon glyphicon-print"></i> Print
    </a>

</div>



<!-- Vendor scripts -->

<script rel="javascript" src=" {{ url('public/dist/js/lightbox-plus-jquery.min.js') }}"></script>
<script rel="javascript" src=" {{ url('public/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>



</body>
</html>

