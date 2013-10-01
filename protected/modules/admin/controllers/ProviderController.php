<?php

class ProviderController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionChangePassword($id)
	{
		$model=$this->loadModel($id);
		$model->scenario="changePw";

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Provider']))
		{
			$model->attributes=$_POST['Provider'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->provider_id));
		}

		$this->render('changePassword',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Provider('create');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Provider']))
		{
			$model->attributes=$_POST['Provider'];
			$pic = CUploadedFile::getInstance($model,'provider_logo');
			
			if($model->validate()){
				if($pic!==null){
					$image = WideImage::load($pic->getTempName());
					$resized = $image->resize(500, 500);
					$thumb = $image->resize(100, 100);
				
					$fileName = time().rand(0,200).".".$pic->getExtensionName();
					$model->provider_logo=$fileName;
				}
			
				if($model->save(false)){
					if($pic!==null){
						$filePath = Yii::app()->basePath.'/../images/provider/logo/'.$fileName;
						$thumbPath = Yii::app()->basePath.'/../images/provider/thumb/'.$fileName;
						$resized->saveToFile($filePath);
						$thumb->saveToFile($thumbPath);
					}
					$this->redirect(array('view','id'=>$model->provider_id));
				}
			}
			
		}
		
		//generate category dropdown list
		$categoryDropdown = CHtml::listData(Category::model()->findAll(),'category_id','category_name');

		$this->render('create',array(
			'model'=>$model,
			'categoryDropdown'=>$categoryDropdown,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Provider']))
		{
			$oldPic = $model->provider_logo;
			$pic = CUploadedFile::getInstance($model,'provider_logo');
			$model->attributes=$_POST['Provider'];
			
			if($pic===null) $model->provider_logo = $oldPic;
			
			if($model->validate()){
				if($pic!==null){
					//delete old image
					if(!empty($oldPic)){
						$oldPic1 = Yii::app()->basePath."/../images/provider/logo/".$oldPic;
						$oldPic2 = Yii::app()->basePath."/../images/provider/thumb/".$oldPic;
						
						if(file_exists($oldPic1)) unlink($oldPic1);
						if(file_exists($oldPic2)) unlink($oldPic2);
					}
					//save new image
					$image = WideImage::load($pic->getTempName());
					$resized = $image->resize(450, 300);
					$thumb = $image->resize(100, 100);
				
					$fileName = time().rand(0,200).".".$pic->getExtensionName();
					$model->provider_logo=$fileName;				
				}
			
			
				if($model->save(false)){
					if($pic!==null){
						$filePath = Yii::app()->basePath.'/../images/provider/logo/'.$fileName;
						$thumbPath = Yii::app()->basePath.'/../images/provider/thumb/'.$fileName;
						$resized->saveToFile($filePath);
						$thumb->saveToFile($thumbPath);
					}
					$this->redirect(array('view','id'=>$model->provider_id));
				}
			}
		}
		
		//generate category dropdown list
		$categoryDropdown = CHtml::listData(Category::model()->findAll(),'category_id','category_name');

		$this->render('update',array(
			'model'=>$model,
			'categoryDropdown'=>$categoryDropdown,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}


	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Provider('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Provider']))
			$model->attributes=$_GET['Provider'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Provider::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='provider-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
