<html>
<head>
    <style>
        .mid_sub_head {
            background-color: rgb(110, 110, 109);
            border-bottom: none;
            height: 40px;
            line-height: 40px;
            padding: 0 5px;
        }

        h1.mid_col_header  {
            border-bottom: none;
            color: #000;
            font-size: 1.5em;
            font-weight: bold;
            margin: 0 0 20px;
            padding: 0;
            text-indent: 0.9375em;
        }


        #approvers, #activities_approvers {
            background-color: rgb(238, 238, 239);
            width:210px;
            padding: 0px 0px;
            margin:0;
        }

        #approvers ul {
            /*background:blue;*/
        }

        #approvers ul li, #activities_approvers ul li {
            background-image: url('../../assets/img/person.png');
            background-size: 40px;
            background-repeat: no-repeat;
            background-position:left 5px;
            cursor: pointer;
            padding: 10px 5px 10px 50px;
        }

        #activities_approvers ul li  {
            cursor: default;
        }

        #approvers ul li img, #activities_approvers  ul li img {
            float: right;
            margin-right: 0px;
            margin-top: 0px;
            width: 20px;
        }

        #approvers ul li.selected, #activities_approvers ul li.selected {
            background-color: rgb(214, 215, 215);
            /*line-height: 45px;*/
        }

        .full_name {
            /*background:red;*/
            display: inline-block;
            width: 60%;
            font-size: 0.8125em;
        }

        .main_stage {
            background-color: rgb(214, 215, 215);
            width:765px;
            float:left;
        }

        #project_list {
            /*background-color: rgb(214, 215, 215);	*/
            min-height: 450px;
            width:765px;
            margin:0;
        }

        #poDetail {
            width:765px;
            float:left;
            height:auto;
            overflow: scroll;
            padding:3px;
        }


        .podetail {
            /*position: absolute;*/
            /*margin-left:-10000px;*/
            width:765px;
            float:left;
            height:1200px;
            overflow: scroll;
            padding:3px;
            border:none!important;
        }

        .podetail textarea, .podetail button.send_comment {
            display: none;
        }



        .project_item {
            background: url("../../assets/img/small_PO.png") no-repeat 5px 30px,  linear-gradient(rgba(255,255,255,1), rgba(237,245,251,1));
            margin-bottom: 5px;
            margin-left: 10px;
            margin-top: 5px;
            margin-right: 0;
            height: 210px;
            width: 355px;
            border: 1px solid #AAA;
            border-radius: 4px;
            padding:5px;
            cursor: pointer;
            position: relative;
            box-shadow: 2px 2px 2px #999
        }

        #project_list div.selected {
            background: url("../../assets/img/small_PO.png") no-repeat 5px 30px,  linear-gradient(rgba(255,255,255,1), rgba(251,246,228,1));
            cursor: default;
        }

        .project_item:last-child {
            margin-bottom: 100px!important;
        }

        .project_item  p {
            width:250px;
            font-size: 0.80em;
            margin-top:2px;
        }

        .project_item  p span {
            display:inline-block;
        }

        .project_item p span.label {
            /*background:yellow;*/
            width:55px;
            float:left;
            font-weight: bold;

        }

        .project_item p span.data {
            /*background:green;*/
            width:189px;
            float:left;
            padding:2px;
        }


        .project_item p.ponumber {
            margin-bottom: 10px;
        }

        .project_item p.ponumber span.data{
            font-weight:bold;
            font-size: 1em;
            margin-left:5px;
        }

        .project_item p.ponumber span.label{
            font-weight:bold;
            font-size: 1em;
            width:auto;
            padding:2px;
        }

        .project_item .icons {
            /*background: red none repeat scroll 0 0;*/
            bottom: 0;
            /*float: right;*/
            margin-bottom: 10px;
            position: absolute !important;
            left: 0;
            width:99%!important;
        }

        .project_item .icons span {
            display: inline-block;
            height:30px;
            width: 40px;
            cursor: pointer;
        }

        .lock {
            background-image: url("../img/po_assign.png");
            background-position: 5px 2px;
            background-repeat: no-repeat;
            background-size: 30px auto;
            margin-top: 2px;
        }

        .forward {
            background-image: url("../img/po_assign.png");
            background-position: 5px 2px;
            background-repeat: no-repeat;
            background-size: 30px auto;
            margin-top: 2px;
        }

        .approve {
            background-image: url("../img/po_approved.png");
            background-position: 5px 2px;
            background-repeat: no-repeat;
            background-size: 30px auto;
            margin-top: 2px;
        }


        .popover {
            /*background: red none repeat scroll 0 0;*/
            bottom: -160px;
            height: 215px;
            width: 250px;
            position: absolute;
            z-index: 100;
            background: #FFF;
            border: 2px solid #c2e1f5;
        }

        span.count  {
            /*background: yellow none repeat scroll 0 0;*/
            display: inline-block;
            font-size: 12px;
            margin-left: 0px;
            text-align: center;
            width: 30px;
            margin-bottom: 5px;
        }


        .popover:after, .popover:before {
            left: 100%;
            top: 17%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .popover:after {
            border-color: rgba(136, 183, 213, 0);
            border-left-color: #FFF;
            border-width: 10px;
            margin-top: -10px;
        }
        .popover:before {
            border-color: rgba(194, 225, 245, 0);
            border-left-color: #c2e1f5;
            border-width: 13px;
            margin-top: -13px;
        }


        .popover ul {
            list-style: none;
            height: 190px;
            overflow: scroll;
            margin-top:10px;

        }

        .popover ul li {
            border-bottom: 1px solid #DDD;
            padding:5px 5px 5px 5px;
            font-size:0.8em;
            cursor: pointer;
        }

        .popover ul li.first {
            font-weight: bold;
            cursor: default;
        }


        .popover ul li.first img {
            position: absolute;
            right: 20px;
            width: 15px;
        }

        .popover ul li span.small_count {
            display: inline-block;
            float: right;
            margin-right: 30px;
        }

        .popover ul li span.name {
            display: inline-block;
            /*float: right;*/
            /*margin-right: 80px;*/
            width:180px;
            /*background-color: red;*/
        }

        .requisitions {
            width:92%;
            height:1200px;
            background-color: #FFF;
            margin:auto;
            padding:20px;
        }

        .icon_command {
            /*background:red;*/
            float: right;
            height: 40px;
            width: 280px;
            /*margin-right:-500px;*/
        }

        .icon_command span,
        span.list {
            /*background-color: yellow;*/
            display: inline-block;
            background-color: rgba(214, 215, 215, 1);
            margin-top: 2px;
            line-height: 35px;
            height:35px;
            font-size:12px!important;
            color:#fff!important;
            border-radius: 6px;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border: none;
            padding:0 15px 0px 15px;
            cursor:pointer;
            float:left;
            margin-right: 5px;
        }

        .close, .email_close {
            background: #fff none repeat scroll 0 0;
            border: 1px solid #000;
            border-radius: 20px;
            color: red;
            cursor: pointer;
            display: inline-block;
            font-weight: bold;
            height: 20px;
            line-height: 20px;
            margin-left: -10px;
            margin-top: -10px;
            position: absolute;
            text-align: center;
            width: 20px;
            left: 0;
        }

        .email_close, .approver_close {
            margin-top: -10px;
            background-color: rgb(179, 199, 212);
        }

        .approver_close {
            left:0;
        }
        .approver_popover form {
            margin: 5px auto;
            width: 94%;
        }

        .email_popover form {
            margin: 5px auto;
            width: 94%;
        }

        .email_popover input[name="to"],
        .email_popover input[name="subject"] {
            font-size: 14px;
            margin: 5px 10px;
            position: relative;
            width: 250px;
            border-radius: 4px;
        }

        .email_popover input[name="to"] {
            /*margin-top: 20px!important;*/
        }

        .email_popover form textarea {
            background: #fff none repeat scroll 0 0;
            border-radius: 4px;
            font-size: 14px;
            height: 100px;
            margin: 10px auto 0 10px;
            padding: 4px;
            width: 247px;
        }

        .email_popover form button {
            margin-bottom: 10px;
            margin-left: 10px;
            width: 260px;
        }

        .req_header {
            color: #000;
            display: inline-block;
            font-size: 12px;
            margin-bottom: -25px;
            margin-top: 5px;
            padding-left: 10px;
        }

        .list {
            background-image: url("../img/list.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 25px auto;
            width: 10px;
            margin-left: -100px;
        }


        .pdf {
            background-image: url("../img/req_pdf.png");
            background-repeat: no-repeat;
            background-size: 30px auto;
            background-position: 4px center;
            width:20px;
        }

        .attach {
            background-image: url("../img/mail.png");
            background-repeat: no-repeat;
            background-size: 50px auto;
            background-position: 4px center;
            width:30px;
        }

        .note {
            background-image: url("../img/note.png");
            background-repeat: no-repeat;
            background-size: 30px auto;
            background-position: 0px top;
            width:30px;
        }

        .note span {
            color:#000!important;
        }

        .approve_btn {
            background-color: rgb(84, 165, 229)!important;
            width:50px!important;
        }


        .header .col_left, .header .col_right {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .header .col_left p, .header .col_right p {
            line-height: 1.3;
        }

        .print_logo {
            width: 130px;
            float:left;
            margin-right: 20px;
        }

        .header_final {
            float: left;
            font-size: 11px;
            margin-top: -5px;
        }

        strong {
            font-weight: bold;
        }

        div.vendor_info {
            border: 1px solid #000;
            float: left;
            margin-top: 20px;
            width: 37%;
            padding:5px;
            height:150px;
        }

        .shipping_info {
            border: 1px solid #000;
            float: left;
            padding:5px;
            width: 37%;
            height: 150px;
            margin-top: 10px;
        }

        .billing {
            border: 1px solid #000;
            float: right;
            width: 58%;
            height:325px;
            margin-top: 20px;
            padding:5px;
        }

        .req_data {
            display: inline-block;
        }


        span.vendor_info{
            float: left;
            font-size: 14px;
            margin-bottom: 20px;
            margin-top: 20px;
            width: 80%;
        }


        span.shippingInfo{
            float: left;
            font-size: 13px;
            margin-bottom: 20px;
            margin-top: 20px;
            width: 90%;
        }

        p.comm {
            /*background:red;*/
            margin-top: 2px;
            font-size: 14px;
        }

        span.ven_fax, span.ven_phone {
            /*background: yellow none repeat scroll 0 0;*/
            height: 20px;
            width: 100px;
        }


        p.comm label {
            font-weight: normal;
            display: inline-block;
        }

        p.black {
            font-style: italic;
            background-color: #000;
            color:#FFF;
            padding:5px;
        }


        span.bill_to_label {
            font-size: 14px;
            font-style: italic;
            margin-left: 20px;
            margin-top: 20px;
            width: 60px;
            display: inline-block;
            float:left;
        }

        span.act_bill_to_label {
            font-size: 14px;
            font-weight: bold;
            margin-left: 5px;
            /*margin-top: 10px;*/
            display: inline-block;
        }

        .BillToInfo {
            float:none;
            font-size: 14px;
            width: 70%;
            margin-top:20px;
            margin-left:10px;
        }

        p.signature {
            font-size: 14px;
            margin-bottom: 10px;
        }


        .ApprovedDate1,
        .ApprovedDate2,
        .ApprovedDate3,
        .ApprovedDate4,
        .ApprovedDate5,
        .ApprovedDateTE,
        .FinalApprovedDate {
            border-bottom:1px solid #ddd;
            margin-left: 10px;
            width: 80px;
        }


        .ApprovedBy1,
        .ApprovedBy2,
        .ApprovedBy3,
        .ApprovedBy4,
        .ApprovedBy5,
        .ApprovedByTE,
        .FinalApprovedBy {
            border-bottom:1px solid #ddd;
            margin-left: 10px;
            width: 120px;
        }

        .sig_label {
            display: inline-block;
            width:30px;
        }

        .ApprovedStatus1,
        .ApprovedStatus2,
        .ApprovedStatus3,
        .ApprovedStatus4,
        .ApprovedStatus5,
        .ApprovedStatusTE,
        .FinalApprovedStatus {
            border-bottom:1px solid #ddd;
            margin-left: 10px;
            width: 80px;
        }

        span.instructions {
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        div.instructions {
            border: 1px solid;
            font-size: 14px;
            height: 40px;
        }


        .small_details table, .lineitems table {
            border: 1px solid;
            border-collapse: collapse;
            border-spacing: 2px;
            display: table;
            font-size: 14px;
            margin-top: 10px;
            width: 100%;

        }

        .small_details table tr td, .lineitems table tr td {
            border: 1px solid;
            padding: 4px;
            text-align: center;
        }

        .total {
            float:right;
        }

        .total_label {
            display: inline-block;
            /*float: left;*/
            font-size: 14px;
            /*height: 20px;*/
            margin-top: 5px;
            width: 40px;
            text-align: right;
        }

        .grandtotal {
            /*margin-left: 10px;*/
            /*width: 82px;*/
            text-align: right;
        }

        .foot {
            font-size: 13px;
            margin-top: 10px;
        }


        .email_popover {
            background: linear-gradient(rgb(179, 199, 212), rgb(152, 179, 195));
            border-radius: 4px;
            box-shadow: 3px -2px 4px #aaa;
            height: auto;
            left: -100px;
            position: relative;
            top: 55px;
            width: 300px;
            z-index: 100;
            cursor: default;
        }


        .email_popover_arrow, .approver_popover_arrow {
            border-bottom: 20px solid rgb(179, 199, 212);
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            filter: drop-shadow(0px -2px 2px #aaa);
            height: 0;
            left: 90px;
            position: absolute;
            top: -18px;
            width: 0;

        }

        .approver_popover {
            background: linear-gradient(rgb(179, 199, 212), rgb(152, 179, 195));
            border-radius: 4px;
            box-shadow: 3px -2px 4px #aaa;
            cursor: default;
            height: auto;
            left: -30px;
            margin-left: -140px;
            position: relative;
            top: 25px;
            width: 300px;
            z-index: 100;
        }

        .approver_popover textarea {
            background:#fff;
            width:90%;
            height:100px;
        }


        .approver_popover form button {
            margin-bottom: 10px;
            margin-left: 10px;
            width: 260px;
        }


        .approver_popover_arrow {
            border-bottom: 20px solid rgb(179, 199, 212);
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            filter: drop-shadow(0px -2px 2px #aaa);
            height: 0;
            left: 180px;
            position: absolute;
            top: -18px;
            width: 0;
        }


        .req_note {
            height: 50px;
            margin-top: 0px;
            border-bottom-left-radius:4px;
            border-bottom-right-radius:4px;
            width: 96%;
            border-top  :none;
        }

        .send_comment {
            margin-top:20px;
        }

        .no_border_left {
            border-left: 0px!important;
        }

        .no_border_right {
            border-right: 0px!important;
        }


        input[name="ReceiveEmails"] {
            height: 30px;
            /*margin-left: 20px;*/
            margin-top: 5px;
            /*position: absolute;*/
            width: 30px;
        }

        span.ReceiveEmails {
            /*height: 40px;*/
            display:inline-block;
            /*margin-left: 60px;*/
            margin-top: 0px;
            /*position: absolute;*/
            /*width: 40px;*/
            color:#FFF;
            float:right;
        }

        span.activityNav a {
            display:inline-block;
            margin-top:0px;
            margin-left:40px;
            color:#FFF;
            float:left;
        }


        div.textdisplay {
            /*background: rgba(161, 188, 203, 0.3) none repeat scroll 0 0;*/
            border: 1px solid #c4c4c4;
            border-bottom  :none;
            border-top-left-radius:4px;
            border-top-right-radius:4px;
            font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
            font-size: 0.75em;
            margin: 0;
            padding: 0 10px;
            resize: none;
            height: 120px;
            margin-top: 30px;
            width: 96%;
        }

        input[name="search"] {
            margin-left:20px;
            margin-top:3px;
            background:#FFF;
        }

        p.settings span.select_wrap {
            background-color:rgba(161, 188, 203, 0.6);
            height:38px!important;
        }


        .pono, .date {
            font-size: 14px;
            width:160px;
            text-align: left;

        }

        .pono span.label, .date span.label {
            width:50px;
            display: inline-block;
            text-align: right;
        }

        .pono span.data, .date span.data {
            width:100px;
            display: inline-block;
        }

        p.vendname {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        p.vendname span.label, p.vendaddy span.label {
            width:60px;
            display: inline-block;
        }

        p.vendaddy span.data2 {
            display: block;
            margin-left:65px;
        }

        .act_billing p {
            padding-left:10px;
            margin-bottom: 5px;
        }

        .act_billing p span.label {
            width:60px;
            display: inline-block;
        }

        .acct {
            float:right;
        }

        .acct span.label {
            display: inline-block;
        }

        .act_billing p {
            line-height: 1.2;
        }

        .additional {
            font-size: 14px;
            margin:15px;
        }

        .additional p {
            width:50%;
            height:30px;
        }

        .additional p.custodian {
            /*font-size: 12px;*/
        }

        span.spacer {
            width:50px;
        }

        .additional p span{
            display: inline-block;

        }
        .additional p span.label {
            width:200px;
        }

        .additional p span.data {
            width:120px;
        }
    </style>
</head>
<body>
<div class="requisitions">
    <div class="header">
        <img src="https://staff.putnamcityschools.org/.pc/site/assets/img/pc_main_logo.png" class="print_logo">
        <p class="float_left" style="font-weight:bold;">PUTNAM CITY PUBLIC SCHOOLS ACCOUNTS PAYABLE</p>
        <div class="col_left float_left">
            <p>5401 NW 40th  - Oklahoma City, OK 73122</p>
            <p>Phone:(405) 495-5200  Fax (405) 495-8648</p>
        </div>
        <div class="col_right float_right">
            <p><strong>Requisition Date:</strong> <span class="req_date req_data"></span></p>
            <p><strong>Purchase No.:</strong> <span class="ponum req_data" style="text-align: right;width: 105px"></span></p>
            <p><span class="created_by req_data float_right"></span><strong>Created By:</strong></p>
        </div>
        <p class="header_final">FINAL APPROVAL MUST BE OBTAINED PRIOR TO PURCHASE</p>
        <p class="clear_all"></p>
    </div>
    <div class="mid_header">

        <div class="billing">
            <span class="bill_to_label">Bill To:</span>
            <span class="BillToInfo req_data"></span>
            <p class="clear_all"></p>
            <br><br><br>
            <p class="signature"><span class="sig_label">1st</span>
                <span class="ApprovedStatus1 req_data"></span>
                <span class="ApprovedBy1 req_data"></span>
                <span class="ApprovedDate1 req_data"></span>
            </p>
            <p class="signature"><span class="sig_label">2nd</span>
                <span class="ApprovedStatus2 req_data"></span>
                <span class="ApprovedBy2 req_data"></span>
                <span class="ApprovedDate2 req_data"></span>
            </p>
            <p class="signature"><span class="sig_label">3rd</span>
                <span class="ApprovedStatus3 req_data"></span>
                <span class="ApprovedBy3 req_data"></span>
                <span class="ApprovedDate3 req_data"></span>
            </p>
            <p class="signature"><span class="sig_label">4th</span>
                <span class="ApprovedStatus4 req_data"></span>
                <span class="ApprovedBy4 req_data"></span>
                <span class="ApprovedDate4 req_data"></span>
            </p>
            <p class="signature"><span class="sig_label">5th</span>
                <span class="ApprovedStatus5 req_data"></span>
                <span class="ApprovedBy5 req_data"></span>
                <span class="ApprovedDate5 req_data"></span>
            </p>
            <p class="signature"><span class="sig_label">TE</span>
                <span class="ApprovedStatusTE req_data"></span>
                <span class="ApprovedByTE req_data"></span>
                <span class="ApprovedDateTE req_data"></span>
            </p>
            <p class="signature"><span class="sig_label">Final</span>
                <span class="FinalApprovedStatus req_data"></span>
                <span class="FinalApprovedBy req_data"></span>
                <span class="FinalApprovedDate req_data"></span>
            </p>
        </div>

        <div class="vendor_info">
            <p class="float_left" style="font-size:14px">Vendor Information:</p>
            <p class="float_right" style="font-size:14px">Vendor #: <span class="ven_num req_data"></span></p>
            <!-- <p class="clear_all"></p> -->
            <span class="vendor_info req_data"></span>
            <p class="float_right" style="width:25px; margin-top:20px; font-size:13px">
                <span>W9</span>
                <br>
                <input type="checkbox">
                <br>
                <span>FD</span>
                <br>
                <input type="checkbox">
            </p>
            <p class="clear_all"></p>
            <p class="comm">
                <label>Phone</label>
                <span class="ven_phone req_data"></span><br>
                <label>Fax</label>
                <span class="ven_fax req_data"></span>
            </p>
        </div>

        <div class="shipping_info">
            <p class="black">Ship To:</p>
            <span class="shippingInfo req_data"></span>
            <p class="clear_all"></p>
            <p class="comm">
                <label>Phone</label>
                <span class="ship_phone req_data"></span>
                <label>Fax</label>
                <span class="ship_fax req_data"></span>
            </p>
        </div>

        <p class="clear_all"></p>
        <span class="instructions">Instructions</span>
        <div class="instructions">
            <span class="instructions_print"></span>
        </div>

        <div class="small_details">
            <table >
                <tr>
                    <td>FY</td>
                    <td>Fund</td>
                    <td>Project</td>
                    <td>Function</td>
                    <!-- <td>Obj</td> -->
                    <td>Program</td>
                    <td>Subject</td>
                    <td>Job Class</td>
                    <td>Site</td>
                    <td>Encumbrance No.</td>
                </tr>
                <tr>
                    <td class="fy"></td>
                    <td class="fund"></td>
                    <td class="project"></td>
                    <td class="function"></td>
                    <td class="program"></td>
                    <td class="subject"></td>
                    <td class="job"></td>
                    <td class="site"></td>
                    <td class="encumbrance"></td>
                </tr>
            </table>
        </div>
        <div class="lineitems">
            <table>
                <tr>
                    <td>Qty</td>
                    <td>Item No.</td>
                    <td>Item Description</td>
                    <td>Object Code</td>
                    <td>Unit price</td>
                    <td style="text-align:right">Total</td>
                </tr>
            </table>
            <!-- <p class="total"><span class="total_label">Total</span><span class="grandtotal req_data"></span></p> -->
            <p class="foot" style="font-size:14px">Charge To: </p>
            <div class="textdisplay"></div>
        </div>







        <!-- </div> -->
    </div>
</div>

</body>
</html>
