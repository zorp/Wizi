function changeTree(loading)
{
	if (loading == 'documents')
	{
		treefrmfrm.treefrm.location.href = 'tree/tree.php';
		treefrmfrm.bottomfrm.location.href = 'bottom.php';
		mainfrm.contentfrm.location.href = 'about:blank';
		//treefrm.document.documents.src = 'graphics/treemenu_images/documents_on.gif';
		//treefrm.document.media.src = 'graphics/treemenu_images/media_off.gif';
		//treefrm.document.newsletters.src = 'graphics/treemenu_images/newsletters_off.gif';
	}
	else if (loading == 'media')
	{
		treefrmfrm.treefrm.location.href = 'mediatree/tree.php';
		treefrmfrm.bottomfrm.location.href = 'mediatree/importnav.php';
		mainfrm.contentfrm.location.href = 'about:blank';
		//treefrm.document.documents.src = 'graphics/treemenu_images/documents_off.gif';
		//treefrm.document.media.src = 'graphics/treemenu_images/media_on.gif';
		//treefrm.document.newsletters.src = 'graphics/treemenu_images/newsletters_off.gif';
	}
	else if (loading == 'newsletter')
	{
		treefrmfrm.treefrm.location.href = 'newsletter/tree.php';
		treefrmfrm.bottomfrm.location.href = 'newsletter/treenav.php';
		mainfrm.contentfrm.location.href = 'about:blank';
		//treefrm.document.documents.src = 'graphics/treemenu_images/documents_off.gif';
		//treefrm.document.media.src = 'graphics/treemenu_images/media_off.gif';
		//treefrm.document.newsletters.src = 'graphics/treemenu_images/newsletters_on.gif';
	}
}