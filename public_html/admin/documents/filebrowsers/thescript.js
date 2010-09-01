function OpenFile( fileUrl ){
	window.top.opener.SetUrl( encodeURI( fileUrl ).replace( '#', '%23' ) ) ;
	window.top.close() ;
	window.top.opener.focus() ;
}

function chooseFlash (imgPath,width,height){
	window.top.opener.SetUrl( encodeURI( imgPath ).replace( '#', '%23' ), width, height );
	window.top.close() ;
	window.top.opener.focus() ;
}

function chooseImage (imgPath,width,height,alt){
	window.top.opener.SetUrl( encodeURI( imgPath ).replace( '#', '%23' ), width, height, alt );
	window.top.close() ;
	window.top.opener.focus() ;
}