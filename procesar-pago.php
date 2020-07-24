<?php

  $back_url = $_GET['back_url'];
  $back_url = str_replace('%3A', ':', $back_url);
  $back_url = str_replace('%2F', '/', $back_url);
  $back_url = str_replace('%3F', '?', $back_url);
  $back_url = str_replace('%3D', '=', $back_url);
  $back_url = str_replace('%26', '&', $back_url);

?>

<script type="text/javascript">
  window.location.href = "<?= $back_url ?>";
</script>
