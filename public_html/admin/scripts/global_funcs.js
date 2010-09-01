/* START OF Functions used by the tree in backend */
function blankcontent ()
{
	mainfrm.contentfrm.location.href = 'about:blank';
}
function toggling( id )
{
    document.tree.toggle.value = id;
    document.tree.submit();
}
function zoomTo( id )
{
    document.tree.root.value = id;
    document.tree.submit();
}
function shownode( id )
{
    obj = document.getElementById( id );
    obj.style.visibility ='visible';
}
function hidenode( id )
{
    obj = document.getElementById( id );
    obj.style.visibility ='hidden';
}
function lightup( name, id )
{
    document[ name + id ].src = 'graphics/'+ name +'_on.gif'; 
}
function grayout( name, id )
{
    document[ name + id ].src = 'graphics/'+ name +'_off.gif'; 
}
function renaming( id )
{
    document.tree.rename.value= id;
    document.tree.submit();
}
function removing( id )
{
    if( confirm('Delete?') )
    {
        document.tree.remove.value= id;
        document.tree.submit();
    }
}
function removingNewsletter( id, name )
{
    if( confirm('Do you want to delete the newsletter "'+name+'"?') )
    {
        if( confirm('Are you sure you want to delete the newsletter "'+name+'"?') )
		    {
					document.tree.remove.value= id;
        	document.tree.submit();
				}
    }
}
function removingDoc( id, name )
{
    if( confirm('Do you want to delete the document "'+name+'"?') )
    {
        if( confirm('Are you sure you want to delete the document "'+name+'"?') )
		    {
					document.tree.remove.value= id;
      	  document.tree.submit();
				}
    }
}
function removingMedia( id, name )
{
    if( confirm('Do you want to delete the media "'+name+'"?') )
    {
        if( confirm('Are you sure you want to delete the media "'+name+'"?') )
		    {
					document.tree.remove.value= id;
      	  document.tree.submit();
				}
    }
}
/* END OF Functions used by the tree in backend */

function showMyprop()
{
	if (MyhideStatus == "hidden")
	{
		document.getElementById('Myprop').style.display = "block";
		document.propsImg.src = '../graphics/downnode_big.gif';
		document.propsImg.alt = 'Hide Title, Keywords and Description';
		MyhideStatus = "show"
	}
	else
	{
		document.getElementById('Myprop').style.display = "none";
		document.propsImg.src = '../graphics/upnode_big.gif';
		document.propsImg.alt = 'Show Title, Keywords and Description';
		MyhideStatus = "hidden"
	}
}
