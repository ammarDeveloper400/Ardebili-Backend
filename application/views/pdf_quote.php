<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style type="text/css">

     @page{
        margin-top: 100px; /* create space for header */
        margin-bottom: 100px; /* create space for footer */
        margin-left:50px;
        margin-right:50px;
        width:100%
      }
    body {
        font-family: 'Rubik', sans-serif;
    }
    .htmlContract {
        margin-top: 0px;
        font-family: 'Rubik', sans-serif;
        position: relative;
    }
    .htmlContract .page {
        background: #ffffff;
        margin: auto;
        max-width: 100%;
    }
    .htmlContract .page.forceWidth {
        width: 210mm;
        height: 297mm;
    }
    .htmlContract .page table td {
        font-size: 15px;
    }
    .htmlContract .page p {
        font-size: 15px;
        font-weight: 500;
        color: #565656;
        margin: 0px;
        font-family: 'Rubik', sans-serif;
    }
    .htmlContract .page td.line {
        font-size: 13px;
    }
    .htmlContract .page td.line:after {
        bottom: 14px;
    }
    .htmlContract .page .line {
        font-weight: 700 !important;
        margin-bottom: 14px;
        position: relative;
        vertical-align: bottom;
    }
    .htmlContract .page .line:after {
        content: "";
        height: 1px;
        width: 250px;
        max-width: 96%;
        display: block;
        position: absolute;
        bottom: 0;
        background: black;
    }
    .htmlContract .page .line b {
        font-weight: 700 !important;
    }
    .htmlContract .page .line .placeholder_sign {
        border: none;
        display: inline-block;
        font-weight: 400;
    }
    .htmlContract .page .line .placeholder_sign img {
        left: 6px;
        bottom: -20px;
    }
    .htmlContract .page table.invoice {
        width: 100%;
    }
    .htmlContract .page table.invoice tr {
        border-bottom: 1px solid #c9c9c9;
    }
    .htmlContract .page table.invoice tr:last-child {
        border-bottom: 0;
    }
    .htmlContract .page table.invoice tr:last-child table {
        border-top: 1px solid black;
    }
    .htmlContract .page table.invoice tr td {
        padding-bottom: 4px;
        padding-top: 20px;
        min-width: 4cm;
    }
    .htmlContract .page table.invoice tr td:last-child {
        width: 4cm;
        text-align: right;
    }
    .htmlContract .page .placeholder_sign {
        width: 200px;
        padding-left: 10px;
        position: relative;
        border-bottom: 1px solid grey;
        display: inline-block;
    }
    .htmlContract .page .placeholder_sign img {
        position: absolute;
        bottom: 0;
    }
    .htmlContract .page table.client_signature {
        width: 100%;
    }
    .htmlContract .page table.client_signature td {
        width: 50%;
        padding-bottom: 20px;
        font-weight: 700;
    }
    .htmlContract .page table.footer_table {
        width: 100%;
        margin-top: 40px;
/*        margin-bottom: 0px;*/
/*        position: absolute;*/
/*        bottom: 50px;*/
    }
    .htmlContract .page table.footer_table td span{
        text-transform: uppercase;
        color: #cecece;
        width: 33%;
        font-size: 12px;
    }
    .htmlContract .page table.footer_table td:nth-child(1) {
        text-align: left;
            width: 33%;
    }
    .htmlContract .page table.footer_table td:nth-child(2) {
        text-align: center;
            width: 26%;
    }
    .htmlContract .page table.footer_table td:nth-child(3) {
        text-align: right;
            width: 25%;
    }
    .htmlContract .page table.footer_table td:nth-child(3) img {
        max-width: 140px;
        filter: grayscale(100%) brightness(70%) sepia(100%) hue-rotate(0deg) saturate(0%);
      /* The above line sets various filters to achieve the desired color change */
      filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
      /* The above line is needed for Safari compatibility */
    }
    .htmlContract .page .client-table {
        width: 100%;
    }
    .htmlContract .page .client-table td {
        width: 50%;
        text-align: left;
        vertical-align: top;
    }
    .htmlContract .page .header .logo {
        margin-bottom: 30px;
    }
    .htmlContract .page .header .logo img {
        max-width: 215px;
        margin-left: -13px;
    }
    .header_custom .logo img {
        max-width: 215px;
        margin-left: -13px;
    }
    .htmlContract .page .header .title h1 {
        font-size: 26px;
        font-weight: 700;
        color: #000;
        margin-bottom: 20px;
        font-family: 'Rubik', sans-serif;
    }
    .client-table b {
        font-family: 'Rubik', sans-serif;
        font-size: 16px;
        color: #202020;
    }
    .client-table p {
        font-family: 'Rubik', sans-serif;
    }
    .heading_pdf {
        width: 100px;
        float: left;
    }
    .pdf_below_data {
        text-transform: uppercase;
        text-decoration: underline;
        color: #202020;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 16px;
        width: 100%;
        font-family: 'Rubik', sans-serif;
    }
    .bg_main {
        background: url('<?php echo base_url();?>resources/cover_bg.png');
        height:100%;
       background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

    }
    .top_box {
        width:100%;
        text-align:center;
    }
    .top_logo {
        padding-top:80px;
        margin-left:60px;
        margin-bottom:60px;
    }
    .bold_data {
        font-size:40px;
        font-family: 'Rubik', sans-serif;
        /*text-decoration:underline;*/
        display: inline-block;
  border-bottom: 7px solid black;
  padding-bottom: 4px; /* Adjust the height of the "border" here */
        font-weight:900;
        text-align:center;
        margin-top:100px;

    }
    .mp_r {
        font-size: 35px;
        font-family: 'Rubik', sans-serif;
        font-weight:900;
        text-align:center;
        letter-spacing: 3px;
        padding-top:10px;
        margin-right:-15px;
    }

