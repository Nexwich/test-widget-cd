<?php
/**
 * @var array $data
 */

$json_settings = file_get_contents(__DIR__ . '/../data/settings.json');
$settings = json_decode($json_settings, true);

?>
<div>
  <div class="js-rate">
    <?= date('d.m.Y H:i:s') ?>

    <?php foreach ($data as $row) { ?>
      <div>
        <?= $row['code'] ?> <?= $row['rate'] ?>

        <?php if ($row['difference'] > 0) { ?>
          <span style="color: green;">↑</span>
        <?php }elseif ($row['difference'] == 0) { ?>
          <span>=</span>
        <?php }else { ?>
          <span style="color: red;">↓</span>
        <?php } ?>

        <?= $row['difference'] ?>
      </div>
    <?php } ?>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>

<script>
  const $rate = $('.js-rate');

  setInterval(function () {
    $.ajax({
      url: '',
      success (response) {
        console.log();
        $rate.html($(response).find('.js-rate').html());
      },
    });
  }, <?= $settings['updateTime'] ?>);
</script>
