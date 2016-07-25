{*
 * Copyright (c) 2004-2013 OIC Group, Inc.
 *
 * This file is part of Tienda
 *
 * Tienda is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}

<div class="module text howitworks">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h2>{$moduletitle}</h2>{/if}
    {$myloc=serialize($__loc)}
    {if $items[0]->title}<h2>{$items[0]->title}</h2>{/if}
    {permissions}
       <div class="item-actions">
            {if $permissions.edit == 1}
                {if $myloc != $items[0]->location_data}
                    {if $permissions.manage == 1}
                        {icon action=merge id=$items[0]->id title="Merge Aggregated Content"|gettext}
                    {else}
                        {icon img='arrow_merge.png' title="Merged Content"|gettext}
                    {/if}
                {/if}
                {icon action=edit record=$items[0]}
            {/if}
            {if $permissions.delete == 1}
                {icon action=delete record=$items[0]}
            {/if}
        </div>
    {/permissions}
    <div class="steps">
		
      	<div class="row-fluid">
      		<div class="span5">
      			<img src="{$smarty.const.PATH_RELATIVE}files/step1.jpg" alt="" />
      		</div>
			<div class="span7">
				<h3>Browse</h3>
				<p>Browse Tienda like it’s your favorite magazine. We have over 900 items available - and counting, all at the lowest possible price.</p>
			</div>
      	</div>
    </div>

	<div class="steps">
		
      	<div class="row-fluid">
      		<div class="span5">
      			<img src="{$smarty.const.PATH_RELATIVE}files/step2.jpg" alt="" />
      		</div>
			<div class="span7">
				<h3>Choose</h3>
				<p>Something caught your eye? Add it to your cart and continue shopping until you have all of the items you want. The item you want is not on our list? <link to tell us lol>Tell us</link>!</p>
			</div>
      	</div>
    </div>

	<div class="steps">
		
      	<div class="row-fluid">
      		<div class="span5">
      			<img src="{$smarty.const.PATH_RELATIVE}files/step3.jpg" alt="" />
      		</div>
			<div class="span7">
				<h3>Checkout</h3>
				<p>Done shopping and ready to seal the deal? Simply click on Checkout and fill-in the necessary details needed for us to find you. You can checkout as a guest if you are in a hurry, but we highly recommend registering on Tienda so that we can save your preferences and help you browse faster on your next visit!</p>
			</div>
      	</div>
    </div>

	<div class="steps">
		
      	<div class="row-fluid">
      		<div class="span5">
      			<img src="{$smarty.const.PATH_RELATIVE}files/step4.jpg" alt="" />
      		</div>
			<div class="span7">
				<h3>Our Turn!</h3>
				<p>Once we receive your order, we will carefully pack your goods and deliver them right at your doorstep the very next day!</p>
			</div>
      	</div>
    </div>

	<div class="steps">
		
      	<div class="row-fluid">
      		<div class="span5">
      			<img src="{$smarty.const.PATH_RELATIVE}files/step5.jpg" alt="" />
      		</div>
			<div class="span7">
				<h3>Payment</h3>
				<p>Our friendly team will gladly accept your payment once the goods are delivered to your satisfaction.</p>
			</div>
      	</div>
    </div>
</div>
