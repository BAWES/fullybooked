<?php
$this->breadcrumbs=array(
	'Categories'=>array('index'),
	$model->category_name,
);

$this->menu=array(
	array('label'=>'Manage Categories', 'url'=>array('index')),
	array('label'=>'Create Category', 'url'=>array('create')),
	array('label'=>'Update Category', 'url'=>array('update', 'id'=>$model->category_id)),
	array('label'=>'Delete Category', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->category_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo $model->category_name; ?> Category</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'category_name',
	),
)); ?>

<br/><br/>
<h2>Providers in this category:</h2>
<ul>
<?php
foreach($model->providers as $provider){
	echo "<li><a href='".$this->createUrl('provider/view',array('id'=>$provider->provider_id))."'>".$provider->provider_name."</a></li>";
}
?>
</ul>