<?php require('top.php'); ?>
<?php require('menu.php'); ?>
<div id="wrap-content" class="container-fluid">
	<?php echo isset($css)?$css:''; ?>
	<?php echo $content; ?>
	<?php echo isset($Jquery)?$Jquery:''; ?>
</div>
<?php require('footter.php'); ?>    
<?php require('error.php'); ?> 




