<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'employee-form',
	'enableAjaxValidation'=>false,
//	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'branch_id'); ?>
		<?php echo $form->dropDownList($model,'branch_id',$dropdown); ?>
		<?php echo $form->error($model,'branch_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'employee_name'); ?>
		<?php echo $form->textField($model,'employee_name',array('size'=>60,'maxlength'=>160)); ?>
		<?php echo $form->error($model,'employee_name'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'employee_workstart'); ?>
		<?php 
		$this->widget('application.extensions.timepick.EJuiDateTimePicker',array(
   		 'model'=>$model,
   		 'attribute'=>'employee_workstart',
   		 'timePickerOnly'=>true,
   		 'options'=>array(
       		'timeFormat' => 'h:mm tt',
        	'ampm'=>true,
    		),
		));  
		?>
		<?php echo $form->error($model,'employee_workstart'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'employee_workend'); ?>
		<?php 
		$this->widget('application.extensions.timepick.EJuiDateTimePicker',array(
   		 'model'=>$model,
   		 'attribute'=>'employee_workend',
   		 'timePickerOnly'=>true,
   		 'options'=>array(
       		'timeFormat' => 'h:mm tt',
        	'ampm'=>true,
    		),
		));  
		?>
		<?php echo $form->error($model,'employee_workend'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'employee_breakstart'); ?>
		<?php 
		$this->widget('application.extensions.timepick.EJuiDateTimePicker',array(
   		 'model'=>$model,
   		 'attribute'=>'employee_breakstart',
   		 'timePickerOnly'=>true,
   		 'options'=>array(
       		'timeFormat' => 'h:mm tt',
        	'ampm'=>true,
    		),
		));  
		?>
		<?php echo $form->error($model,'employee_breakstart'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'employee_breakend'); ?>
		<?php 
		$this->widget('application.extensions.timepick.EJuiDateTimePicker',array(
   		 'model'=>$model,
   		 'attribute'=>'employee_breakend',
   		 'timePickerOnly'=>true,
   		 'options'=>array(
       		'timeFormat' => 'h:mm tt',
        	'ampm'=>true,
    		),
		));  
		?>
		<?php echo $form->error($model,'employee_breakend'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'employee_dayoff'); ?>
		<?php echo $form->dropDownList($model,'employee_dayoff',$daysDropdown); ?>
		<?php echo $form->error($model,'employee_dayoff'); ?>
	</div>
	
	<div id='servicesSelection' style='margin-top:15px;'>
		<div class="row">
			<h3><?php echo $form->labelEx($model,'services_input'); ?></h3>
			<?php echo $form->checkBoxList($model,'services_input',$allServices); ?>
			<?php echo $form->error($model,'services_input'); ?>
		</div>
	</div>
	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->