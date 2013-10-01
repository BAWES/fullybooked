<?php
Yii::app()->clientScript->registerCoreScript('jquery');
$cs = Yii::app()->getClientScript();
$cs->registerScript('resendActivation',"
$.ajaxSetup({
	cache: false
});
$(document).ready(function(){
	var loadUrl = '".$this->createUrl('user/resendActivation')."';
	var resendResult = $('#resendResult');
	$('#resend').click(function(){
		resendResult.text('Loading..').load(loadUrl);
	});
});
",CClientscript::POS_END);

?>


<?php
$this->breadcrumbs=array(
	'Account'=>array('view'),
	'Activate Account',
);
?>

<h1>Activate Account</h1>

<p>Please insert the activation code you have received via SMS on <b><?php echo $userModel->user_mobile_num; ?></b> (<a href='<?php echo $this->createUrl('user/updateMobile'); ?>'>Change your mobile number</a>)</p>

<form>
Activation Code: <input type='text' name='activationCode'/> 
<?php
if($error) echo "<br/><span style='color:red'>$error</span>";
?>
<br/>
<input type='submit' value='Activate'/>
</form>

<br/>
<p>Didn't get the activation code? <a href='<?php echo Yii::app()->createUrl("user/resendactivation") ?>' id='resend'>Resend Activation Code</a> </p>
<p style='color:green;' id='resendResult'></p>
