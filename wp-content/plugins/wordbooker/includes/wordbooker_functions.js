function Wordbooker_Download_Code(t,bid) {
xmlhttp=null
// code for Mozilla, etc.
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest()
  }
// code for IE
else if (window.ActiveXObject)
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
  }
if (xmlhttp!=null)
  {
 xmlhttp.open("GET",wpcontent+"/wordbooker/includes/wordbooker_get_friend.php?name="+t+"&userid="+userid,false)
  xmlhttp.send(false)
  xxx=xmlhttp.responseText.replace("\n","");
xxx=xxx.replace(" ","");
  }
else
  {
  alert("Your browser does not support XMLHTTP.")
  }
return(xxx);
}
function Wordbooker_getFBFriend(tag) {
code_id=Wordbooker_Download_Code(tag,userid);
junk=document.getElementById("FriendID");
junk2=document.getElementById("wordbooker_tag_list");
junk2.value=junk2.value+code_id + ':' + junk.value + ';';
junk3=document.getElementById("wordbooker_tag_list_names");
junk3.value=junk3.value+junk.value+', ';
junk.value="";
return;
}

function Wordbooker_removeFBFriend(tag) {
code_id=Wordbooker_Download_Code(tag,userid);
junk=document.getElementById("FriendID");
junk2=document.getElementById("wordbooker_tag_list");
junk2.value=junk2.value.replace(code_id + ':' + junk.value + ';','');
junk3=document.getElementById("wordbooker_tag_list_names");
junk3.value=junk3.value.replace(junk.value + ', ','');
junk.value="";
return;
}

function Wordbooker_getFBFriend2(tag) {
code_id=Wordbooker_Download_Code(tag,userid);
junk=document.getElementById("FriendID");
junk3=document.getElementById("content");
tinyMCE.execCommand('mceReplaceContent', false, ' [wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ');
// The next bit works if you are in HTML raw mode
junk3.value=junk3.value+'[wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ';
junk.value="";
return;
}

function Wordbooker_removeFBFriend2(tag) {
code_id=Wordbooker_Download_Code(tag,userid);
junk=document.getElementById("FriendID");
junk3=document.getElementById("content");
tinyMCE.execCommand('mceReplaceContent', '[wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ', '');
// The next bit works if you are in HTML raw mode
junk3.value=junk3.value.replace('[wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ','');
junk.value="";
return;
}

function Wordbooker_getFBFriend3(tag) {
code_id=Wordbooker_Download_Code(tag,userid);
junk=document.getElementById("FriendID");
junk2=document.getElementById("wordbooker_tag_list");
junk3=document.getElementById("content");
tinyMCE.execCommand('mceReplaceContent', false, '[wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ');
// The next bit works if you are in HTML raw mode
junk3.value=junk3.value+'[wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ';
junk2.value=junk2.value+code_id + ':' + junk.value + ';';
junk4=document.getElementById("wordbooker_tag_list_names");
junk4.value=junk4.value+junk.value+', ';
junk.value="";
return;
}

function Wordbooker_removeFBFriend3(tag) {
code_id=Wordbooker_Download_Code(tag,userid);
junk=document.getElementById("FriendID");
junk2=document.getElementById("wordbooker_tag_list");
junk3=document.getElementById("content");
var editor = tinymce.get('content'); // use your own editor id here - equals the id of your textarea
var content = editor.getContent();
content = content.replace('[wb_fb_f name="' + tag + '" id="' + code_id +  '"]', ' ');
editor.setContent(content);
// The next bit works if you are in HTML raw mode
junk3.value=junk3.value.replace('[wb_fb_f name="' + tag + '" id="' + code_id +  '"]' + ' ','');
junk2.value=junk2.value.replace(code_id + ':' + junk.value + ';','');
junk4=document.getElementById("wordbooker_tag_list_names");
junk4.value=junk4.value.replace(junk.value + ', ','');
junk.value="";
return;
}