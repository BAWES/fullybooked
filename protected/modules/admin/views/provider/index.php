<?php
$this->breadcrumbs=array(
	'Providers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create New Provider', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('salon-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Providers</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salon-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'category_search',
			'value'=>'$data->category->category_name',
			'filter'=>CHtml::listData(Category::model()->findAll(),'category_name','category_name'),
		),
		'provider_name',
		//'provider_booking_startdate',
		//'provider_booking_enddate',
            'provider_contact_name',
		'provider_contact_number',
            'bookingStart',
            'bookingEnd',
		
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
