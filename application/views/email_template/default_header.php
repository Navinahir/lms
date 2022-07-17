<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet"/>
        <style type="text/css">
            body{
                /*font-family: arial;*/
                font-size: 14px;
                line-height: 20px;
                background-color: #f0f0f0;
                margin: 0;
                padding: 0;
                padding:3% 0;
                /*font-family: 'Pacifico';*/
            }
            #main_tbl{
                margin:0 auto;
                box-shadow: 0px 3px 10px 2px #ccc;
                font-family: arial;     
                /*font-family: 'Pacifico',arial;*/
                -webkit-border-radius: 15px 15px 0 0;
            }
            #main_tbl *{
                font-family: arial;
                /*font-family: 'Pacifico',arial;*/
            }
            .btn_href{
                padding:15px 30px;
                background-color: #3ca446;
                color:#fff !important;
                font-weight: bold;
                text-decoration: none;
                border-radius: 5px;
            }
            p{margin: 0;}

        </style>
    </head>
    <body>
        <table cellpadding="0" cellspacing="0" width="500" id="main_tbl">
            <tbody style="box-shadow: 0px 3px 10px 2px #ccc;-webkit-border-radius: 15px 15px 0 0;">
                <tr style="background-color:#0079c1;position: relative;background-image: url(<?= base_url() ?>assets/images/backgrounds/panel_bg.png);height:75px">
                    <td style="text-align:center;-webkit-border-radius: 15px 15px 0 0;">
                        <a href="<?php echo base_url(); ?>" style="display: inline-block;padding-top: 10px;padding-bottom: 10px;color: #fff; text-decoration: none;">
                            <h1 style="font-weight: 600;font-size: 26px;position:relative;top:1px;margin:7px"><?php echo (isset($title) && $title != '') ? $title : 'Always Reliable Keys' ?></h1>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" style="padding: 20px 30px 20px 30px;" colspan="2">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family: arial;">