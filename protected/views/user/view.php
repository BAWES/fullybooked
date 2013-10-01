<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScriptFile($baseUrl . '/js/jquery.columnizer.js');
//$cs->registerCoreScript('jquery');
if ($model->user_verif_code != '0' && $status == '') {
    $cs->registerScript('dialogmessage', "
            $.ajaxSetup({
                cache: false
            }); 
            $(document).ready(function(){
                $( '#dialog-message' ).dialog({
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( 'close' );
                        }
                    }
                });
            });
", CClientscript::POS_END);
}
?>

<section id='content' class='pad'>
    <?php
    if ($status) {
        echo "
    <div id='statusMessage' style='padding-left: 225px; padding-bottom: 50px; font-size: 21px'>
        $status
    </div>";
    }
    ?>
    <div id='leftColumn'>
        <div id='accountInfo'>
            <!-- Box -->
            <div><?php echo "
                <h1>Account Info</h1>
                <p><span>Name:</span> $model->user_name</p>
                <p><span>Date of Birth:</span> $model->user_birth_date</p>
                <p><span>Gender:</span> $model->user_gender</p>

                <a href='" . Yii::app()->createUrl("user/update") . "' class='edit-btn'>Edit</a>" ?>
            </div>
            <!-- Box -->
            <div>
                <h1>Change Password</h1>
                <form method="POST" action='<?php echo Yii::app()->createUrl("user/updatepassword") ?>'>
                    <table>
                        <tr>
                            <td>Old Password</td><td><input type='password' name="User[oldpass]" required/></td>
                        </tr>
                        <tr>
                            <td>New Password</td><td><input type='password' name="User[newpass]" required/></td>
                        </tr>
                        <tr>
                            <td>Confirm Password</td><td><input type='password' name="User[confpass]" required/></td>
                        </tr>
                    </table>
                    <input type='submit' value='Submit' class='btn-small'/>
                </form>
            </div>
            <!-- Box -->
            <div><?php echo"
                <h1>Contact Info</h1>
                <p><span>Email Address:</span> $model->user_email</p>
                <p><span>Mobile Number:</span> $model->user_mobile_num</p>

                <a href='" . Yii::app()->createUrl("user/updatemobile") . "' class='edit-btn'>Edit</a>" ?>
            </div>
            <!-- Box -->
            <div>
                <h1>Activation</h1>
                <?php
                if ($model->user_verif_code == '0') {
                    echo "Your account has already been activated.";
                } else {
                    ?>
                    <form method="GET" action='<?php echo Yii::app()->createUrl("user/activate") ?>'>
                        <table>
                            <tr>
                                <td>Activation Code</td><td><input type='text' name='activationCode' required/></td>
                            </tr>
                        </table>

                        <input type='submit' value='Submit' class='btn-small'/>
                        <p>Didn't get the activation code? <a href='<?php echo Yii::app()->createUrl("user/resendactivation") ?>' id='resend'>Resend Activation Code</a></p>
                    </form>
                <?php } ?>
            </div>

            <br class='clear'/>
        </div>
    </div>

    <div id='rightColumn'>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad1.jpg'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad2.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad4.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad3.gif'/></a>
    </div>

    <br class='clear'/>
</section>
<div id="dialog-message" title="Inactive Account" style="display:none;">
    <p>
        Please activate your account to be able to book appointments.
    </p>
</div>