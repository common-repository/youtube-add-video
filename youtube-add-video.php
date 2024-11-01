<?php
/* 
Plugin Name:  Youtube Add Video
Plugin URI:   http://www.muzungu.pl/moje-pluginy-do-wordpressa
Description:  Add YT video using Custom Fields and show last video in sidebar
Version:      0.2
Author:       Konrad Karpieszuk
Author URI:   http://www.muzungu.pl/
* 
* 
GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function show_LastYT ($user='')  {
	
	// shows last YT video added to post
	global $wpdb;
	$prefix = $wpdb->prefix;
	$yt_tablename = $prefix."yt_videos";
	
	if ($user == '') {
	
	$zapytanie = "SELECT video_id FROM  ".$yt_tablename." ORDER BY id DESC LIMIT 0, 1;";
	
	$wynik = $wpdb->get_var($zapytanie);
	
	if ($wynik != "") {
		
	$wynik = explode(":", $wynik);
	
	$wynik = $wynik[1];
	
	}
	}
	
	else {
		$zapytanie = "SELECT video_id FROM  ".$yt_tablename." ORDER BY id DESC;";
		$wyniki = $wpdb->get_results($zapytanie, ARRAY_A);
		
		foreach ($wyniki as $wynik) {
			$wynik = explode(":", $wynik['video_id']);
			$czyTenUser = $wynik[0];
			$wynik = $wynik[1];
			if ($czyTenUser == $user) {
				break;
				}
			}
		
		}
	
	
	
	echo '
	<object width="378" height="305">
	 <param name="movie" value="http://www.youtube.com/v/'.$wynik.'&hl=pl&fs=1&">
	 </param>
	 <param name="allowFullScreen" value="true">
	 </param>
	 <param name="allowscriptaccess" value="always">
	 </param>
	 <embed src="http://www.youtube.com/v/'.$wynik.'&hl=pl&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="378" height="305">
	 </embed>
	</object>
	';
	
		}


function yt_install () {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$yt_tablename = $prefix."yt_videos";
	
	$yt_db_version = "1.0";
	
	if ($wpdb->get_var("SHOW TABLES LIKE '".$yt_tablename."'") != $yt_tablename) {
		$zapytanie = "CREATE TABLE ".$yt_tablename." (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		video_id varchar(120) NOT NULL,
		PRIMARY KEY  (id)
		);";
		
		$wpdb->query($zapytanie);
		
		add_option("yt_db_version", $yt_db_version);
		
		}
	}
	
function yt_database ($post_id) {
	global $post;
	$czy_maVideo = get_post_meta($post_id, "video", true);
	
	if ($czy_maVideo != "") {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$yt_tablename = $prefix."yt_videos";
		
		/* $url = explode("?", $czy_maVideo);
		$url = $url[1];
		$url = explode("&", $url);
		$url = $url[0];
		$url = explode("=", $url);
		$video_id = $url[1]; */
		
		$url = parse_url($czy_maVideo, PHP_URL_QUERY);
		$url = explode("&", $url);
		foreach ($url as $parka) {
			if (substr($parka, 0, 2) == "v=") {
				$konkretnaParka = explode("=", $parka);
				$video_id = $konkretnaParka[1];
				break;
				}
			}
		
		
		$poscik = get_post($post_id);
		
		$ktododal = $poscik->post_author;
		
		$wstawka = $ktododal.":".$video_id;
		
		$zapisz_video = "INSERT INTO ".$yt_tablename." (video_id) VALUES ('".$wstawka."');";
		
		$rezultat = $wpdb->query($zapisz_video);
		
		wp_add_post_tags($post_id, 'video');
		
		}
	
	}
	
function add_ytVideo ($content) {
	global $post;
	$czy_maVideo = get_post_meta($post->ID, "video", true);
	
	if ($czy_maVideo != "") {
		
		$url = explode("?", $czy_maVideo);
		$url = $url[1];
		$url = explode("&", $url);
		$url = $url[0];
		$url = explode("=", $url);
		$video_id = $url[1];

	
			return $content.'
			<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/'.$video_id.'&hl=pl&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$video_id.'&hl=pl&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
			
			';
		
		}
	
	
	else return $content;
	
	}

register_activation_hook(__FILE__, 'yt_install');
add_filter('the_content', 'add_ytVideo');
add_action('publish_post', 'yt_database');

?>
