<form method='post'>

<table border='0' align='center'>
<tr>
<td rowspan='4'>
	<img src="http://img.photobucket.com/albums/v242/Corbic/ohnoes.gif">
</td>
</tr>
<tr>
	<td>Utilisateur</td>
	<td><input type='text' name='utilisateur' value='{$affichage.request.utilisateur|default:''}'></td>
</tr>
<tr>
	<td>Mot de passe</td>
	<td><input type='password' name='motdepasse' value='{$affichage.request.motdepasse|default:''}'></td>
</tr>
<tr>
	<td></td>
	<td><input type='submit' name=''></td>
</tr>
</table>
</form>