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
        margin-top: 100px; 
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
        letter-spacing: 18px;
        padding-top:10px;
        margin-right:-15px;
    }

    #header,
#footer {
  position: fixed;
  left: 0;
    right: 0;
}

#header {
  top: 0;
}

#footer {
  bottom: 15px;
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
    color: #777;
}
.header_custom .logo img {
        max-width: 215px;
        margin-left: -13px;
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

        <div class="header">
            
            <div class="title"><h1>Additional Services Agreement</h1></div>
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
                                    <!-- <br /> -->
                                    <?php //echo ($asa->client_address); ?>
                                    <?php //if($client_data->city != null){echo ", ".$client_data->city;} ?>
                                    <?php //if($client_data->state != null){echo ", ".$client_data->state;} ?>
                                    <?php //if($client_data->zipcode != null){echo ", ".$client_data->zipcode;} ?>
                                </p>
                                <?php if($asa->client_project_number != ""){?>
                                    <br />
                                    <p><b class="heading_pdf" style="width:35%">Client Project #:</b> <?php echo $asa->client_project_number; ?></p>
                                <?php } ?>
                                <?php if($client_data->po_number != ""){?>
                                    <br />
                                    <p><b class="heading_pdf"  style="width:35%">PO Number:</b> <?php echo $client_data->po_number; ?></p>
                                <?php } ?>
                            </td>
                            <td>
                                <p><b class="heading_pdf">Date:</b> <?php echo date("m/d/Y", strtotime($client_data->start_date));?></p>
                                <br />
                                <p><b class="heading_pdf">Proposal #:</b> <?php echo $asa->pNumber; ?></p>
                                <br>
                                <p><b class="heading_pdf">Project #:</b> <?php echo $asa->asa_project_no; ?></p>
                                
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                <br>
                <div>
                    <div>
                        <p style="text-decoration: underline; margin-bottom:10px; clear: both;"><b>Project Scope:</b></p>
                        <p>
                            <b style="margin-bottom: 0px; float:left; width: 100%"><?php echo $asa->asa_project_name; ?></b>
                            <br />
                            <span style="font-weight:400">
                                <?php echo $client_data->service_description; ?>
                            </span>
                        </p>    
                    </div>
                </div>
            </div>
        </div>
        <table class="invoice" style="page-break-after: always;">
            <thead>
                <tr>
                    <td style="font-weight: bold; color: rgb(32, 32, 32); border-bottom: 1px solid #202020;">Tasks</td>
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
                    <td><b style="color: rgb(32, 32, 32); font-size:17px">Valid Till: &nbsp;&nbsp;<?php echo date("m/d/Y", strtotime($client_data->valid_date));?></b></td>
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

        <div class="pdf_below_data" style="margin-top: 20px;">
            THIS AGREEMENT SHALL BE DEEMED WITHDRAWN IF NOT EXECUTED AND RETURNED BY CLIENT WITHIN NINTY (90) DAYS AFTER THE DATE HEREOF. THIS AGREEMENT IS SUBJECT TO ITS TERMS AND CONDITIONS FOLLOWING THE ACCEPTANCE OF AGREEMENT.
        </div>

        <?php
        if($asa->am_id != 0){
            $am_data = $this->db->query("SELECT * FROM users WHERE id = ".$asa->am_id)->result_object()[0];
            $am_name = $am_data->fname." ".$am_data->lname;
        } 
        ?>
        <div class="pdf_below_data">Ardebili Engineering</div>
        <div class="pdf_below_data" style="position:relative; margin-top:30px">Signature:<span class="image_signature"><img src="<?php echo $signature;?>"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div class="pdf_below_data"  style="margin-top:30px">Title: <small>Matthew Fabros<?php //echo $am_name; ?></small></div>
        <div class="pdf_below_data">Acceptance of Agreement</div>
        <div class="pdf_below_data">
            THIS AGREEMENT SHALL BE DEEMED WITHDRAWN IF NOT EXECUTED AND RETURNED BY CLIENT WITHIN NINTY (90) DAYS AFTER THE DATE HEREOF. THIS AGREEMENT IS SUBJECT TO ITS TERMS AND CONDITIONS FOLLOWING THE ACCEPTANCE OF AGREEMENT.
        </div>
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
