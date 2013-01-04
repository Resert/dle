<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004,2012 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: shortsite.php
-----------------------------------------------------
 Назначение: WYSIWYG для новостей с сайта
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

$p_name = urlencode($member_id['name']);

if( $config['allow_site_wysiwyg'] == "1" ) {

if ( $user_group[$member_id['user_group']]['allow_image_upload'] ) $image_upload = "\"DLEUpload\", "; else $image_upload = "";

$shortarea = <<<HTML
<script type="text/javascript" src="engine/editor/scripts/language/{$lang['wysiwyg_language']}/editor_lang.js"></script>
<script type="text/javascript" src="engine/editor/scripts/innovaeditor.js"></script>
<script type="text/javascript">
var text_upload = "$lang[bb_t_up]";

jQuery(document).ready(function($){

	create_editor('');

	setTimeout(function() {
		
	    for(var i = 0;i < oUtil.arrEditor.length;i++) {
	      var oEditor = eval("idContent" + oUtil.arrEditor[i]);
	      var sHTML;
	      if(navigator.appName.indexOf("Microsoft") != -1) {
	        sHTML = oEditor.document.documentElement.outerHTML
	      }else {
	        sHTML = getOuterHTML(oEditor.document.documentElement)
	      }
	      sHTML = sHTML.replace(/FONT-FAMILY/g, "font-family");
	      var urlRegex = /font-family?:.+?(\;|,|")/g;
	      var matches = sHTML.match(urlRegex);
	      if(matches) {
	        for(var j = 0, len = matches.length;j < len;j++) {
	          var sFont = matches[j].replace(/font-family?:/g, "").replace(/;/g, "").replace(/,/g, "").replace(/"/g, "");
			  sFont=sFont.split("'").join('');
	          sFont = jQuery.trim(sFont);
	          var sFontLower = sFont.toLowerCase();
	          if(sFontLower != "serif" && sFontLower != "arial" && sFontLower != "arial black" && sFontLower != "bookman old style" && sFontLower != "comic sans ms" && sFontLower != "courier" && sFontLower != "courier new" && sFontLower != "garamond" && sFontLower != "georgia" && sFontLower != "impact" && sFontLower != "lucida console" && sFontLower != "lucida sans unicode" && sFontLower != "ms sans serif" && sFontLower != "ms serif" && sFontLower != "palatino linotype" && sFontLower != "tahoma" && sFontLower != 
	          "times new roman" && sFontLower != "trebuchet ms" && sFontLower != "verdana") {
	            sURL = "http://fonts.googleapis.com/css?family=" + sFont + "&subset=latin,cyrillic";
	            var objL = oEditor.document.createElement("LINK");
	            objL.href = sURL;
	            objL.rel = "StyleSheet";
	            oEditor.document.documentElement.childNodes[0].appendChild(objL)
	          }
	        }
	      }
	    }
	}, 100);

});

function create_editor( root ) {

	var use_br = false;
	var use_div = true;
	if ($.browser.mozilla || $.browser.webkit) { use_br = true; use_div = false; }
	
	oUtil.initializeEditor("wysiwygeditor",  {
		width: "98%", 
		height: "400", 
		css: root + "engine/editor/scripts/style/default.css",
		useBR: use_br,
		useDIV: use_div,
		groups:[
			["grpEdit1", "", ["FullScreen", "TextDialog", "FontDialog", "Subscript", "Superscript", "ForeColor", "BackColor", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "DLEPasteText", "Styles", "RemoveFormat"]],
			["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
			["grpEdit3", "", ["TableDialog", "DLESmiles", "FlashDialog", "CharsDialog", "Line", "BRK", "LinkDialog", "DLELeech", "ImageDialog", {$image_upload}"YoutubeDialog"]],
			["grpEdit4", "", ["DLEQuote", "DLECode", "DLEHide", "DLESpoiler", "CustomTag", "BRK", "DLEVideo", "DLEAudio", "DLEMedia", "DLETypograf", "Paragraph"]],
			["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo"]]
	    ],
		arrCustomButtons:[
			["DLEUpload", "media_upload('short_story', '{$p_name}', '{$id}', '1')", "{$lang['bb_t_up']}", "dle_upload.gif"],
			["DLESmiles", "modalDialog('"+ root +"engine/editor/emotions.php',250,160)", "{$lang['bb_t_emo']}", "btnEmoticons.gif"],
			["DLEPasteText", "modalDialog('"+ root +"engine/editor/scripts/common/webpastetext.htm',450,380)", "{$lang['paste_text']}", "btnPaste.gif"],
			["DLETypograf", "tag_typograf()", "{$lang['bb_t_t']}", "dle_tt.gif"],
			["DLEQuote", "DLEcustomTag('[quote]', '[/quote]')", "{$lang['bb_t_quote']}", "dle_quote.gif"],
			["DLECode", "DLEcustomTag('[code]', '[/code]')", "{$lang['bb_t_code']}", "dle_code.gif"],
			["DLEHide", "DLEcustomTag('[hide]', '[/hide]')", "{$lang['bb_t_hide']}", "dle_hide.gif"],
			["DLESpoiler", "DLEcustomTag('[spoiler]', '[/spoiler]')", "{$lang['bb_t_spoiler']}", "dle_spoiler.gif"],
			["DLELeech", "DLEcustomTag('[leech=http://]', '[/leech]')", "{$lang['bb_t_leech']}", "dle_leech.gif"],
			["DLEVideo", "modalDialog('"+ root +"engine/editor/scripts/common/webbbvideo.htm',400,250)", "{$lang['bb_t_video']} (BB Codes)", "dle_video.gif"],
			["DLEAudio", "modalDialog('"+ root +"engine/editor/scripts/common/webbbaudio.htm',400,200)", "{$lang['bb_t_audio']} (BB Codes)", "dle_mp3.gif"],
			["DLEMedia", "modalDialog('"+ root +"engine/editor/scripts/common/webbbmedia.htm',400,250)", "{$lang['bb_t_yvideo']} (BB Codes)", "dle_media.gif"]
		],
		arrCustomTag: [
			["{$lang['bb_t_br']}", "{PAGEBREAK}"],
	        ["{$lang['bb_t_p']}", "[page=1][/page]"]
		]
		}
	);	
};

function tag_typograf() {

	ShowLoading('');

	var oEditor = oUtil.oEditor;
	var obj = oUtil.obj;

	obj.saveForUndo();
    oEditor.focus();
    obj.setFocus();

	var txt = obj.getXHTMLBody();

	$.post("engine/ajax/typograf.php", {txt: txt}, function(data){
	
		HideLoading('');
	
		obj.loadHTML(data); 
	
	});
};
</script>
<style type="text/css">
.wseditor table td { 
	padding:0px;
	border:0;
}
</style>
<div class="wseditor"><textarea id="short_story" name="short_story" class="wysiwygeditor" style="width:98%;height:200px;">{short-story}</textarea></div>
HTML;

} else {

$js_array[] = "engine/editor/jscripts/tiny_mce/jquery.tinymce.js";

if ( $user_group[$member_id['user_group']]['allow_image_upload'] ) $image_upload = "media_upload('short_story', '{$p_name}', '{$id}', '2');"; else $image_upload = "return false;";

$shortarea = <<<HTML
<script type="text/javascript">
var text_upload = "$lang[bb_t_up]";
$(function(){

	$('textarea.wysiwygeditor').tinymce({
		script_url : 'engine/editor/jscripts/tiny_mce/tiny_mce_gzip.php',
		theme : "advanced",
		skin : "cirkuit",
		language : "{$lang['wysiwyg_language']}",
		width : "98%",
		height : "350",
		plugins : "advhr,advimage,emotions,inlinepopups,insertdatetime,media,searchreplace,paste",
		relative_urls : false,
		convert_urls : false,
		remove_script_host : false,
		media_strict : false,
		dialog_type : 'window',
		extended_valid_elements : "noindex,div[align|class|style|id|title]",
		custom_elements : 'noindex',
		paste_auto_cleanup_on_paste : false,
		paste_text_use_dialog: true,

		// Theme options
		theme_advanced_buttons1 : "fontselect,fontsizeselect,|,sub,sup,|,charmap,advhr,|,insertdate,inserttime,|,nonbreaking,dle_quote,dle_code,dle_hide,cleanup",
		theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,dle_spoiler,|,link,dle_leech,|,forecolor,backcolor,|,removeformat,cleanup",
		theme_advanced_buttons3 : "pastetext,pasteword,search,|,outdent,indent,|,undo,redo,|,dle_upload,image,media,dle_mp,dle_tube,dle_mp3,emotions,|,dle_break,dle_page,code",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
	   	plugin_insertdate_dateFormat : "%d-%m-%Y",
	    plugin_insertdate_timeFormat : "%H:%M:%S",

		// Example content CSS (should be your site CSS)
		content_css : "{$config['http_home_url']}engine/editor/css/content.css",

		setup : function(ed) {
		        // Add a custom button
			ed.addButton('dle_quote', {
			title : '{$lang['bb_t_quote']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_quote.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[quote]' + ed.selection.getContent() + '[/quote]');
			}
	           });

			ed.addButton('dle_hide', {
			title : '{$lang['bb_t_hide']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_hide.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[hide]' + ed.selection.getContent() + '[/hide]');
			}
	           });

			ed.addButton('dle_code', {
			title : '{$lang['bb_t_code']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_code.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[code]' + ed.selection.getContent() + '[/code]');
			}
	           });

			ed.addButton('dle_spoiler', {
			title : '{$lang['bb_t_spoiler']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_spoiler.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[spoiler]' + ed.selection.getContent() + '[/spoiler]');
			}
	           });

			ed.addButton('dle_break', {
			title : '{$lang['bb_t_br']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_break.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceInsertContent',false,'{PAGEBREAK}');
			}
	           });

			ed.addButton('dle_page', {
			title : '{$lang['bb_t_p']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_page.gif',
			onclick : function() {
				ed.execCommand('mceReplaceContent',false,"[page=1]{\$selection}[/page]");
			}
	           });

			ed.addButton('dle_leech', {
			title : '{$lang['bb_t_leech']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_leech.gif',
			onclick : function() {
				ed.execCommand('mceReplaceContent',false,"[leech=http://]{\$selection}[/leech]");
			}
	           });

			ed.addButton('dle_mp', {
			title : '{$lang['bb_t_video']} (BB Codes)',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_mp.gif',
			onclick : function() {
				ed.execCommand('mceInsertContent',false,"[video=http://]");
			}
	           });

			ed.addButton('dle_tube', {
			title : '{$lang['bb_t_yvideo']} (BB Codes)',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_tube.gif',
			onclick : function() {
				ed.execCommand('mceInsertContent',false,"[media=http://]");
			}
	           });

			ed.addButton('dle_upload', {
			title : '{$lang['bb_t_upload']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_upload.gif',
			onclick : function() {

				{$image_upload}
			}
	           });

			ed.addButton('dle_mp3', {
			title : '{$lang['bb_t_audio']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_mp3.gif',
			onclick : function() {
				ed.execCommand('mceInsertContent',false,"[audio=http://]");
			}
	           });
   		 }


	});


});
</script>
    <textarea id="short_story" name="short_story" class="wysiwygeditor" style="width:98%;height:200px;">{short-story}</textarea>
HTML;


}

?>