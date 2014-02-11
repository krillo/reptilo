<?php 
/**
 * This is the Reptilo feedback module. It opens in a modal window and sends feedback to a specified email with category and text. 
 * It is not designed to be an standalone module since it has dependencys: 
 * 1. jQuery
 * 2. Bootstrap 3 (js and css)
 * 3. font-awesome.css
 * 4. The destination email adress, id='rep-feedback-to-email'
 * 5. The feedback callback function in reptilo_utils.php
 * 
 * Author: Kristian Erendi - Reptilo AB
 * Author URI: http://reptilo.se/
 * Author Email: kristian@reptilo.se
 * Date: 2014-01-16
 * Version: 1.0
 * License: GNU General Public License v2 or later
 */

$post = $wp_query->get_queried_object(); 
?>

<style>
  .modal-dialog {width: 380px;}
  i{margin-right: 10px;}
  #rep-feedback-title{color:#666;}
  .feedback-types-wrapper{height:50px;overflow: hidden;}
  .feedback-type{margin-right: 0;padding: 15px;float: left;}
  .feedback-type:hover{text-decoration: none;}
  .feedback-type:visited{color: #666;}
  #rep-feedback-msg{width:340px;height:300px;height: 140px;margin-bottom: 10px;} 
  #rep-feedback-email{width:340px;margin-bottom: 10px;}
  #rep-feedback-submit{margin-bottom: 10px;}
  #rep-feedback-error.alert, #rep-feedback-thankyou.alert {margin-bottom: 0;}

  .feedback-types-wrapper a.selected{
    background-color: #555555;
    border-radius: 3px;
    -moz-border-radius: 3px;
    -o-border-radius: 3px;
    -ms-border-radius: 3px;
    -webkit-border-radius: 3px;
    color: white;
    outline: none;
    padding: 10px;
  }
</style>

<script type="text/javascript" charset="utf-8">
  jQuery(document).ready(function($) {
    reset();

    function reset() {
      hide_error();
      hide_thx();
    }

    function show_error(msg) {
      $("#rep-feedback-error-txt").html(msg);
      $("#rep-feedback-error").show('slow');
    }

    function hide_error() {
      $("#rep-feedback-error").hide();
    }

    function show_thx() {
      hide_error();
      $("#rep-feedback-thankyou").show('slow');
    }

    function hide_thx() {
      $("#rep-feedback-thankyou").hide();
    }

    function isValidEmailAddress(emailAddress) {
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      return pattern.test(emailAddress);
    }

    //submit - ajax
    $("#rep-feedback-submit").click(function(event) {
      event.preventDefault();
      reset();
      email = $('#rep-feedback-email').val();
      if (!isValidEmailAddress(email)) {
        show_error("Ogiltig emailadress");
      } else {
        hide_error();
        $("#rep-feedback-submit").button('loading');
        var data = {
          action: 'rep_feedback',
          pagename: $('#rep-feedback-pagename').val(),
          guid: $('#rep-feedback-guid').val(),
          to_email: $('#rep-feedback-to-email').val(),
          email: email,
          msg: $('#rep-feedback-msg').val(),
          type: $('#rep-feedback-type').val()
        };
        $.post('/wp-admin/admin-ajax.php', data, function(response) {
          $("#rep-feedback-submit").button('reset');
          if (response.success) {
            show_thx();
          } else{
            show_error("Något gick fel, prova igen senare.");
          }
        });
      }
    });

    //handle type
    $(".feedback-type").click(function(event) {
      event.preventDefault();
      $(".feedback-type").removeClass("selected");
      $(this).addClass("selected");
      $("#rep-feedback-type").val($(this).attr("data-type"));
    });


  });
</script> 

<!--button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Skicka in dina synpunkter och kommentarer</button-->
<!--a href="" class="" data-toggle="modal" data-target="#myModal">Skicka in dina synpunkter och kommentarer</a-->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="rep-feedback-title">Skicka din feedback</h4>
      </div>
      <div class="modal-body">
        <input value="<?php echo $post->post_title; ?>" id='rep-feedback-pagename' name="pagename" type="hidden" />				
        <input value="<?php echo $post->guid; ?>" id='rep-feedback-guid' name="guid" type="hidden" />				
        <input value="<?php the_field('to_email'); ?>" id='rep-feedback-to-email' name="to_email" type="hidden" />
        <input value="Ide" id="rep-feedback-type" name="type" type="hidden" />
        <div class="feedback-types-wrapper">
          <a href="#" class="feedback-type selected" data-type="Ide"><i class="fa fa-lightbulb-o"></i>Idé</a>
          <a href="#" class="feedback-type" data-type="Fråga"><i class="fa fa-question-circle"></i>Fråga</a>
          <a href="#" class="feedback-type" data-type="Problem"><i class="fa fa-exclamation-circle"></i>Problem</a>
          <a href="#" class="feedback-type" data-type="Tack"><i class="fa fa-heart"></i>Tack</a>
        </div>
        <textarea id="rep-feedback-msg"  name="description" placeholder="Din feedback" class="form-control"></textarea>
        <input id="rep-feedback-email"  value="" name="email" type="text" placeholder="Din e-postadress" class="form-control">	
        <button type="button" data-loading-text="Skickar..." class="btn btn-primary" id="rep-feedback-submit">Skicka</button>
        <div class="alert rep-alert alert-danger" id="rep-feedback-error"><i class="fa fa-exclamation-triangle"></i><span id="rep-feedback-error-txt"></span></div>
        <div class="alert rep-alert alert-success"id="rep-feedback-thankyou"><i class="fa fa-check-circle"></i>Tack, din feedback är skickad.</div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

