<?php  if($this->session->get('error')): ?>
	<script>
		$.growl.error({ message: "<?php echo $this->session->get('error') ?>" });
	</script>
<?php $this->session->delete('error'); endif; ?> 

<?php  if($this->session->get('success')): ?>
	<script>
		$.growl.notice({ message: "<?php echo $this->session->get('success') ?>" });
	</script>
<?php $this->session->delete('success'); endif; ?>


<?php  if($this->session->get('warning')): ?>
	<script>
		$.growl.warning({ message: "<?php echo $this->session->get('warning') ?>" });
	</script>
<?php $this->session->delete('warning'); endif; ?>