<?php

	ob_start();

	function cutline($filename,$line_no=-1) {
		$strip_return=FALSE;
		$data=file($filename);
		$pipe=fopen($filename,'w');
		$size=count($data);
		if($line_no==-1){
			$skip=$size-1;
		}
		else {
			$skip=$line_no-1;
		}
		for ($line=0;$line<$size;$line++){
			if ($line!=$skip) {
				fputs($pipe,$data[$line]);
				}
			else {
				$strip_return=TRUE;
				}
			}
		return $strip_return;
		}

	function create_link($link, $text){
		return '<a target="_blank" href="' . $link . '">' . $text . '</a>';
		}

	function get_tag($lista_tags){
		$tags = explode(",", $lista_tags);
		$show_tags = '';
		for ($i = 0; $i <= count($tags); $i++){
			$show_tags .= ' ' . $tags[$i];
			}
		return $show_tags;
		}

	function create_delete_link($link_id){
		return 'index.php?del=' . ($link_id + 1);
		}

	function create_line($id, $link, $text, $description, $tags){
		return '<tr> 
			<td><input type="checkbox"></td> 
			<td>' . create_link($link, $text) . '</td> 
			<td>' . $description . '</td> 
			<td>' . get_tag($tags) . '</td> 
			<td>
				<a href="#"><img src="images/icn_edit.png" /></a>
				<a href="' . create_delete_link($id) . '"><img src="images/icn_trash.png" /></a>
			</td> 
		</tr>';
		}

	include "class/password_protect.php";

	if (isset($_GET['del'])){
		cutline('link.list', $_GET['del']);
		}

	if (file_exists('link.list')) {
		$linklist = fopen('link.list', 'r');
		$linklistdim = filesize('template.html');
		$complete_linklist = fread($linklist, $linklistdim);
		$links = explode("\n", $complete_linklist);
		$message = '';
		for ($i = 0; $i <= count($links); $i++) {
			$link = explode("|", $links[$i]);
			if ($link[0] != ''){
				#$message .= create_delete_link($i) . create_link($link[0], $link[1]) . ' [' . $link[2] . '] - ' . get_tag($link[3]) . '<br/>';
				$message .= create_line($i, $link[0], $link[1], $link[2], $link[3]);
				}
		}
	} else {
		$linklist = fopen('link.list', 'x');
		$message = 'no link yet';
	}
	// POPOLA IL TEMPLATE E LO MOSTRA
	$template = fopen('template.html', 'r');
	$dim = filesize('template.html');
	$complete_template = fread($template, $dim);
	$complete_template = str_replace('{{LINKS_LIST}}', $message, $complete_template);
	fclose($template);
	echo $complete_template;

?>