<?php
$this->breadcrumbs=array(
	'Categories'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Category', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('category-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Categories</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'category_name',
		array(
			'name'=>'totalProviders',
			'value'=>'$data->totalProviders',
			'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
