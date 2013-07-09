{if $affichage.notification.niveau > 0}
<div align='center' width='100%' id='notification' style="position:relative;width:500px;margin-left: auto; margin-right: auto">
	<br/>
<table width='60%' 
{if $affichage.notification.niveau == 1} bgcolor='green' {/if}
{if $affichage.notification.niveau == 2} bgcolor='yellow' {/if}
{if $affichage.notification.niveau == 3} bgcolor='red' {/if}
>
	<tr>
		<td>Message</td>
		<td>{$affichage.notification.message}</td>
	</tr>
	<tr>
		<td>niveau</td>
		<td>{$affichage.notification.niveau}</td>
	</tr>
	<tr>
		<td>redirect</td>
		<td><a href='{$affichage.notification.redirect}'>redirect</a></td>
	</tr>
	<tr>
		<td>redirect time</td>
		<td>{$affichage.notification.redirect_time}</td>
	</tr>	
</table>
	<br/>

{if $affichage.notification.redirect_time != '' }
<meta http-equiv="refresh" content="{$affichage.notification.redirect_time};URL='{$affichage.notification.redirect}'">
{/if}
</div>
{/if}