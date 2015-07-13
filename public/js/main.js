function checkStatus (headerStatus) {
	if(headerStatus == '401')
	{
		//alert('not logged in');
		location.reload();
	}
}