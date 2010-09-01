var NS4 = navigator.appVersion.indexOf("Nav") > 0 && parseInt(navigator.appVersion) == 4;
var currentActive = false;

function showFeed(id)
{
	if(NS4) return false;

	if (currentActive && currentActive != id) //Hide the current active description
	{
		obj = document.getElementById(currentActive);
		obj.style.display = "none";
	}

	obj = document.getElementById(id); //Show the selected searchresult
	obj.style.display = (obj.style.display=="none" ? "block" : "none");
	currentActive = id;
}

function printThis(id,action,query)
{
	var params="width=570,height=580,toolbar=no,directories=no,status=no,location=no,menubar=no,resizable=no,scrollbars=yes";
	url = "index.php?print=1";
	if (action)
	{
		url+= "&action="+ action +"";
	}
	if (action == 'search')
	{
		url+= "&query="+ query +"";
	}
	if (!action)
	{
		url+="&page="+ id +"";
	}
	
	PopupWindow = window.open(url,'print',params);
	PopupWindow.focus();
}
function SendToPrinter()
{
	window.print();
//	self.close();
	return false;
}
function validate_form( formName )
{
  var errors  = "";
  var empty   = "";
  var numbers = "";
  var emails  = "";
  var list_selection = "";

  if( fields2validate )
  {
    for( var i = 0; i < fields2validate.length; i++ )
    {
	  name = fields2validate[i][0];
      type = fields2validate[i][1];
      label = fields2validate[i][2];
      if( type != 'checkbox' && type != 'list' && type != 'postcode' ) str = eval('document.'+ formName +'.'+ name +'.value');
      switch( type )
      {
        case('text'):
          if( isblank( str ) ) 
          {
            empty +='-'+ label +"\n"; 
          }
          break;
        case('textarea'):
          if( isblank( str ) ) 
          {
            empty +='-'+ label +"\n"; 
          }
          break;
        case('number'):
          if( isNaN( parseInt( str ) ) )
          {
            numbers +='-'+ label +"\n";
          }
          break;
        case('mail'):
          if( !isEmailValid( str ) )
          {
            emails +='-'+ label +"\n";
          }
          break;
        case('combobox'):
          if(  eval('document.'+ formName +'.'+ name +'.selectedIndex') == '0' ) 
          {
            list_selection +='-'+ label +"\n"; 
          }
          break;
        case('radio'):
          if( !checkRadio( formName, name ) )
          {
            list_selection +='-'+ label +"\n"; 
          }
          break;
        case('checkbox'):
          if( !checkCheckBox( formName, name ) )
          {
            list_selection +='-'+ label +"\n"; 
          }
          break;
        case('list'):
          if( !checkList( formName, name ) )
          {
            list_selection +='-'+ label +"\n"; 
          }
          break;
        case('postcode'):
          if(  eval('document.'+ formName +'.'+ name +'.selectedIndex') == '0' ) 
          {
            list_selection +='-'+ label +"\n"; 
          }
          break;
        case('country'):
          if(  eval('document.'+ formName +'.'+ name +'.selectedIndex') == '0' ) 
          {
            list_selection +='-'+ label +"\n"; 
          }
          break;
      }
    }
  }


  if( empty ) errors+= "\nThe following required field(s) are empty:\n"+ empty;
  if( numbers ) errors+="\nThe following field(s) should be numbers:\n"+ numbers;
  if( emails ) errors+="\nThe following email field(s) are not valid:\n"+ emails;
  if( list_selection ) errors+="\nYou haven't select an element in:\n"+ list_selection;

  if( errors ) 
  {
    str ="____________________________________________________________\n";
    str+="                                                            \n";
    str+="The form was not submitted because of the following error(s)\n";
    str+="Please correct these error(s) and re-submit the form\n";
    str+="____________________________________________________________\n";
    str+= errors;
    alert( str );
    return false;
  }
  return true;
}
function checkRadio( formName, fieldName )
{
    var field = eval('document.'+ formName +'.'+ name );
    for( var i = 0; i < field.length; i++ )
    {
      if( field[i].checked ) return true;
    }
    return false;
}
function checkList( formName, fieldName )
{
  var fields = eval('document.'+ formName );
  for( var i = 0; i < fields.elements.length; i++ )
  {
    if( fields.elements[i].name == fieldName+'[]' ) 
    {
      var n = fields.elements[i].selectedIndex;
      if( n == 0 || n == -1 ) return false;
      else return true;
    }
  }
  return false;
}
function checkCheckBox( formName, fieldName )
{
  var fields = eval('document.'+ formName );
  for( var i = 0; i < fields.elements.length; i++ )
  {
    if( fields.elements[i].name == fieldName+'[]' ) 
    {
      if( fields.elements[i].checked ) return true;
    }
  }
  return false;
}
function isblank(s)
{
  for( var i = 0; i < s.length; i++ )
  {
    var c = s.charAt(i);
    if(( c !=' ') && ( c!='\n') && ( c!='\t')) return false;
  }
  return true;
}

function isEmailValid(emailStr)
{
  var emailPat=/^(.+)@(.+)$/
  var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
  var validChars="\[^\\s" + specialChars + "\]"
  var quotedUser="(\"[^\"]*\")"
  var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
  var atom=validChars + '+';
  var word="(" + atom + "|" + quotedUser + ")";
  var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
  var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
  var matchArray=emailStr.match(emailPat);

  if (matchArray==null) return false;

  var user=matchArray[1];
  var domain=matchArray[2];

  if (user.match(userPat)==null) return false;
  var IPArray=domain.match(ipDomainPat)
  if (IPArray!=null) {
      // this is an IP address
      for (var i=1;i<=4;i++) {
        if (IPArray[i]>255) return false
      }
      return true
  }

  var domainArray=domain.match(domainPat)
  if (domainArray==null) return false

  var atomPat=new RegExp(atom,"g")
  var domArr=domain.match(atomPat)
  var len=domArr.length
  if (domArr[domArr.length-1].length<2 || domArr[domArr.length-1].length>3) return false;

  if (len<2) return false
  return true;
}