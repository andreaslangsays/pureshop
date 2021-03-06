<?php
/*
  $Id: forum.php, v 1 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Forum');
define('NAVBAR_TITLE', 'Forum');
define('FORUM_TITLE', 'Forums');

define('TABLE_FORUM_POST', 'forum_post');
define('TABLE_FORUM_TOPIC','forum_topic');
define('TABLE_FORUM_POST_TEXT','forum_postext');

define('FORUM_POSTED','Posted');
define('FORUM_TOPIC','Thread');
define('FORUM_VIEWS','Views');
define('FORUM_REPLIES','Replies');
define('FORUM_LAST_REPLY','Last Reply');

define('FORUM_TOTAL_TOPICS','Threads: ');
define('FORUM_TOTAL_POSTS','Posts: ');
define('FORUM_NO_POSTS','No posts found.');
define('FORUM_THREAD_VIEWS','Viewed: ');
define('FORUM_TOPIC_REPLIES','Replies: ');
define('TEXT_DISPLAY_NUMBER_OF_POSTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> posts)');
define('TEXT_DISPLAY_NUMBER_OF_TOPICS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> threads)');
define('FORUM_NEW_THREAD_HEADING','Post a new thread...');
define('FORUM_NEW_REPLY_HEADING','Post a reply...');
define('FORUM_NEW_CONTACT_HEADING','Type your message here...');
define('FORUM_NEW_THREAD_NAME','* Your Name');
define('FORUM_NEW_THREAD_EMAIL','Your E-Mail Address');
define('FORUM_EMAIL_DISCLAIMER',' <i>[This will not be displayed]</i>');
define('FORUM_NEW_THREAD_HOMEPAGE','Your Home Page');
define('FORUM_NEW_THREAD_SUBJECT','* Subject');
define('FORUM_NEW_THREAD_TEXT','* Message');
define('FORUM_NEW_THREAD_REQUIRED','(*) These fields are required');

define('FORUM_BUTTON_POSTMESSAGE','Post this message now...');
define('FORUM_LINK','Back to the Forums...');
define('FORUM_NEW_TOPIC','New Thread');
define('FORUM_BUTTON_REPLY','Reply to this thread...');

define('FORUM_DISCLAIMER', 'Views and opinions expressed on these forums are not necessarily those of ' . STORE_NAME . '.');

define('TEXT_SUCCESS', 'Your email has been sent successfully!');
define('EMAIL_SUBJECT_1', 'RE: "');
define('EMAIL_SUBJECT_2', '" on the ' . STORE_NAME . ' forum');
define('EMAIL_TO', $_GET["cont"] . " <i>[Address hidden]</i>" );

define('CONTACT_NAME', 'Message to:');

define('ENTRY_SUBJECT', 'Subject:');
define('ENTRY_TO', 'To:');
define('ENTRY_NAME', 'Your Name:');
define('ENTRY_EMAIL', 'Your E-Mail Address:');
define('ENTRY_ENQUIRY', 'Message:');
define('ENTRY_EMAIL_CONTENT_CHECK_ERROR', 'Sorry, your message must be greater than 50 characters!');

define('CONTACT_DISCLAIMER', 'All emails and IP addresses are logged to prevent abuse. Only registered customers are allowed to send emails.');
?>