#header table,
#footer table {
    width: 100%;
    border-collapse: collapse;
    border: none;
}
.page-number {
  text-align: center;
}
.bullets_points {
    font-size: 14px;
    font-weight: 400;
    margin-left: 60px;
    margin-bottom: 10px;
}
.bullets_points span {
    margin-bottom: 10px;
    font-weight: 400;
    width: 100%;
    clear: both;
    display: inline-block;
}

.bullets_normal {
    font-size: 14px;
    font-weight: 400;
    margin-left: 0px;
    margin-bottom: 15px;
}
.bullets_actual_ span b {
    width: 400px;
    display: inline-block;
    font-weight: normal;
}
.porposal_text_normal {
    font-size: 14px;
    font-weight: 400;
    margin-bottom: 10px;
    margin-top: 10px;
}
.image_signature {
    position: absolute;
    top: -15px;
}
.image_signature img{
    height: 54px;
}
.header_custom {
    position: relative;
}
.header_custom .quote_number {
    position: absolute;
    right: 0;
    top: 13px;
    font-size: 14px;
}
.font-dyanmic li {
    font-size: 15px;
    font-weight: 500;
    color: #565656;
    margin: 0px;
    font-family: 'Rubik', sans-serif;
}
</style>
</head>
<body>
    <?php 
        $data = file_get_contents($image_url);
        $type = pathinfo($image_url, PATHINFO_EXTENSION);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>
