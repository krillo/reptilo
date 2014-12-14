/** 
 * This js script will be run on every pageview
 * @author Kristian Erendi
 */
jQuery(document).ready(function($) {
  init();


  /**
   * Inits stuff
   * 
   * 1. The tooltip fom Bootstrap must be initialized, use like this:
   * <a href="#" data-toggle="tooltip" class="rep-tooltip" title="This is Kaptens tooltip" data-placement="top">Hover over me</a>
   * 
   * 2. Get placeholder to work on IE, jquery.placeholder.js,  http://mths.be/placeholder v2.0.7 by @mathias
   * 
   * 
   * @returns {undefined}
   */
  function init() {
    $(".rep-tooltip").tooltip();
    $('input, textarea').placeholder();
  }



  
  /**
   * Catch event - expand list in the rep-page-hierarchy navigation
   * Part of rep-page-hierarchy functionality
   */
  $(".rep-caret").click(function() {
    var postId = $(this).attr("data");
    var ulId = '#rep-children-to-' + postId;
    $(ulId).toggle('slow');
  });


});
