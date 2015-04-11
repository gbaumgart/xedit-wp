<?php
/*
Plugin Name: File Editor
Description: Adds powerful editor for theme and plugin in the admin panel.
Version: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
class XEditWPPlugin {



	function XEditWPPlugin() {

		$debug = true;
		$useCDN = true;


		if(!$debug) {
			if(!$useCDN) {
				add_action( 'admin_footer-theme-editor.php', array( $this, 'admin_footer_release' ) );
				add_action( 'admin_footer-plugin-editor.php', array( $this, 'admin_footer_release' ) );
			}else{
				add_action( 'admin_footer-theme-editor.php', array( $this, 'admin_footer_cdn' ) );
				add_action( 'admin_footer-plugin-editor.php', array( $this, 'admin_footer_cdn' ) );
			}
			return;
		}else{
			add_action( 'admin_footer-theme-editor.php', array( $this, 'admin_footer' ) );
			add_action( 'admin_footer-plugin-editor.php', array( $this, 'admin_footer' ) );
		}
	}

	function admin_footer_cdn() {
			$CDN_URL =$cdnUrl = 'http://www.4dojo.org/xedit-wp/1.2/xedit-wp/';
		?>

		<script type="text/javascript" charset="utf-8">

			var dojoConfig = {
				async: true,
				parseOnLoad: false,
				isDebug: 0,
				baseUrl: "<?php echo $CDN_URL . '/client/src/xfile' ?>",
				tlmSiblingOfDojo: 0,
				useCustomLogger:false,
				packages: [{"name":"dojo","location":"dojo"},{"name":"dojox","location":"dojox"},{"name":"dijit","location":"dijit"},{"name":"cbtree","location":"cbtree"},{"name":"xfile","location":"xfile"},{"name":"xide","location":"xide"},{"name":"dgrid","location":"dgrid"},{"name":"dstore","location":"dstore"},{"name":"xwordpress","location":"xwordpress"},{"name":"xedit","location":"xedit"}],
				has:{'xlog':true,'xide-beans':true},
				locale:'en'
			};

			var resourceVariables = {
				ACE:"<?php echo $CDN_URL . '/client/src/xfile/ext/ace/'?>",
				APP_URL:"<?php echo $CDN_URL. '/client/src/'?>"
			}

		</script>

		<link rel='stylesheet' href='<?php echo $CDN_URL .'/client/src/xfile/xedit/resources/app-xedit.css'?>' type='text/css' media='all' />
		<link rel='stylesheet' href='<?php echo $CDN_URL .'/client/src/css/elusive-icons/elusive-webfont.css'?>' type='text/css' media='all' />

		<script type="text/javascript" src="<?php echo $CDN_URL .'/client/src/xfile/dojo/xedit.js'?>"></script>

		<script src="<?php echo $CDN_URL .'/client/src/xfile/xedit/run.js' ?>"></script>

		<script src="<?php echo $CDN_URL .'/client/src/xfile/ext/underscore.min.js' ?>"></script>
		<script src="<?php echo $CDN_URL .'/client/src/xfile/ext/keypress.js' ?>"></script>

		<script src="<?php echo $CDN_URL .'/client/src/xfile/ext/ace/ace.js' ?>"></script>
		<script src="<?php echo $CDN_URL .'/client/src/xfile/ext/ace/ext-language_tools.js' ?>"></script>
		<script src="<?php echo $CDN_URL .'/client/src/xfile/ext/ace/ext-modelist.js' ?>"></script>
		<script src="<?php echo $CDN_URL .'/client/src/xfile/ext/ace/ext-spellcheck.js' ?>"></script>


		<style type="text/css">

			#template div {
				/* Need to reset margin here from core styles since it destroys
				   every single div contained in the editor... */
				margin-right: 0px;
			}
			#template #editor, #template > div {
				/* ... then redefine it in a much more scoped manner. */
				margin-right: 210px;
			}
			#template div #newcontent {
				width: 100%;
			}

			.dijitMenuItemHover td, .dijitMenuItemHover {
				background: none repeat scroll 0 0 rgba(45, 15, 18, 0.53);
				color: #fff;
			}
			.options-menu a {
				color: #c7c7c7;
			}
			.options-menu{
				background-color: #000000;
			}
			#wp-ace-editor {
				position: relative;
				height: 500px;
				font-size: 12px;
				border: 1px solid #BBB;
				border-radius: 3px;
			}
			.ace_editor {
				font-family: Consolas, Menlo, "Liberation Mono", Courier, monospace !important;
				height: 100%;
				width: 100%;
			}
			#wp-ace-editor-controls table td {
				vertical-align: center;
				padding: 5px;
			}
			.actionToolbarButtonElusive {
				font-family: Fontawesome;
			}
			#documentation {
				margin-top: 100px;
			}
			.dijitIcon{
				font-family: Fontawesome;
			}

			.acediff-gutter {
				/*background-color: #efefef;*/
				border-left: 1px solid #999999;
				border-right: 1px solid #999999;
				flex: 0 0 60px;
				overflow: hidden;
				width: 60px;
				height: inherit;
				position: absolute;
				-moz-box-sizing: border-box;
			}
			.acediff-gutter svg {
				/*background-color: #efefef;*/

			}
			#flex-container {
				display: flex;
				display: -webkit-flex;
				flex-direction: row;
				position: absolute;
				bottom: 0;
				width: 100%;
				top: 0px !important;
				left: 0px;

				/* these 3 lines are to prevents an unsightly scrolling bounce affect on Safari */
				height: 100%;
				width: 100%;
				overflow: auto;
			}
			#flex-container>div {
				flex-grow: 1;
				-webkit-flex-grow: 1;
				position: relative;
			}
			#flex-container>div#acediff-gutter {
				flex: 0 0 60px;
				-webkit-flex: 0 0 60px;
				border-left: 1px solid #999999;
				border-right: 1px solid #999999;
				/*background-color: #efefef;*/
				overflow: hidden;
			}
			#acediff-gutter svg {
				/*background-color: #efefef;*/
			}

			#acediff-left-editor {
				position: absolute;
				top: 0;
				bottom: 0;
				width: 100%;
			}
			#acediff-right-editor {
				position: absolute;
				top: 0;
				bottom: 0;
				width: 100%;
			}
			.acediff-diff {
				background-color: rgba(216, 242, 255, 0.32);
				border-top: 1px solid rgba(162, 215, 242, 0.54);
				border-bottom: 1px solid rgba(162, 215, 242, 0.51);
				position: absolute;
				z-index: 4;
			}

			.acediff-diff.targetOnly {
				height: 0px !important;
				border-top: 1px solid #a2d7f2;
				border-bottom: 0px;
				position: absolute;
			}

			.acediff-connector {
				fill: rgba(216, 242, 255, 0.50);
				stroke: #a2d7f2;
			}

			.acediff-copy-left {
				float: right;
			}
			.acediff-copy-right,
			.acediff-copy-left {
				position: relative;
			}
			.acediff-copy-right div {
				margin-top: -2px;
				/*color: #000000;*/
				/*text-shadow: 1px 1px #ffffff;*/
				position: absolute;
				/*margin: 2px 3px;*/
				margin-left: 3px;
				margin-right: 3px;
				cursor: pointer;
			}
			.acediff-copy-right div:hover {
				color: #004ea0;
			}
			.acediff-copy-left div {
				/*color: #000000;*/
				/*text-shadow: 1px 1px #ffffff;*/
				margin-top: -2px;
				position: absolute;
				right: 0px;
				margin-left: 3px;
				margin-right: 3px;
				cursor: pointer;
			}

			.acediff-copy-left div:hover {
				color: #c98100;
			}
			.ui-widget-content {
				background-color: #ffffff;
				border: 0px solid #eeeeee;
				color: #333333;
			}
			#adminmenuwrap{
				z-index: 200;
			}

			#wpadminbar{
				z-index: 200;
			}

		</style>

	<?php
	}


	function admin_footer_release() {
		?>

		<script type="text/javascript" charset="utf-8">

			var dojoConfig = {
				async: true,
				parseOnLoad: false,
				isDebug: 0,
				baseUrl: "<?php echo plugins_url( 'client/src/xfile' , __FILE__ ); ?>",
				tlmSiblingOfDojo: 0,
				useCustomLogger:false,
				packages: [{"name":"dojo","location":"dojo"},{"name":"dojox","location":"dojox"},{"name":"dijit","location":"dijit"},{"name":"cbtree","location":"cbtree"},{"name":"xfile","location":"xfile"},{"name":"xide","location":"xide"},{"name":"dgrid","location":"dgrid"},{"name":"dstore","location":"dstore"},{"name":"xwordpress","location":"xwordpress"},{"name":"xedit","location":"xedit"}],
				has:{'xlog':true,'xide-beans':true},
				locale:'en'
			};

			var resourceVariables = {
				ACE:"<?php echo plugins_url( 'client/src/xfile/ext/ace/' , __FILE__ ); ?>",
				APP_URL:"<?php echo plugins_url( 'client/src/' , __FILE__ ); ?>"
			}

		</script>

		<link rel='stylesheet' href='<?php echo plugins_url( 'client/src/xfile/xedit/resources/app-xedit.css' , __FILE__ );?>' type='text/css' media='all' />
		<link rel='stylesheet' href='<?php echo plugins_url( 'client/src/css/elusive-icons/elusive-webfont.css' , __FILE__ );?>' type='text/css' media='all' />


		<script type="text/javascript" src="<?php echo plugins_url( 'client/src/xfile/dojo/xedit.js' , __FILE__ ); ?>"></script>

		<script src="<?php echo plugins_url( 'client/src/xfile/xedit/run.js' , __FILE__ ); ?>"></script>

		<script src="<?php echo plugins_url( 'client/src/xfile/ext/underscore.min.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/keypress.js' , __FILE__ ); ?>"></script>



		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ace.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ext-language_tools.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ext-modelist.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ext-spellcheck.js' , __FILE__ ); ?>"></script>

		<style type="text/css">

			#template div {
				/* Need to reset margin here from core styles since it destroys
				   every single div contained in the editor... */
				margin-right: 0px;
			}
			#template #editor, #template > div {
				/* ... then redefine it in a much more scoped manner. */
				margin-right: 210px;
			}
			#template div #newcontent {
				width: 100%;
			}

			.dijitMenuItemHover td, .dijitMenuItemHover {
				background: none repeat scroll 0 0 rgba(45, 15, 18, 0.53);
				color: #fff;
			}
			.options-menu a {
				color: #c7c7c7;
			}
			.options-menu{
				background-color: #000000;
			}
			#wp-ace-editor {
				position: relative;
				height: 500px;
				font-size: 12px;
				border: 1px solid #BBB;
				border-radius: 3px;
			}
			.ace_editor {
				font-family: Consolas, Menlo, "Liberation Mono", Courier, monospace !important;
				height: 100%;
				width: 100%;
			}
			#wp-ace-editor-controls table td {
				vertical-align: center;
				padding: 5px;
			}
			.actionToolbarButtonElusive {
				font-family: Fontawesome;
			}
			#documentation {
				margin-top: 100px;
			}
			.dijitIcon{
				font-family: Fontawesome;
			}

			.acediff-gutter {
				/*background-color: #efefef;*/
				border-left: 1px solid #999999;
				border-right: 1px solid #999999;
				flex: 0 0 60px;
				overflow: hidden;
				width: 60px;
				height: inherit;
				position: absolute;
				-moz-box-sizing: border-box;
			}
			.acediff-gutter svg {
				/*background-color: #efefef;*/

			}
			#flex-container {
				display: flex;
				display: -webkit-flex;
				flex-direction: row;
				position: absolute;
				bottom: 0;
				width: 100%;
				top: 0px !important;
				left: 0px;

				/* these 3 lines are to prevents an unsightly scrolling bounce affect on Safari */
				height: 100%;
				width: 100%;
				overflow: auto;
			}
			#flex-container>div {
				flex-grow: 1;
				-webkit-flex-grow: 1;
				position: relative;
			}
			#flex-container>div#acediff-gutter {
				flex: 0 0 60px;
				-webkit-flex: 0 0 60px;
				border-left: 1px solid #999999;
				border-right: 1px solid #999999;
				/*background-color: #efefef;*/
				overflow: hidden;
			}
			#acediff-gutter svg {
				/*background-color: #efefef;*/
			}

			#acediff-left-editor {
				position: absolute;
				top: 0;
				bottom: 0;
				width: 100%;
			}
			#acediff-right-editor {
				position: absolute;
				top: 0;
				bottom: 0;
				width: 100%;
			}
			.acediff-diff {
				background-color: rgba(216, 242, 255, 0.32);
				border-top: 1px solid rgba(162, 215, 242, 0.54);
				border-bottom: 1px solid rgba(162, 215, 242, 0.51);
				position: absolute;
				z-index: 4;
			}

			.acediff-diff.targetOnly {
				height: 0px !important;
				border-top: 1px solid #a2d7f2;
				border-bottom: 0px;
				position: absolute;
			}

			.acediff-connector {
				fill: rgba(216, 242, 255, 0.50);
				stroke: #a2d7f2;
			}

			.acediff-copy-left {
				float: right;
			}
			.acediff-copy-right,
			.acediff-copy-left {
				position: relative;
			}
			.acediff-copy-right div {
				margin-top: -2px;
				/*color: #000000;*/
				/*text-shadow: 1px 1px #ffffff;*/
				position: absolute;
				/*margin: 2px 3px;*/
				margin-left: 3px;
				margin-right: 3px;
				cursor: pointer;
			}
			.acediff-copy-right div:hover {
				color: #004ea0;
			}
			.acediff-copy-left div {
				/*color: #000000;*/
				/*text-shadow: 1px 1px #ffffff;*/
				margin-top: -2px;
				position: absolute;
				right: 0px;
				margin-left: 3px;
				margin-right: 3px;
				cursor: pointer;
			}

			.acediff-copy-left div:hover {
				color: #c98100;
			}
			.ui-widget-content {
				background-color: #ffffff;
				border: 0px solid #eeeeee;
				color: #333333;
			}
			#adminmenuwrap{
				z-index: 200;
			}

			#wpadminbar{
				z-index: 200;
			}

		</style>

	<?php
	}

	function admin_footer() {
		?>




		<script type="text/javascript" charset="utf-8">

			var dojoConfig = {
				async: true,
				parseOnLoad: false,
				isDebug: 0,
				baseUrl: "<?php echo plugins_url( 'client/src/lib' , __FILE__ ); ?>",
				tlmSiblingOfDojo: 0,
				useCustomLogger:false,
				packages: [{"name":"dojo","location":"dojo"},{"name":"dojox","location":"dojox"},{"name":"dijit","location":"dijit"},{"name":"cbtree","location":"cbtree"},{"name":"xfile","location":"xfile"},{"name":"xide","location":"xide"},{"name":"dgrid","location":"dgrid"},{"name":"dstore","location":"dstore"},{"name":"xwordpress","location":"xwordpress"},{"name":"xedit","location":"xedit"}],
				has:{'xlog':true,'xide-beans':true},
				locale:'en'
			};

			var resourceVariables = {
				ACE:"<?php echo plugins_url( 'client/src/xfile/ext/ace/' , __FILE__ ); ?>",
				APP_URL:"<?php echo plugins_url( 'client/src/' , __FILE__ ); ?>"
			}

		</script>

		<link rel='stylesheet' href='<?php echo plugins_url( 'client/src/lib/dijit/themes/claro/dflat/CSS/dojo/flat.css' , __FILE__ );?>' type='text/css' media='all' />
		<link rel='stylesheet' href='<?php echo plugins_url( 'client/src/css/elusive-icons/elusive-webfont.css' , __FILE__ );?>' type='text/css' media='all' />


		<script type="text/javascript" src="<?php echo plugins_url( 'client/src/lib/dojo/dojo.js' , __FILE__ ); ?>" data-dojo-config="baseUrl: '<?php echo plugins_url( 'client/src/lib' , __FILE__ ); ?>',has:{'ace-formatters':true,'dojo-firebug': false,'xlog':true,'dojo-built':true}, parseOnLoad: false, async: 1,packages:[]"></script>

		<!--script src="<?php echo plugins_url( 'client/src/lib/dojo/dojo.js' , __FILE__ ); ?>"></script-->

		<script src="<?php echo plugins_url( 'client/src/lib/xedit/run.js' , __FILE__ ); ?>"></script>

		<script src="<?php echo plugins_url( 'client/src/xfile/ext/underscore.min.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/keypress.js' , __FILE__ ); ?>"></script>



		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ace.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ext-language_tools.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'client/src/xfile/ext/ace/ext-modelist.js' , __FILE__ ); ?>"></script>




		<!--script src="<?php echo plugins_url( 'js/require.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'js/ace/ace.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'js/ace/ext-modelist.js' , __FILE__ ); ?>"></script>
		<script type="text/javascript" charset="utf-8">
			jQuery(document).ready(function() {
				/**
				 * Detecting the HTML5 Canvas API (usually) gives us IE9+ and
				 * of course all modern browsers. This should be adequate for
				 * minimum requirements instead of browser sniffing.
				 */
				if(!!document.createElement('canvas').getContext)
				{
					var wpacejs = document.createElement('script');
					wpacejs.type = 'text/javascript'; wpacejs.charset = 'utf-8';
					wpacejs.src = '<?php echo plugins_url( "js/wp-ace.js" , __FILE__ ); ?>';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wpacejs, s);
				}
			});
		</script-->
		<style type="text/css">
			#template div {
				/* Need to reset margin here from core styles since it destroys
				   every single div contained in the editor... */
				margin-right: 0px;
			}
			#template #editor, #template > div {
				/* ... then redefine it in a much more scoped manner. */
				margin-right: 210px;
			}
			#template div #newcontent {
				width: 100%;
			}

			.dijitMenuItemHover td, .dijitMenuItemHover {
				background: none repeat scroll 0 0 rgba(45, 15, 18, 0.53);
				color: #fff;
			}
			.options-menu a {
				color: #c7c7c7;
			}
			.options-menu{
				background-color: #000000;
			}
			#wp-ace-editor {
				position: relative;
				height: 660px;
				font-size: 12px;
				border: 1px solid #BBB;
				border-radius: 3px;
			}
			.ace_editor {
				font-family: Consolas, Menlo, "Liberation Mono", Courier, monospace !important;
				height: 100%;
				width: 100%;
			}
			#wp-ace-editor-controls table td {
				vertical-align: center;
				padding: 5px;
			}
			.actionToolbarButtonElusive {
				font-family: Fontawesome;
			}
			#documentation {
				margin-top: 100px;
			}
			.dijitIcon{
				font-family: Fontawesome;
			}

			.acediff-gutter {
				/*background-color: #efefef;*/
				border-left: 1px solid #999999;
				border-right: 1px solid #999999;
				flex: 0 0 60px;
				overflow: hidden;
				width: 60px;
				height: inherit;
				position: absolute;
				-moz-box-sizing: border-box;
			}
			.acediff-gutter svg {
				/*background-color: #efefef;*/

			}
			#flex-container {
				display: flex;
				display: -webkit-flex;
				flex-direction: row;
				position: absolute;
				bottom: 0;
				width: 100%;
				top: 0px !important;
				left: 0px;

				/* these 3 lines are to prevents an unsightly scrolling bounce affect on Safari */
				height: 100%;
				width: 100%;
				overflow: auto;
			}
			#flex-container>div {
				flex-grow: 1;
				-webkit-flex-grow: 1;
				position: relative;
			}
			#flex-container>div#acediff-gutter {
				flex: 0 0 60px;
				-webkit-flex: 0 0 60px;
				border-left: 1px solid #999999;
				border-right: 1px solid #999999;
				/*background-color: #efefef;*/
				overflow: hidden;
			}
			#acediff-gutter svg {
				/*background-color: #efefef;*/
			}

			#acediff-left-editor {
				position: absolute;
				top: 0;
				bottom: 0;
				width: 100%;
			}
			#acediff-right-editor {
				position: absolute;
				top: 0;
				bottom: 0;
				width: 100%;
			}
			.acediff-diff {
				background-color: rgba(216, 242, 255, 0.32);
				border-top: 1px solid rgba(162, 215, 242, 0.54);
				border-bottom: 1px solid rgba(162, 215, 242, 0.51);
				position: absolute;
				z-index: 4;
			}

			.acediff-diff.targetOnly {
				height: 0px !important;
				border-top: 1px solid #a2d7f2;
				border-bottom: 0px;
				position: absolute;
			}

			.acediff-connector {
				fill: rgba(216, 242, 255, 0.50);
				stroke: #a2d7f2;
			}

			.acediff-copy-left {
				float: right;
			}
			.acediff-copy-right,
			.acediff-copy-left {
				position: relative;
			}
			.acediff-copy-right div {
				margin-top: -2px;
				/*color: #000000;*/
				/*text-shadow: 1px 1px #ffffff;*/
				position: absolute;
				/*margin: 2px 3px;*/
				margin-left: 3px;
				margin-right: 3px;
				cursor: pointer;
			}
			.acediff-copy-right div:hover {
				color: #004ea0;
			}
			.acediff-copy-left div {
				/*color: #000000;*/
				/*text-shadow: 1px 1px #ffffff;*/
				margin-top: -2px;
				position: absolute;
				right: 0px;
				margin-left: 3px;
				margin-right: 3px;
				cursor: pointer;
			}

			.acediff-copy-left div:hover {
				color: #c98100;
			}
			.ui-widget-content {
				background-color: #ffffff;
				border: 0px solid #eeeeee;
				color: #333333;
			}
			#adminmenuwrap{
				z-index: 200;
			}

			#wpadminbar{
				z-index: 200;
			}

		</style>

	<?php
	}

}

$bfe_plugin = new XEditWPPlugin2();
