<section id='content' class='pad'>

    <div id='leftColumn'>
        <h1>Welcome to <?php echo $model->provider_name ?></h1>
        <img src='<?php echo $model->logoLarge ?>' id='providerLogo'/>

        <div id='description'>
            <h2>Description</h2>
            <p>
                Casa Spa brings the day spa into the comfort of your home.
                Our licensed beauty therapists transport everything needed to transform your space into a spa. 
                We offer a full range of spa treatments, spa packages and parties to meet the needs of unique individuals. 
                Our licensed beauty therapists transport everything needed to transform your space into a spa.
            </p>
        </div>

        <div id='location'>
            <h2>
                Location
            </h2>

            <ul class="show">
                <?php
                foreach ($model->branches as $branch) {
                    echo "<li>" . $branch->location->location_name . " <span>$branch->branch_address</span></li>";
                }
                ?>
            </ul>
        </div>

        <div id='services'>
            <h2>Services</h2>
            <?php
            $services = $model->services;
            if (count($services) == 0) {
                echo 'No available services';
            } else {
                foreach ($services as $service) {
                    echo "<div>
                    <h3>$service->service_name</h3>
                    <h4><span>Duration $service->service_duration Minutes</span>&nbsp;&nbsp;-&nbsp;&nbsp;$service->service_price KD</h4>
                    <p>$service->service_description</p>
                    </div>";
                }
            }
            ?>
        </div>

        <div id='booking' hidden>
            <h2>Select Date, Person & Time</h2>

            <div id='legends'>
                <p class='available'>Available</p>
                <p class='unavailable'>Unavailable</p>
            </div>

            <div id="datepicker"></div>

            <div id='employees'>

            </div>
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