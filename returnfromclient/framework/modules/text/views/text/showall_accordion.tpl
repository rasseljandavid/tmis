<div class="module text showall-accordion">
    <h2>{$moduletitle}</h2>

    {if $config.moduledescription != ""}
        {$config.moduledescription}
    {/if}

    <div class="accordion" id="accordion2">
        {foreach from=$items item=text name=items}
			<div class="accordion-group">
			    <div class="accordion-heading">
			      	<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse{$text->id}">
						{$text->title}
				 	</a>
					{permissions}
						<div class="item-actions">
						   {if $permissions.edit == 1}
								{icon action=edit record=$text}
							{/if}
							{if $permissions.delete == 1}
								{icon action=delete record=$text}
							{/if}
						</div>
                    {/permissions}
				</div>
				<div id="collapse{$text->id}" class="accordion-body collapse">
			      <div class="accordion-inner">
			        	{$text->body}
			      </div>
			    </div>
			</div>
	{/foreach}
</div>