<?php

class EmailObject {
  
  function __construct($mysql,$uniqid,$source) {

    $this->mysql  = $mysql;
    $this->uniqid = $uniqid;
    $this->source = $source;
  }
  
  function readEmail(){
    
    // Decode email message into parts
    $decoder = new Mail_mimeDecode($this->source);

    $this->decoded = $decoder->decode(
      Array(
        "decode_headers" => TRUE,
        "include_bodies" => TRUE,
        "decode_bodies"  => TRUE,
      )
    );
    
    // Get from name and email
    $this->from  = $this->decoded->headers["from"];
    
    if (preg_match("/.* <.*@.*\..*>/i",$this->from,$matches)) {
      $this->name  = preg_replace("/ <(.*)>$/", "", $this->from);
      $this->email = preg_replace("/.*<(.*)>.*/","$1",$this->from);
    } else {
      $this->email = $this->from;
    }
      
    // Get subject
    $this->subject = trim($this->decoded->headers["subject"]);

    // Get body & attachments (if available)
    if (is_array($this->decoded->parts)) {
      foreach($this->decoded->parts as $arItem => $body_part){
        $this->decodePart($body_part);
      }
    } else {
      $this->bodyText = $this->decoded->body;
    }

	// Save Message to MySQL
    $this->saveToDb();
  }

  // Decode body part
  private function decodePart($body_part){
    
    // Ignore attachments
    if (isset($body_part->d_parameters["filename"])) {
      return;
    }
    
    $mimeType = "{$body_part->ctype_primary}/{$body_part->ctype_secondary}";

    // Decode sub-parts
    if ($body_part->ctype_primary == "multipart") {
      if (is_array($body_part->parts)) {
        foreach($body_part->parts as $arItem => $sub_part) {
          $this->decodePart($sub_part);
        }
      }
    }
    
    // Get plain text version
    if ($mimeType == "text/plain") {
      if (!isset($body_part->disposition)) {
        $this->bodyText .= $body_part->body;
      }
    }
    
    // Get HTML version
    if ($mimeType == "text/html") {
      if (!isset($body_part->disposition)) {
        $this->bodyHtml .= $body_part->body;
      }
    }
    echo "<P>".$body_part->ctype_primary;
    if ($body_part->ctype_primary == "body")
      echo $body_part->body;
  }
  
  // Save message to MySQL
  private function saveToDb() {
    
    $mysql  = $this->mysql;
    $uniqid = $this->uniqid;
    
    if (isset($this->bodyText)) {
      $body_text = $this->bodyText;
      $body_text = mysql_real_escape_string(mb_convert_encoding(trim($body_text),'UTF-8','UTF-8'), $mysql);
    } else {
      $body_text = "";
    }
    
    if (isset($this->bodyHtml)) {

      $body_html = $this->bodyHtml;
    
      // Strip header tag (some email clients)
      $body_html = preg_replace("/<!DOCTYPE(.*?)>(\\r\\n)?/i","",$body_html);
    
      // Strip HTML tags (Yahoo, Mozilla)
      $body_html = preg_replace("/<\/?html(.*?)>(\\r\\n)?/i","",$body_html);
      $body_html = preg_replace("/<\/?head(.*?)>(\\r\\n)?/i","",$body_html);
      $body_html = preg_replace("/<\/?body(.*?)>(\\r\\n)?/i","",$body_html);
      $body_html = preg_replace("/<meta(.*?)>(\\r\\n)?/i","",$body_html);
      $body_html = preg_replace("/<style(.*?)<\/style>(\\r\\n)?/i","",$body_html);

      // Replace superfluous inline image meta
      $body_html = preg_replace("/ id=\"(.*?)\"/i","",$body_html);
      $body_html = preg_replace("/ alt=\"(.*?)\"/i","",$body_html);
      $body_html = preg_replace("/ title=\"(.*?)\"/i","",$body_html);
      $body_html = preg_replace("/ class=\"(.*?)\"/i","",$body_html);
      $body_html = preg_replace("/ data-id=\"(.*?)\"/i","",$body_html);
      $body_html = preg_replace("/ apple-inline=\"yes\"/i","",$body_html);
      
      $body_html = mysql_real_escape_string(mb_convert_encoding(trim($body_html),'UTF-8','UTF-8'), $mysql);
    } else {
      $body_html = "";
    }
        
    // Prepare data for MySql
    if (isset($this->name))
      $name = mysql_real_escape_string(mb_convert_encoding($this->name,'UTF-8','UTF-8'), $mysql);
    else
      $name = "";
    if (isset($this->email))
      $email = mysql_real_escape_string(mb_convert_encoding($this->email,'UTF-8','UTF-8'), $mysql);
    else
      $email = "";
    if (isset($this->subject))
      $subject = mysql_real_escape_string(mb_convert_encoding($this->subject,'UTF-8','UTF-8'), $mysql);
    else
      $subject = "";
    
    // check if candidate
	$query = "SELECT candidate_id, site_id, email1 FROM candidate WHERE email1='".$email."' ORDER BY candidate_id DESC LIMIT 0,1";
	$result = mysql_query( $query );
	$howmany = mysql_num_rows($result);
	if ($howmany > 0) { // it means it is candidate, so insert to database
	    $row = mysql_fetch_array( $result );
	    $mailtext = 'Subject: '.$subject;
        $mailtext.= "\n\n";
	    $mailtext.= "Message:\n";
        $mailtext.= $body_text;
        $user_id = $row['candidate_id'];
        $site = $row['site_id'];
        $date_now = date("Y-m-d H:i:s");
        mysql_query("INSERT INTO `email_history` (`email_history_id`, `uniqid`, `name`, `from_address`, `recipients`, `text`, `body_html`, `user_id`, `site_id`, `date`, `for_module`, `for_id`) VALUES (NULL, '".$uniqid."', '".$name."', '".$email."', '".$email."', '".$mailtext."','".$body_html."', '".$user_id."', '".$site_id."', '".$date_now."', 'candidates', '".$user_id."')");
    }

    // check if contact
	$query = "SELECT contact_id, site_id, email1 FROM contact WHERE email1='".$email."' ORDER BY contact_id DESC LIMIT 0,1";
	$result = mysql_query( $query );
	$howmany = mysql_num_rows($result);
	if ($howmany > 0) { // it means it is contact, so insert to database
	    $row = mysql_fetch_array( $result );
	    $mailtext = 'Subject: '.$subject;
        $mailtext.= "\n\n";
	    $mailtext.= "Message:\n";
        $mailtext.= $body_text;
        $user_id = $row['contact_id'];
        $site = $row['site_id'];
        $site_id = 180;
        $date_now = date("Y-m-d H:i:s");
        mysql_query("INSERT INTO `email_history` (`email_history_id`, `uniqid`, `name`, `from_address`, `recipients`, `text`, `body_html`, `user_id`, `site_id`, `date`, `for_module`, `for_id`) VALUES (NULL, '".$uniqid."', '".$name."', '".$email."', '".$email."', '".$mailtext."','".$body_html."', '".$user_id."', '".$site_id."', '".$date_now."', 'contacts', '".$user_id."')");
    }
    
    // Attachments are ignored
  }
}
