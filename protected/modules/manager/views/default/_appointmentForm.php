<?php 
/* @var $form CActiveForm */
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'employee-form',
	'enableAjaxValidation'=>false,
//	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
	<?php echo $form->errorSummary($model); ?>
        <?php if(!isset($_GET['id'])){ ?>
	<div class="row" id="branch">
		<?php echo $form->labelEx($model,'branch_id'); ?>
		<?php echo $form->dropDownList($model, 'branch_id', CHtml::listData($branches, 'branch_id', 'location.location_name')); ?>
		<?php echo $form->error($model,'branch_id'); ?>
	</div>
        <?php } ?>
        <div class="row" id="service">
		<?php echo $form->labelEx($model,'services.service_id'); ?>
                <?php echo CHtml::dropDownList('service', 0, CHtml::listData($services, 'service_id', 'service_name'), array('prompt'=>'Select a service')); ?>
		<?php echo $form->error($model,'branch_id'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->