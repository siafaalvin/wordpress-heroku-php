<?php
if( is_front_page() ) { ?>
    <form action="<?php //bloginfo('siteurl'); ?>" name="searchform" id="searchform" method="post">
        <div>
            <input type="text" id="s" name="s" value="" ng-model="search_key" placeholder="Free summaries (80 word limit) anyone can edit." />
            <i></i>
            <input type="submit" value="" id="searchsubmit" ng-click="post_filter_by_search($event, search_key, 'index')"/>
        </div>
        <input type="hidden" name="search_in" id="search_in" value="index" />
    </form><?php
} else{  ?>
	 <form action="<?php //bloginfo('siteurl'); ?>" name="searchform" id="searchform_page" method="get">
        <div>
            <input type="text" id="s" name="s" value="" /><i></i>
            <input type="submit" value="" id="searchsubmit" class="searchsubmit_page"/>
        </div>
        <input type="hidden" name="search_in" id="search_in" value="page" />
    </form><?php
} ?>
