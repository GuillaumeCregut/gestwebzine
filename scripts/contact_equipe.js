function envoi_message()
{
	let Sujet=document.getElementById('sujet').value;
	let Message=document.getElementById('taMessage').value;
	if((Sujet=='')|(Message==''))
	{
		alert('Veuillez remplir le sujet et le message');
		return false;
	}
	if (confirm('Voulez-vous envoyer le message ?'))
	{
		let formulaire=document.forms[0];
		formulaire.submit();
	}
}