<?php
$type = get_field('notice_type');
$text = get_field('notice');
$show_link = get_field('link');
if ($text != '') {
  $link = '';
  if (get_field('notice_link')) {
    $post_obj = get_field('notice_link');
    if ($show_link) {
      $link = '<a class="alert-link" target="_blank" href="' . $post_obj->guid . '" title="' . $post_obj->post_title . '">' . $post_obj->post_title . '</a>';
    }
  }
}
?>
<?php if ($text != ''): ?>
  <?php if ($type == 'info'): ?>
    <div class="alert rep-alert alert-warning"><i class="fa fa-info-circle"></i> <?php echo $text . ' ' . $link; ?></div>
  <?php elseif ($type == 'warning'): ?>
    <div class="alert rep-alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?php echo $text . ' ' . $link; ?></div>
  <?php endif; ?>
<?php endif; ?>