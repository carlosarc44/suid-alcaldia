<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <!-- Facebook sharing information tags -->
  <meta property="og:title" content="*|MC:SUBJECT|*" />

  <title>Notificaciones SUID</title>
  <style type="text/css">
    /* Client-specific Styles */
    #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
    body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

    /* Reset Styles */
    body{margin:0; padding:0;}
    img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
    table td{border-collapse:collapse;}
    #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* Template Styles */

    /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: COMMON PAGE ELEMENTS /\/\/\/\/\/\/\/\/\/\ */

      /**
      * 
      *  background color
      *  Set the background color for your email. You may want to choose one that matches your company's branding.
      * 
      */
      body, #backgroundTable{
        background-color:#FAFAFA;
      }

      /**
      * 
      *  email border
      *  Set the border for your email.
      */
      #templateContainer{
        border: 1px solid #DDDDDD;
      }

      /**
      * 
      *  heading 1
      *  Set the styling for all first-level headings in your emails. These should be the largest of your headings.
      *  heading 1
      */
      h1, .h1{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:34px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
      }

      /**
      * 
      *  heading 2
      *  Set the styling for all second-level headings in your emails.
      *  heading 2
      */
      h2, .h2{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:30px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
      }

      /**
      * 
      *  heading 3
      *  Set the styling for all third-level headings in your emails.
      *  heading 3
      */
      h3, .h3{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:18px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
      }

      /**
      * 
      *  heading 4
      *  Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
      *  heading 4
      */
      h4, .h4{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:22px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
      }

      /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: HEADER /\/\/\/\/\/\/\/\/\/\ */

      /**
      *  Header
      *  header style
      *  Set the background color and border for your email's header area.
      *  header
      */
      #templateHeader{
        background-color:#FFFFFF;
        border-bottom:0;
      }

      /**
      *  Header
      *  header text
      *  Set the styling for your email's header text. Choose a size and color that is easy to read.
      */
      .headerContent{
        color:#202020;
        font-family:Arial;
        font-size:34px;
        font-weight:bold;
        line-height:100%;
        padding:0;
        text-align:center;
        vertical-align:middle;
      }

      /**
      *  Header
      *  header link
      *  Set the styling for your email's header links. Choose a color that helps them stand out from your text.
      */
      .headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
        color:#336699;
        font-weight:normal;
        text-decoration:underline;
      }

      #headerImage{
        height:auto;
        width:100% !important;
      }

      /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: MAIN BODY /\/\/\/\/\/\/\/\/\/\ */

      /**
      *  Body
      *  body style
      *  Set the background color for your email's body area.
      */
      #templateContainer, .bodyContent{
        background-color:#FFFFFF;
      }

      /**
      *  Body
      *  body text
      *  Set the styling for your email's main content text. Choose a size and color that is easy to read.
      *  main
      */
      .bodyContent div{
        color:#505050;
        font-family:Arial;
        font-size:18px;
        line-height:150%;
        text-align:left;
      }

      /**
      *  Body
      *  body link
      *  Set the styling for your email's main content links. Choose a color that helps them stand out from your text.
      */
      .bodyContent div a:link, .bodyContent div a:visited, /* Yahoo! Mail Override */ .bodyContent div a .yshortcuts /* Yahoo! Mail Override */{
        color:#336699;
        font-weight:normal;
        text-decoration:underline;
      }

      /**
      *  Body
      *  data table style
      *  Set the background color and border for your email's data table.
      */
      .templateDataTable{
        background-color:#FFFFFF;
        border:1px solid #DDDDDD;
      }
      
      /**
      *  Body
      *  data table heading text
      *  Set the styling for your email's data table text. Choose a size and color that is easy to read.
      */
      .dataTableHeading{
        background-color:#0082ff;
        color:#fff;
        font-family:Helvetica;
        font-size:14px;
        font-weight:bold;
        line-height:150%;
        text-align:left;
      }

      /**
      *  Body
      *  data table heading link
      *  Set the styling for your email's data table links. Choose a color that helps them stand out from your text.
      */
      .dataTableHeading a:link, .dataTableHeading a:visited, /* Yahoo! Mail Override */ 
      .dataTableHeading a .yshortcuts /* Yahoo! Mail Override */{
        color:#FFFFFF;
        font-weight:bold;
        text-decoration:underline;
      }
      
      /**
      *  Body
      *  data table text
      *  Set the styling for your email's data table text. Choose a size and color that is easy to read.
      */
      .dataTableContent{
        border-top:1px solid #DDDDDD;
        border-bottom:0;
        color:#202020;
        font-family:Helvetica;
        font-size:12px;
        line-height:150%;
        text-align:left;
      }

      /**
      *  Body
      *  data table link
      *  Set the styling for your email's data table links. Choose a color that helps them stand out from your text.
      */
      .dataTableContent a:link, .dataTableContent a:visited, /* Yahoo! Mail Override */ .dataTableContent a .yshortcuts /* Yahoo! Mail Override */{
        color:#202020;
        font-weight:bold;
        text-decoration:underline;
      }

      /**
      *  Body
      *  button style
      *  Set the styling for your email's button. Choose a style that draws attention.
      */
      .templateButton{
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        background-color:#0082ff;
        border:0;
        border-collapse:separate !important;
        border-radius:3px;
      }

      /**
      *  Body
      *  button style
      *  Set the styling for your email's button. Choose a style that draws attention.
      */
      .templateButton, .templateButton a:link, .templateButton a:visited, /* Yahoo! Mail Override */ .templateButton a .yshortcuts /* Yahoo! Mail Override */{
        color:#FFFFFF;
        font-family:Arial;
        font-size:15px;
        font-weight:bold;
        letter-spacing:-.5px;
        line-height:100%;
        text-align:center;
        text-decoration:none;
      }

      .bodyContent img{
        display:inline;
        height:auto;
      }

      /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: FOOTER /\/\/\/\/\/\/\/\/\/\ */

      /**
      *  Footer
      *  footer style
      *  Set the background color and top border for your email's footer area.
      *  footer
      */
      #templateFooter{
        background-color:#FFFFFF;
        border-top:0;
      }

      /**
      *  Footer
      *  footer text
      *  Set the styling for your email's footer text. Choose a size and color that is easy to read.
      *  footer
      */
      .footerContent div{
        color:#707070;
        font-family:Arial;
        font-size:12px;
        line-height:125%;
        text-align:center;
      }

      /**
      *  Footer
      *  footer link
      *  Set the styling for your email's footer links. Choose a color that helps them stand out from your text.
      */
      .footerContent div a:link, .footerContent div a:visited, /* Yahoo! Mail Override */ .footerContent div a .yshortcuts /* Yahoo! Mail Override */{
        color:#336699;
        font-weight:normal;
        text-decoration:underline;
      }

      .footerContent img{
        display:inline;
      }

      /**
      *  Footer
      *  utility bar style
      *  Set the background color and border for your email's footer utility bar.
      *  footer
      */
      #utility{
        background-color:#FFFFFF;
        border:0;
      }

      /**
      *  Footer
      *  utility bar style
      *  Set the background color and border for your email's footer utility bar.
      */
      #utility div{
        text-align:center;
      }

      #monkeyRewards img{
        max-width:190px;
      }
    </style>
  </head>
  <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    {{''; $pathSuid =  public_path().'//img/SUID_transp.png'; }}
  {{''; $pathCabezote =  public_path().'//img/email.png'; }}
  {{'';  $registros = json_decode($datos, true);}}
    <center>
      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
        <tr>
          <td align="center" valign="top" style="padding-top:20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainer">
              <tr>
                <td align="center" valign="top">
                  <!-- // Begin Template Header \\ -->
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader">
                    <tr>
                      <td class="headerContent">

                        <!-- // Begin Module: Standard Header Image \\ -->
                        <img src="<?php echo $message->embed($pathCabezote);?>" style="width:100%;" id="headerImage campaign-icon" mc:label="header_image" mc:edit="header_image" mc:allowdesigner mc:allowtext />
                        <!-- // End Module: Standard Header Image \\ -->

                      </td>
                    </tr>
                  </table>
                  <!-- // End Template Header \\ -->
                </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <!-- // Begin Template Body \\ -->
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody">
                    <tr>
                      <td valign="top">

                        <!-- // Begin Module: Standard Content \\ -->
                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                          <tr>
                            <td valign="top" class="bodyContent">
                              <div mc:edit="std_content00">
                                <h4 class="h3">
                                  {{'';
                                        $nombresUsuario = $registros["nombre"];
                                        $nombre = explode(" ", $nombresUsuario);
                                  }}
                                  {{ ucfirst(strtolower($nombre[0])) }},
                                  </h4>
                                Le informamos que La Oficina de Control Disciplinario realizó remisión a juzgamiento del proceso <strong>{{$registros["radicado"]}}</strong>.                                  
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top" class="bodyContent">
                              <div mc:edit="std_content00">
                                Proceso: <strong>{{$registros["radicado"]}}</strong>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top" class="bodyContent">
                              <div mc:edit="std_content00">
                                Etapa Actual: <strong>Avoca Conocimiento (Juzgamiento)</strong>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td align="center" valign="top" style="padding-top:20px;">
                              <br>
                              <table border="0" cellpadding="15" cellspacing="0" class="templateButton">
                                <tr>
                                  <td valign="middle" class="templateButtonContent">
                                    <div mc:edit="std_content02">
                                      <a href="http://gestion.manizales.gov.co/suid/public/procesos/reparto-juzgamiento" target="_blank" style="color:#fff; text-decoration: none;">Ingresar al SUID</a>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>

                          <tr>
                            <td align="center" valign="top">
                            <p><em>Mensaje automático, por favor no responder</em></p>
                            <hr />
                              <!-- // Begin Template Footer \\ -->
                              <table border="0" cellpadding="10" cellspacing="0" width="100%" id="templateFooter">
                                <tr>
                                  <td valign="top" class="footerContent">

                                    <!-- // Begin Module: Transactional Footer \\ -->
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                      <tr>
                                        <td valign="top">
                                          <div mc:edit="std_footer">
                                           <h2 style="font-style: normal;font-weight: 400;margin-bottom: 1px;margin-top: 0;font-size: 14px;line-height: 28px;font-family: Ubuntu,sans-serif;color: #0082ff; text-align:center;"><em style="text-align:justify">AVISO LEGAL</em></h2>
                                           <p align="justify">               
                                            <em>El contenido de este mensaje es para uso exclusivo de su destinatario intencional, puede contener informaci&#243;n legalmente protegida, privilegiada o confidencial.</em><br />
                                            <em>Si usted no es el destinatario por favor notif&#237;quenos de inmediato y elimine el mensaje. Cualquier retenci&#243;n, revisi&#243;n sin autorizaci&#243;n, divulgaci&#243;n, reenv&#237;o, copia, impresi&#243;n o uso inadecuado de este mensaje est&#225; estrictamente prohibida y sancionada legalmente.</em><br />
                                            <em>Los mensajes, archivos o datos que contiene el correo han sido sometidos a revisi&#243;n por un antivirus, no obstante usted puede ponerse en contacto con nosotros en caso de encontrar cualquier anomal&#237;a.</em>
                                          </p>

                                        </div>
                                      </td>
                                    </tr>
                                  </table>
                                  <!-- // End Module: Transactional Footer \\ -->

                                </td>
                              </tr>
                            </table>
                            <!-- // End Template Footer \\ -->
                          </td>
                        </tr>

                      </table>
                      <!-- // End Module: Standard Content \\ -->
                    </td>
                  </tr>
                </table>
                <!-- // End Template Body \\ -->
              </td>
            </tr>
            <tr>
              <td align="center" valign="top">
                <!-- // Begin Template Footer \\ -->
                <table border="0" cellpadding="10" cellspacing="0" width="100%" id="templateFooter">
                  <tr>
                    <td valign="top" class="footerContent">

                      <!-- // Begin Module: Transactional Footer \\ -->
                      <table border="0" cellpadding="10" cellspacing="0" width="100%">
                        <tr>
                          <td valign="top">
                            <div mc:edit="std_footer">
                              <em>&copy; {{date('Y')}} | SUID | Sistema Unificado de Investigaciones Disciplinarias
                                <br/>
                                Todos los derechos reservados.</em>
                                <br/>
                                Oficina de Control Disciplinario Interno
                                <br/>
                                <strong>Alcaldía de Manizales</strong>
                                <br/>

                              </div>
                            </td>
                          </tr>                          
                        </table>
                        <!-- // End Module: Transactional Footer \\ -->

                      </td>
                    </tr>
                  </table>
                  <!-- // End Template Footer \\ -->
                </td>
              </tr>
            </table>
            <br />
          </td>
        </tr>
      </table>
    </center>
  </body>
  </html>