<div class="htmlContract">
    <div class="page" style="margin-left: 0px; background: #fff;">

        <div class="header"  style="page-break-after: always;">

            <div class="title"><h1>PROPOSAL INFORMATION</h1></div>
            <div class="client">
                <table class="client-table">
                    <tbody>
                        <tr>
                            <td>
                                <p><b>Attention:</b></p>
                                <p>
                                    <?php echo $asa->asa_company_name; ?>
                                    <br />
                                    <?php echo $asa->company_contact; ?>
                                </p>
                                <?php if($client_data->po_number != ""){?>
                                    <br />
                                    <p><b class="heading_pdf" style="width:35%">PO #:</b> <?php echo $client_data->po_number; ?></p>
                                    
                                <?php } ?>
                                <?php if($client_data->clientprojectnumber != ""){?>
                                    <br />
                                    <p><b class="heading_pdf" style="width:35%">Client Project #:</b> <?php echo $client_data->clientprojectnumber; ?></p>
                                    
                                <?php } ?>

                               
                            </td>
                            <td>
                                <p><b class="heading_pdf">Date:</b> <?php echo date("d/m/Y", strtotime($client_data->start_date));?></p>
                                <br />
                                <p><b class="heading_pdf">Proposal #:</b> <?php echo $asa->pNumber; ?><?php echo $asa->revision_number!=0?".".$asa->revision_number:""; ?></p>
                                
                                
                                
                            </td>
                        </tr>
                        <br>
                        <tr>
                            <td colspan="2" style="font-weight:700; font-size: 15px; line-height: 23px;">
                                Ardebili Engineering, LLC ("Engineer") hereby submits its Proposal and Professional Services Agreement ("Agree-<br>
                                    ment") to perform the following Scope of Services and Fees for the Project. Client's signature on this Agreement<br>

                                    shall confirm its agreement to a binding contract with Engineer for the following Scope of Services and Fees subject<br>
                                    to the Terms and Conditions of this Agreement. Engineer must receive a signed Agreement before commencement<br>
                                    of Services.
                            </td>
                        </tr>
                        </tbody>
                         </table>
                        <br>
                       
                        <div>
                            <div>
                              <b  style="text-decoration: none; margin-bottom:15px; clear: both; font-size:18px; font-weight: 900;">Project Identification and Description:</b>
                                <p style="margin-top:10px">
                                    <b style="margin-bottom: 0px; float:left; width: 100%"><?php echo $asa->asa_project_name; ?></b>
                                    <br />
                                    <div class="font-dyanmic" style="font-weight:400; margin-bottom: 20px;">
                                        <?php echo $client_data->service_description; ?>
                                    </div>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div>
                              <p style="text-decoration: none; margin-top:20px; clear: both; font-size: 15px; font-weight: 900;"><b style="font-size:18px">Project Address:</b> <?php echo $client_data->address;?><?php if($client_data->city != null){echo ", ".$client_data->city;} ?>
                                    <?php if($client_data->state != null){echo ", ".$client_data->state;} ?>
                                    <?php if($client_data->zipcode != null){echo ", ".$client_data->zipcode;} ?></p>
                            </div>
                        </div>
                    
               
            </div>
        </div>
        <div  style="page-break-after: always;">
            <div class="title"><h1 style="text-align:center; margin-top: 40px; font-size: 25px;">SCOPE OF SERVICES AND FEES</h1></div>
            <table class="invoice">
                <thead>
                    <tr>
                        <td style="font-weight: bold; color: rgb(32, 32, 32); border-bottom: 1px solid #202020;">Services</td>
                        <td style="font-weight: bold; color: rgb(32, 32, 32); border-bottom: 1px solid #202020;">Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $key => $row) { ?>
                    <tr>
                        <td style="color: rgb(86, 86, 86); border-bottom: 1px solid #919191;">
                            <?php echo $row->task_name;?>
                            <br />
                            <?php 
                            echo $descppp = str_replace('•', "<br /> •", $row->task_description);
                            // echo $row->task_description;

                            ?>
                        </td>
                        <td style="color: rgb(86, 86, 86); border-bottom: 1px solid #919191;">$<?php echo number_format($row->total, 2);?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td><b style="color: rgb(32, 32, 32); font-size:17px">Valid Till: &nbsp;&nbsp;<?php echo date("d/m/Y", strtotime($client_data->valid_date));?></b></td>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td><b style="color: rgb(32, 32, 32); font-size: 16px;">Total</b></td>
                                        <td><b style="color: rgb(32, 32, 32); font-size: 16px;">$<?php echo number_format($total_amount,2); ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div  style="page-break-after: always;">
            
          
            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">General Terms and Conditions:</h1></div>
            <div class="bullets_points">
                    <span>• Engineer must receive a signed agreement before it will commence work on the above-mentioned project.</span>
                    <span>• Engineer will provide Construction Documents and calculations necessary for interdisciplinary coordination and preliminary
                    budgeting.</span>
                    <span>• Drawings will be furnished as PDF files and specifications in Engineer standard format will be located on the drawings.</span>
                    <span>• Client is responsible for all Permit Fees and charges for coordination of prints/plots and submittals to Engineer during design/
                    bid/construction phases of the project.</span>
                    <span>• Engineer cannot be held responsible for any contractor change orders based on our design if the contractor(s) was utilizing our
                    engineered drawings prior to the issuance of the stamped approved set by the local authority.</span>
                    <span>• Services outside of our contracted scope are Additional Services subject to additional fees. Engineer is require to perform
                    Aditional Services only when agreed to in writing by Engineer and Client, either through a modification of this Agreement or an
                    Additional Services Agreement.</span>
                    <span>• Substantive changes affecting our drawings and design after delivery to Client will constitute Additional Services subject to an
                    Additional Services Agreement.</span>
                    <span>• The following minimum hourly rates are to be used to compute Additional Services and/or hourly fees for services rendered:</span>
                    <div class="bullets_actual_">
                        <span><b>◊ Engineer/P.E. = </b> $200.00 per hour</span>
                        <span><b>◊ Project Manager =</b> $175.00 per hour</span>
                        <span><b>◊ Clerical =</b> $90.00 per hour</span>
                        <span><b>◊ Construction Site Visits (out of town) =</b> $1,500 per day / per discipline (excludes travel expenses)</span>
                        <span><b>◊ Construction Site Visits (local / in town) =</b> $400 per visit / per discipline</span>
                    </div>
                    
            </div>

            <div><h1 style="text-align:left; margin-top: 0px; font-size: 17px;">Time Frames:</h1></div>
            <div class="bullets_points">
                    <span>• Ardebili Engineering LLC has standard minimum time frames for City Comments, Submittal Review(s), RFI’s, and
                            Revisions. Please note that these are minimum time frames. Additional time may be required based on project size
                            and type of request.
                    </span>
                    <span>• City Comments: 5 business days from receipt of comments</span>
                    <span>• Submittal Review: 4 business days</span>
                    <span>• Requests for Information (RFI): 2 business days</span>
                    <span>• Revisions: 2 – 5 business days</span>
                    
            </div>

            <div><h1 style="text-align:left; margin-top: 0px; font-size: 17px;">Construction Administration:</h1></div>
            <div class="bullets_normal">
                  As outlined on page 1 of Proposal  
            </div>
           
            <div><h1 style="text-align:left; margin-bottom: 10px; font-size: 17px;">Payment Terms:</h1></div>
            <div style="font-weight:700; margin-bottom:25px; font-size:16px"> RETAINER</div>
            <div class="bullets_normal">
                Ardebili Engineering LLC reserves the right to invoice an advance payment on any and/or all projects as deemed necessary. Retainer
                payments will be deducted as a statement of credit from the final invoice. Retainers are non-refundable unless contract is terminated by
                Ardebili Engineering. <b>Failure to pay will delay project kick-off.</b>
            </div>

        </div>

         <div  style="page-break-after: always;">
            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">PROGRESS PAYMENTS</h1></div>
            <div class="porposal_text_normal">
                Ardebili Engineering LLC reserves the right to issue a progress invoice at any given point or points of the project. Failure to
                pay may result in suspension of Engineer’s services and delay in project completion. If a project goes on hold or is cancelled,
                Engineer will issue a progress invoice for the work completed to date.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">FULL PAYMENT</h1></div>
            <div class="porposal_text_normal">
                Ardebili Engineering LLC reserves the right to collect full payment before releasing contracted services. Failure to pay im-
                mediately will delay the release of set services.
            </div>

            <div><h1 style="text-align:left; margin-top: 7px; font-size: 17px;">REIMBURSABLE EXPENSES</h1></div>
            <div class="porposal_text_normal">
                Client shall pay for travel, plots, copies of plans, drawings, exhibits, and other material produced by Engineer and provided
                to Client, governmental agencies, contractors, or others at Client’s direction. Client shall pay for copies at Engineer's rates
                for copies produced in-house, and at cost plus 15% for reimbursable expenses and copies produced by others.
            </div>

            <div><h1 style="text-align:left; margin-top: 7px; font-size: 17px;">OVERDUE OR FAILURE TO PAY</h1></div>
            <div class="porposal_text_normal">
                Invoices are due upon presentation. If payment of an invoice amount is not received within 10 days of the due date, Engi-
                neer can (a) charge an accruing late interest at the rate of 1.5% of the outstanding balance per month, or the maximum rate

                permitted by law, whichever is lower and/or (b) suspend future work on the project until all payments are brought current
                and/or (c) withhold construction documents and/or (d) take appropriate legal actions against all parties involved. Nothing
                herein limits any other right or remedy that Engineer may be entitled to under law, and nothing affects Engineer’s right to
                terminate this Agreement in accordance with the Termination provision below.
            </div>

            <div><h1 style="text-align:left; margin-top: 7px; font-size: 17px;">PAYMENT METHODS</h1></div>
            <div class="porposal_text_normal">
                Acceptable payment methods include:<br>
                Credit Card<br>
                ACH / Wire Transfer<br>
                Check payable to Ardebili Engineering LLC<br><br>

                In performing its professional services hereunder, Engineer will observe that level of care and skill ordinarily exercised, under
                similar circumstances, by reputable members of its profession currently practicing in the same or similar locality. No warran-
                ty, express or implied, is made or intended by the Engineer’s undertaking herein or its performance of services hereunder.
            </div>

            <div><h1 style="text-align:left; margin-top: 7px; font-size: 17px;">LIEN RIGHTS</h1></div>
            <div class="porposal_text_normal">
                Ardebili Engineering reserves the right in pursuance with the lien laws specific to the state the project is located, to com-
                plete a preliminary notice on all projects. When doing so, it is not a lien as preliminary notices are not recorded documents.
                When Ardebili Engineering completes a preliminary notice, this is not a reflection on the integrity of any owner, contractor,
                or subcontractor. A preliminary notice is required in accordance with lien laws in order to protect payment rights.
            </div>

            <div><h1 style="text-align:left; margin-top: 7px; font-size: 17px;">RISK ALLOCATION</h1></div>
            <div class="porposal_text_normal">
                Considering the relative risks and benefits of this Agreement, Client and Engineer have agreed that, to the fullest extent
                permitted by law the total liability, in the aggregate, of the Engineer, agents, and consultants, and any of them, to Client and
                anyone claiming by, through or under Client, for any and all injuries, claims, losses, expenses, or damages arising out of the
                Engineer’s services, the Project of this Agreement, including but not limited to negligence, errors, omissions, strict liability
                or breach of contract of Engineer or Engineer’s officers, agents, and consultants, and any of them, shall not exceed the
                total
                compensation actually received by the Engineer under this Agreement.
            </div>

        </div>

        <div  style="page-break-after: always;">
           
            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">INDEMNIFICATION</h1></div>
            <div class="porposal_text_normal">
                The Client acknowledges the inherent risks to the Engineer in projects and the disparity between the Engineer’s fee and
                the Engineer’s potential liability for problems or alleged problems with such projects. Therefore, Engineer agrees, to the
                fullest extent permitted by law, to indemnify and hold harmless the Client, its officers, directors, employees and agents from
                and against any liabilities, damages and costs (including reasonable attorney’s fees and costs of defense) arising out of the

                death or bodily injury to any person or the destruction or damage to any property, only to the extent caused, during the per-
                formance of Services under this Agreement, by the negligent acts, errors or omissions of the Engineer or anyone for whom

                the Engineer is legally responsible, subject to any limitations of liability contained in this Agreement. The Client agrees, to
                the fullest extent permitted by law, to indemnify and hold harmless Engineer, its officers, directors, employees and agents
                from any liabilities, damages and costs (including reasonable attorney’s fees and costs of defense) to the extent caused
                by the negligent acts, errors or omissions of the Client, the Client’s contractors, consultants or anyone for whom Client is
                legally responsible.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">WAIVER OF SUBROGATION</h1></div>
            <div class="porposal_text_normal">
                To the extent damages are covered by property insurance, the Client and Engineer waive all rights against each other and
                against the contractors, consultants, agents, and employees of the other for damages. The Client or the Engineer, as ap-
                propriate, shall require of the contractors, consultants, agents, and employees of any of them similar waivers in favor of the
                other parties enumerated herein.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">WAIVER OF CONSEQUENTIAL DAMAGES</h1></div>
            <div class="porposal_text_normal">
                The Engineer and Client waive consequential damages for claims, disputes, or other matters in question, arising out of or
                relating to this Agreement. This mutual waiver is applicable, without limitation, to all consequential damages due to either
                party’s termination of this Agreement.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">CONFIDENTIALITY</h1></div>
            <div class="porposal_text_normal">
               Each party shall retain as confidential all information and data furnished to it by the other party which are designated in
                writing by such other party as confidential at the same time of transmission and said party shall not reveal such information
                to any third party without the prior permission of the disclosing party.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">REUSE OF DOCUMENTS</h1></div>
            <div class="porposal_text_normal">
                All documents including drawings and specifications prepared by Engineer pursuant to this Agreement are instruments of
                Engineer’s service, and Engineer retains all rights thereto, including but not limited to copyrights. They are not intended or

                represented to be suitable for reuse by Client or others on extensions or modifications of the Project, or for use on any oth-
                er project. Any reuse without specific written verification or adaptation by Engineer will be at Client’s sole risk and without

                liability or legal exposure to Engineer; and Client hereby releases Engineer, and shall indemnify, defend and hold harmless
                Engineer, from all claims, damages, losses and expenses including attorney’s fees arising out of or resulting therefrom. Any

                such verification or adaptation shall entitle Engineer to further compensation at rates to be agreed upon Client and Engi-
                neer.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">CONSTRUCTION RESPONSIBILITY</h1></div>
            <div class="porposal_text_normal">
                The Engineer is an independent contractor and shall not be responsible for the means, methods, procedures, techniques, or
                sequences of construction, nor safety precautions or programs on the job site, nor shall the Engineer be responsible for the
                Contractor’s failure to carry out the work in accordance with the contract documents. The Engineer shall be responsible for
                the Engineer’s negligent acts or omissions, subject t the limitations stated in this Agreement, but shall not have control over
                or charge of, and shall not be responsible for, acts or omissions of the construction contractor or of any other persons or
                entities performing portions of the construction work on the Project.
            </div>

        </div>

        <div  style="page-break-after: always;">
            
            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">CERTIFICATION OF MERIT</h1></div>
            <div class="porposal_text_normal">
                The Client shall make no claim for professional negligence, either directly or in a THIRD-PARTY claim, against Engineer unless the Client
                has first provided the Engineer with an affidavit containing a written certification executed by an independent design
                professional currently practicing in the discipline of the alleged defective design and licensed in the jurisdiction of the Project for at least

                the past ten (10) consecutive years. This certification shall: (a) contain the name and license number of the certifier and bear the profes-
                sional seal and signature of the certifier; (b) specify the basis of the duty of care allegedly owed by

                Engineer or its consultants to Client; (c) describe the elements of the professional standard of care applicable to the conduct or services

                of the Engineer or its consultants that gave rise to the claim; (d) describe in reasonable detail the facts constituting a breach of the appli-
                cable standard of care by the Engineer or its consultants; and (e) describe I reasonable detail the nature and quantum of resulting damage

                suffered by Client as a consequence of the alleged breach of the standard of care by the Engineer or its consultants.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">STATUTE OF LIMITATIONS</h1></div>
            <div class="porposal_text_normal">
                Causes of action between the parties to this Agreement pertaining to acts or failures to act shall be deemed to have accrued and the
                applicable statutes of limitations shall commence to run not later than either the date of Substantial Completion for acts or failures to act
                    occurring prior to Substantial Completion or the date of issuance of the final Certificate for Payment for acts or failures to act occurring
                    after Substantial Completion. In no event shall such statutes of limitations commence to run any later than the date when the Engineer’s
                    services are substantially completed.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">ALTERNATIVE DISPUTE RESOLUTION</h1></div>
            <div class="porposal_text_normal">
                In the event of any dispute, claim, question, or disagreement arising from or relating to this agreement or the breach thereof, the parties
                hereto shall use their best efforts to settle the dispute, claim, question, or disagreement. To this effect, they shall
                consult and negotiate with each other in good faith and, recognizing their mutual interests, attempt to reach a just and equitable solution
                satisfactory to both parties. If they do not reach such solution within a period of 60 days, then, upon notice by either party to the other,
                the parties agree to endeavor first to settle the dispute by mediation administered by the American Arbitration Association (AAA) under
                its Construction Industry Mediation Procedures before resorting to
                arbitration. Any unresolved controversy or claim arising from or relating to this Agreement or breach thereof, if not resolved during or
                before mediation, shall be resolved by arbitration administered by the AAA in accordance with its Construction Industry Arbitration Rules,
                and judgment on the award rendered by the arbitrator may be entered in any court having

                jurisdiction thereof. If all parties to the dispute agree, a mediator involved in the parties’ mediation may be asked to serve as the arbi-
                trator. The place of mediation and, if necessary, arbitration shall be Maricopa County, Arizona. The arbitration shall be heard by a single

                arbitrator appointed according to AAA rules. The award of the arbitration shall be accompanied by a reasoned opinion, unless the parties
                mutually waive this requirement in writing. This agreement shall be governed by the laws of the State of Arizona.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">ATTORNEY’S FEES</h1></div>
            <div class="porposal_text_normal">
               In the event of arbitration or litigation based upon, or arising out of, this Agreement, the losing party shall pay to the prevailing party all
                costs and expenses, including attorney’s fees, incurred by the prevailing party in the enforcing of any of the covenants and provisions of
                this Agreement and incurred in any action brought on account of the provisions hereof, and all such costs, expenses and attorney’s fees
                may be included in and form a part of any judgment entered in any proceeding brought on or under the Agreement. This Agreement shall
                be governed by the governing laws of the State of Arizona. The parties hereto stipulate and agree that any litigation based upon or arising
                out of this Agreement shall be filed in Maricopa County, Arizona.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">SUBCONTRACTING</h1></div>
            <div class="porposal_text_normal">
                Engineer has the right to subcontract any and all services, duties, and obligations of this Agreement.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">COMPLIANCE WITH CODES AND STANDARDS</h1></div>
            <div class="porposal_text_normal">
                In the performance of all services to be provided hereunder, Engineer and Client agree to put forth reasonable professional efforts to
                interpret and comply with codes, regulations, and laws in effect as of this Agreement date.
            </div>

        </div>

        <div  style="page-break-after: always;">
          
            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">TERMINATION</h1></div>
            <div class="porposal_text_normal">
                This agreement may be terminated by either party upon seven (7) days’ prior written notice, whether with or without cause.
                In the event of termination, the Engineer shall be compensated by Client for all services and expenses rendered to the date

                of termination plus reasonable termination costs to organize Engineer’s files and any reasonable expenses incurred by Engi-
                neer to coordinate efforts with another party.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">PROBABLE COST</h1></div>
            <div class="porposal_text_normal">
                Since the Engineer has no control over the cost of labor, materials, equipment, over a Contractor’s method of determining
                prices, or over competitive bidding or market conditions, budgeting, cost estimating, or opinions of probable project cost of
                construction shall not be provided by the Engineer as part of its services.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">HAZARDOUS MATERIALS</h1></div>
            <div class="porposal_text_normal">
                Any hazardous or toxic substances encountered by or associated with services provided by the Engineer for the Project
                shall at no time be or become the property of the Engineer. Arrangements for handling the hazardous or toxic substances

                shall be made by Client at Client’s cost. Any such arrangements that are made by Engineer, shall be made solely and exclu-
                sively on

                Client’s behalf and benefit and Client releases, and shall indemnify, defend and hold harmless Engineer from and against any
                and all liability which arises out of the hazardous or toxic substance handling.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">PROJECT DELAYS</h1></div>
            <div class="porposal_text_normal">
               The parties shall mutually agree upon a schedule for the furnishing of Engineer’s services in connection with the Project.
                The schedule is subject to reasonable adjustment for delays in providing information or approvals needed from Client, or
                third parties who must review and approve the documents prepared by Engineer, or public official having the right to review
                and approve the documents. The Client agrees that the Engineer is not responsible for damages arising directly or indirectly
                from
                any delays for causes beyond the Engineer’s control. For purposes of this Agreement, such causes include, but are not
                limited to, strikes or other labor disputes; severe weather disruptions or other natural disasters, fires, riots, war or other

                emergencies or acts of God, the effects of epidemics or pandemics, shutdowns or delays resulting from the actions of gov-
                ernmental authority, failure of any government agency to act in timely manner, failure of performances by the Client or the

                Client’s Contractors or Consultants, or discover of any hazardous substances or differing site conditions. In addition, if the
                delays
                resulting from any such causes increase the cost or time required by the Engineer to perform its services in an orderly and
                efficient manner, the Engineer shall be entitled to an equitable adjustment of schedule and/or compensation.
            </div>

            <div><h1 style="text-align:left; margin-top: 10px; font-size: 17px;">CLIENT’S RESPONSIBILITIES</h1></div>
            <div class="porposal_text_normal">
                The Client shall furnish surveys to describe physical characteristics, legal limitations and utility locations for the site of the
                Project, and a written legal description of the site. The surveys and legal information shall include, as applicable, grades and

                lines of streets, alleys, pavements and adjoining property and structures; designated wetlands; adjacent drainage; rights-
                of- way, restrictions, easements, encroachments, zoning, deed restrictions, boundaries and contours of the site; locations,

                dimensions, and other necessary data with respect to existing buildings, other improvements and trees; and information
                concerning available utility services and lines, both public and private, above and below grade, including inverts and depths.
                All the information on the survey shall be referenced to a Project benchmark. Unless expressly included in the Scope of
                Services for this Agreement, the Engineer is not responsible for the location and identification of utilities. The Engineer is
                entitled to rely on information furnished by the Client, or third parties at the Client’s direction, concerning any matter that is
                the subject of this Section.<br>
                The Client shall furnish services of geotechnical engineers, which may include test borings, test pits, determinations of soil

                bearing values, percolation tests, evaluations of hazardous materials, seismic evaluation, ground corrosion tests and resistiv-
                ity tests, including necessary operations for anticipating subsoil conditions, with written reports and appropriate recommen-
                dations.
            </div>

        </div>


        <div class="porposal_text_normal" style="margin-top:10px">
            The Client shall coordinate the services of its own consultants with those services provided by the Engineer. Upon the En-
            gineer’s request, the Client shall furnish copies of the scope of services in the contracts between the Client and the Client’s

            consultants. The Client shall furnish the services of consultants other than those designated as the responsibility of the
            Engineer in this Agreement or authorize the Engineer to furnish them as an Additional Service, when the Engineer requests
            such services and demonstrates that they are reasonably required by the scope of the Project. The Client shall require that
            its consultants and contractors maintain insurance, including professional liability insurance, as appropriate to the services
            or work provided.
            <br><br>
            The Client shall furnish tests, inspections and reports required by law or the contract documents for the Project, such as
            structural, mechanical, and chemical tests, tests for air and water pollution, and tests for hazardous materials.

            The Client shall furnish all legal, insurance and accounting services, including auditing services, that may be reasonably nec-
            essary at any time for the Project to meet the Client’s needs and interests.
            <br><br>
            The Client shall provide prompt written notice to the Engineer if the Client becomes aware of any fault or defect in the Proj-
            ect, including errors, omissions or inconsistencies in the Engineer’s instruments of service.
            <br><br>
            The Client shall include the Engineer in all communications with the Contractor that relate to or affect the Engineer’s ser-
            vices or professional responsibilities. The Client shall promptly notify the Engineer of the substance of any direct communi-
            cations between the Client and the Contractor affecting Engineer’s services.
        </div>

        <div><h1 style="text-align:left; margin-top: 0px; font-size: 17px;">END OF TERMS AND CONDITIONS</h1></div>

        <div><h1 style="text-align:center; margin-top: 10px; font-size: 17px; text-decoration: underline;">ACCEPTANCE OF AGREEMENT</h1></div>
        <div class="pdf_below_data" style="margin-top: 10px;">
            THIS AGREEMENT SHALL BE DEEMED WITHDRAWN IF NOT EXECUTED AND RETURNED BY CLIENT WITHIN NINTY (90) DAYS AFTER THE DATE HEREOF. THIS AGREEMENT IS SUBJECT TO ITS TERMS AND CONDITIONS FOLLOWING THE ACCEPTANCE OF AGREEMENT.
        </div>

        <?php
        if($asa->am_id != 0){
            $am_data = $this->db->query("SELECT * FROM users WHERE id = ".$asa->am_id)->result_object()[0];
            $am_name = $am_data->fname." ".$am_data->lname;
        } 
        ?>
        <div class="pdf_below_data">ARDEBILI ENGINEERING, LLC</div>
        <div class="pdf_below_data" style="position:relative; margin-top:30px">Signature:<span class="image_signature"><img src="<?php echo $signature;?>"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div class="pdf_below_data" style="margin-top:30px">Title: <small>Matthew Fabros<?php //echo $am_name; ?></small></div>
        <div class="pdf_below_data"></div>
       
        <div class="pdf_below_data">Client:</div>
        <table class="client_signature">
            <tbody>
                <tr>
                    <td>
                        <span class="pdf_below_data">
                            Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>
                    <td>
                        <span class="pdf_below_data">
                            Signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="pdf_below_data">
                            Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="pdf_below_data">
                            Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>


</body>
</html>
