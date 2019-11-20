<!DOCTYPE html>
<html>

<head>
   <style>
       body{
        font-family: 'bangla', sans-serif;
        padding: 100px;
       }
       .logo-div{
           text-align: center;
           /* -webkit-filter: grayscale(100%);
            filter: grayscale(100%); */
       }
       .text-red{
           color: red;
       }
       .bg-red{
           background-color: red;
       }
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
   </style>
</head>

<body>
   <div class="logo-div">
        <img class="navy-logo" src="{{URL::to('/')}}/public/img/bd-navy.png">
   </div>
   <h2 class="underline text-center">Naval Stores Sub Depot Dhaka<br /> <span class="bg-red"> 
        @if($tenderInfoForPdf->tender_type==1)
            {!! 'Limited Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==2)
            {!! 'Open Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==3)
            {!! 'Restricted Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==4)
            {!! 'Spot Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==5)
            {!! 'Direct Purchase'  !!}
        @endif
        
   </h2>
   
    <table>
        <tr>
            <th colspan="5" class="text-center">INVITATION FOR TENDERS (IFT)</th>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="2">Ministry/Division</td>
            <td colspan="2">Ministry of Defence</td>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="2">Agency</td>
            <td colspan="2">Bangladesh Navy</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="2">Procuring Entity Name</td>
            <td colspan="2">NSSD Dhaka</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="2">Procuring Entity Code</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="2">Procuring Entity District</td>
            <td colspan="2">Dhaka</td>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="2">Invitation for</td>
            <td colspan="2">{!! $tenderInfoForPdf->invitation_for  !!}</td>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="2">Invitation Ref No</td>
            <td colspan="2">{!! $tenderInfoForPdf->tender_number  !!}</td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="2">Date</td>
            <td colspan="2">@if(!empty($tenderInfoForPdf->date)) {!! date('d F Y', strtotime($tenderInfoForPdf->date))  !!} @endif</td>
        </tr>
        <tr>
            <th colspan="5">KEY INFORMATION</th>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="2">Procuremment method</td>
            <td colspan="2">
                @if($tenderInfoForPdf->tender_type==1)
                    {!! 'Limited Tender Method'  !!}
                @endif
                @if($tenderInfoForPdf->tender_type==2)
                    {!! 'Open Tender Method'  !!}
                @endif
                @if($tenderInfoForPdf->tender_type==3)
                    {!! 'Restricted Tender Method'  !!}
                @endif
                @if($tenderInfoForPdf->tender_type==4)
                    {!! 'Spot Tender Method'  !!}
                @endif
                @if($tenderInfoForPdf->tender_type==5)
                    {!! 'Direct Purchase Method'  !!}
                @endif
            </td>
        </tr>
        <tr>
            <th colspan="5">FUNDING INFORMATION</th>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="2">Budget and Source of Funds</td>
            <td colspan="2">GoB</td>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="2">Development Partners</td>
            <td colspan="2"><span class="bg-red">{!! $tenderInfoForPdf->development_partners  !!}</span></td>
        </tr>
        <tr>
            <th colspan="5">PARTICULAR INFORMTION</th>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="2">Project/Programme Code</td>
            <td colspan="2"><span class="bg-red"{!! $tenderInfoForPdf->proj_prog_code  !!}</span></td>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="2">Project/Programme Code (if Applicable)</td>
            <td colspan="2"><span class="bg-red">{!! $tenderInfoForPdf->proj_prog_code  !!}</span></td>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="2">Tender Package No</td>
            <td colspan="2"><span class="bg-red">{!! $tenderInfoForPdf->tender_package_no  !!}</span></td>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="2">Tender Package Name</td>
            <td colspan="2"><span class="bg-red">{!! $tenderInfoForPdf->tender_package_name  !!}</span></td>
        </tr>
        <tr>
            <td>16.</td>
            <td colspan="2">Tender Publication Date</td>
            <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->valid_date_from)) {!! date('d F Y', strtotime($tenderInfoForPdf->valid_date_from))  !!} @endif</span></td>
        </tr>
        <tr>
            <td>17.</td>
            <td colspan="2">Tender Last Selling Date</td>
            <td colspan="2"><Span class="bg-red">@if(!empty($tenderInfoForPdf->tender_opening_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->tender_opening_date)). '& Time 1200 hrs'  !!} @endif</Span></td>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="2">Tender Submission Date and Time</td>
            <td colspan="2"><Span class="bg-red">@if(!empty($tenderInfoForPdf->tender_opening_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->tender_opening_date)). '& Time 1200 hrs'  !!} @endif</Span></td>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="2">Tender Opening Date and Time</td>
            <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->tender_opening_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->tender_opening_date)). '& Time 1200 hrs'  !!} @endif</span></td>
        </tr>
        <tr>
            <td>20.</td>
            <td colspan="2">
                <p>Name & Address of the office (s)</p>
                <p>-Selling Tender Document (Principle)</p>
                <p>-Selling Tender Document (Others)</p>
                <p>-Receiving Tender Document</p>
                <p>- Opening Tender Document</p>
            </td>
            <td colspan="2">
                <p>Officer- In-charge</p>
                <p>Naval Stores Sub Depot Dhaka</p>
                <p> Namapara Khilkhet, Dhaka 1229</p>
            </td>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="2">Place/Date/Time of Pre-Tender Meeting</td>
            <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->pre_tender_meeting)) {!! date('d F Y', strtotime($tenderInfoForPdf->pre_tender_meeting))  !!} @endif</span></td>
        </tr>
        <tr>
            <th colspan="5">INFORMATION FOR TENDER</th>
        </tr>
        <tr>
            <td>22.</td>
            <td colspan="2">Eligibility of Tender</td>
            <td colspan="2">
                <p><span class="bg-red">{!! $tenderInfoForPdf->eligibility_of_tender  !!}</span>
                </p>
            </td>
        </tr>
        <tr>
            <td>23.</td>
            <td colspan="2">Brief Description of Goods </td>
            <td colspan="2">
                <p>
                    <span class="bg-red">{!! $tenderInfoForPdf->tender_description  !!}</span>
                </p>
            </td>
        </tr>
        <tr>
            <td>24.</td>
            <td colspan="2">Brief Description of Related Services</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>25.</td>
            <td colspan="2">Price of Tender Document (Tk)</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>26.</td>
            <td colspan="2">Name of Official Inviting Tender</td>
            <td colspan="2"><span class="bg-red">{!! $tenderInfoForPdf->name_of_offi_invit_ten  !!}</span></td>
        </tr>
        <tr>
            <td>27.</td>
            <td colspan="2">Designation of Official inviting Tender</td>
            <td colspan="2"><span class="bg-red">{!! $tenderInfoForPdf->desg_of_offi_invit_ten  !!}</span></td>
        </tr>
        <tr>
            <td>28.</td>
            <td colspan="2">Address of Official Inviting Tender</td>
            <td colspan="2">
                <p>OIC NSSD DHAKA</p>
                <p>Naval Stores Sub Depot Dhaka</p>
                <p>Namapara Dhaka 1229</p>
            </td>
        </tr>
        <tr>
            <td rowspan="2" >29.</td>
            <td rowspan="2" >Contact details of Official</td>
            <th>Tel. No.</th>
            <th>Fax. No.</th>
            <th>E-mail</th>
        </tr>
        <tr>
            <td class="text-center">41095104-8/4036</td>
            <td class="text-center">4109510</td>
            <td class="text-center">oic@bnnssddhk.org.bd</td>
        </tr>
        <tr>
            <td>30</td>
            <td colspan="4">The procuring entity reserves the right to reject all Tenders. </td>
        </tr>
        <tr>
            <td>31</td>
            <td colspan="4">NHQ Ltr No. – <span class="bg-red">{!! $tenderInfoForPdf->nhq_ltr_no  !!}</span></td>
        </tr>
        <tr>
            <td>32</td>
            <td colspan="4">Due to unavoidable circumstance if the tender can’t be received or opened in the schedule
                date the same will be shifted on the next working day.</td>
        </tr>
    </table>
    <div>
        <p>নিম্নলিখিত শর্ত সাপেক্ষে দরপত্র দাখিল করতে হবে:</p>
        <p>{!! $tenderInfoForPdf->tender_terms_conditions  !!}</p>
    </div>
</body>

</html>