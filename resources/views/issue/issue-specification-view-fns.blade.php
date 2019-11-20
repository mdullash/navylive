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
            font-size: 14px;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .table td, .table th {
            padding: .5rem .25rem;
            vertical-align: middle;
        }
        .table p {
            margin: 0;
        }
        @page {
            /*header: page-header;*/
            footer: page-footer;
        }

    </style>
</head>


<body style="margin-top: 0px; padding-top: 20px; margin-left: 15%; margin-right: 15%;">


<table style="width: 100%;">
    <tr>
        <td style="text-align: center; width: 650px;">

            <img class="navy-logo" src="{{URL::to('/')}}/public/img/bd-navy.png"><br><br>

            S-549 NO- <span>{!! $issue_datas->gate_pass_no !!}</span><br>
            <p style="margin: 0;  border-top: 1px solid #282828;display: inline-block;">Dt- <span>{!! $issue_datas->date !!}</span></p>
        </td>
        <td style="text-align: right;">
            <span>F (NS)-23</span><br>
            <p style="margin: 0;  border-top: 1px solid #282828;display: inline-block;"><span>S-549(L)</span></p>
        </td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td colspan="3" style="text-align: center;">
            <h1><b>Demand, Supply or Receipt Note for Occasional Supplies</b></h1>
            <p style="margin-bottom: 0;">Also for Stores supplied or expended on board the Ships for foreign Ships of war,</p>
            <p style="margin-top: 0;">Merchant Ships, or other than Naval Services.</p>
        </td>
    </tr>
    <tr>
        <td>Supplied by <br>
            <h5 style="margin: 5px 0;"><b>The Officer-in-Charge</b></h5>
            <span>B.N.S Naval Stores Sub Depot Dhaka</span><br>
            <span>at Namapara, Khilkhet, Dhaka-1229</span><br>
            <span>Date <span class="dt"> {!! $issue_datas->issue_date !!}</span></span><br>
            <span>Date required on board.................</span>
        </td>
        <td style="width: 205px;"></td>
        <td>Received by <br>
            <h5 style="margin: 5px 0;"><b>The Commanding Officer</b></h5>
            <span>{!! $issue_datas->received_address !!}</span><br>
            <span>Date <span class="dt">  </span></span><br>
            <span>Classification of Stores, Fixures of Spare Gear.....</span>
        </td>
    </tr><!--/tr-->
    <tr>
        <td colspan="3"> <br> </td>
    </tr>
</table>
<br>
<table class="table table-bordered main_table" style="width: 100%;">
    <tbody>
    <tr>
        <td rowspan="2">Pattern No.</td>
        <td rowspan="2" style="text-align: center;">ARTICLE</td>
        <td rowspan="2">Ship Ledger Page No</td>
        <td rowspan="2">Denomination</td>
        <td rowspan="2">Quantity Supplied</td>
        <td colspan="2" style="text-align: center;">Receiving Ship* <br>See Note*</td>
        <td rowspan="2">Value See Note</td>
        <td rowspan="2">Pack Ages</td>
    </tr><!--/tr-->
    <tr>
        <td>Allowed by Establishment</td>
        <td>Quantity on board after transfer</td>
    </tr><!--/tr-->
    <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
    </tr><!--/tr-->

    @if(!empty($qyeryResutl))
        <?php $i=1;
        ?>
        @foreach($qyeryResutl as $qrl)

            <tr>

                <td>{!! $i++ !!}</td>
                <td>
                    {!! $qrl->item_name !!}
                </td>
                <td>

                </td>
                <td>
                    {!! $qrl->denoName !!}
                </td>

                <td>
                    {!! $qrl->quantity !!}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr><!--/tr-->
        @endforeach
    @endif
    </tbody>
</table>
<br>
<table style="width: 100%;">
    <tr>
        <td style="width:30px;">
            <i>Ref:</i>
        </td>
        <td>{!! $issue_datas->ref  !!}</td>
        <td style="width: 100px;"></td>
        <td style="width: 42%;">Approved by <br>
            <span>@if(isset($issue_datas['approvedName']))  {!! $issue_datas['approvedName']->first_name !!} {!! $issue_datas['approvedName']->last_name !!} @endif</span><br>
            <span>@if($issue_datas->approved_rank==null) @if(isset($issue_datas['approvedName'])) {!! $issue_datas['approvedName']->rank !!} @endif @else {!! $issue_datas->approved_rank !!}  @endif</span><br>
            <span>Officer in Charge</span><br>
            <span>NSSD Dhaka</span><br>
            <span>Date <span class="dt">{!! $issue_datas->approve_date  !!}</span></span><br>
        </td>
    </tr><!--/tr-->
    <tr>
        <td colspan="3"> <br> </td>
    </tr>
</table>
<br>
<table style="width: 100%;">
    <tr style="margin-top: 10px;">
        <td>*When Permanent Naval Stores are transferred Columns 6 and 7 are to be completed by the Receiving Ship an if quantity in column 7 exceeds  that in Column 6 particulars should be given of the  necessity of, and approval for transfer.</td>
        <td style="text-align: center; width: 250px;">
            <p>Signature of Officer</p>
        </td>
        <td style="width: 255px;">
            <p style="margin-top: 0;">Supplying <span>..............................................</span></p>

            <p style="margin-top: 0;">Demanding <span>..............................................</span></p>

            <p style="margin-top: 0;">Receiving <span>..............................................</span></p>

            <p style="margin-top: 0;">Bank <span>...................................................</span></p>
        </td>
    </tr><!--/tr-->
    <tr>
        <td colspan="3">
            *In the case of supplies of Naval Stores from one Shop to another, this column need only be compiled for consumable Stores for which no values are shown in S_1098 <br><br>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            B N C P P - 174(s)
        </td>
    </tr>
</table>

<br>
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

