<!DOCTYPE html>

<html>
<head>
    <style>
        .login-card {
            padding: 40px;
            max-width: 300px;
            background-color: #FFFFFF;
            margin: 20px auto;
            border-radius: 2px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3) !important;
            overflow: hidden;
        }

        .text-center {
            text-align: center;
            text-wrap: normal;
        }

        .text-justify {
            text-align: justify;
            text-wrap: normal;
        }

        .main-bknd {
            background: #EEEEEE repeat center center fixed;
            background-size: cover !important;
            font-family: 'Roboto', sans-serif;
            height: 100%;
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        .app-icon {
            height: 40px;
            width: 40px;        
        }

        .container {
            padding-top: 20px;
        }

        .row {
            width: 100% !important;
        }

        .colm-1 {
            width: 50% !important;
        }        
    </style>
</head>
<body class="main-bknd">
    <div class="container">
        <div class="login-card">
            <div class="row">
                <table>
                    <tr class="row">
                        <td class="colm-1">
                            <div class="cursor-pointer">
                                <!-- <a class="app-url-click" href="javascript:void(0)"> -->
                                    <!-- <img class="app-icon"
                                    title="Hrms" src="http://www.larch.in/images/icon-crm.png"> -->
                                    <h2 class="text-center">&nbsp;HRMS&nbsp;</h2>
                                    
                                    <!-- </a>   -->                              
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                    <label class="h2" id="mailContent">
                        To change your password, click <a class='hrefLink' href="{{$user['resetToken']}}">here</a>
                        or paste the following link into your browser:
                    </label>
                    <br />
                    <label class="text-justify">
                        <br />
                        <?php echo $user['resetToken']; ?>
                    </label>
                    <br />
                    <label>
                        <br />
                        This link will expire in 4 hours, so be sure to use it right away.
                    </label>
                    <br />
                    <footer class="text-center">
                        <p style="color:#000000">&copy; {{date('Y')}} - Hrms Application</p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</body>
</html>