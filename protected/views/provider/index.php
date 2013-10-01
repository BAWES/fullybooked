<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/jquery.columnizer.js');
$cs->registerScript('filter', "
        function filter(){
            var category = $('#categoryMenu option:selected').val();
            var location = $('#locationMenu option:selected').val();
            var url = '". $this->createUrl("directory/")."';
            if(category != ''){
                url += '?category=' + category;
                if(location != ''){
                    url += '&location=' + location;
                }
            }
            else if(location != ''){
                url += '?location=' + location;
            }
            window.location = url;
            
        }
        //Directory Page Settings
    var directory = $('#directory');
    var directoryHtml = directory.html();
    var activeLetter = '';
    $('#directoryNav a').click(function(){
    	$('#directoryNav a').removeClass('active');
    	$(this).addClass('active');
    	
    	var letterShow = $(this).attr('href');
    	
    	if(activeLetter == letterShow){
    		$(this).removeClass('active');
    		activeLetter = '';
    		
    		directory.html(directoryHtml);
    		directory.children().addClass('dontsplit');
    		directory.columnize({columns:3});
    	}
    	else{
    		directory.html(directoryHtml);
    		
    		directory.children().hide();
    		directory.find(letterShow).show();
    		activeLetter = letterShow;
    	}
    	
    	return false;
    });
    
    directory.children().addClass('dontsplit');
    directory.columnize({columns:3});
    
    //End Directory Page Settings
	",CClientScript::POS_END);
?>
<section id='content'>

    <ul id='directoryNav'>
        <?php echo $letterNavigation ?>
        <br class='clear'/>
    </ul>

    <section id='filters'>
        <div>
            Industry
            <select id="categoryMenu" onchange="filter()">
                <option></option>
                <?php
                echo $categoryNavigation
                ?>
            </select>
        </div>

        <div>
            Area
            <select id="locationMenu" onchange="filter()">
                <option></option>
                <?php
                echo $locationNavigation
                ?>
            </select>
        </div>

        <div id='availableTag'/>
        Available For Booking
        </div>

        <br class='clear'/>
    </section>
    <div id='leftColumn'>
        <ol id='directory'>
            <?php echo $output ?>
        </ol>
    </div>

    <div id='rightColumn'>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad1.jpg'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad2.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad4.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad3.gif'/></a>
    </div>

    <br class='clear'/>
</section>