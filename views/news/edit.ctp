<div class="news form">
<?php echo $this->Form->create('News');?>
	<legend><?php __('Modificar Noticia'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title', array('class' => 'form-control'));
		echo $this->Form->input('content', array('label' => 'Contenido', 'class' => 'form-control'));
		echo $this->Form->input('published');
	?>
	<br />
	<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary', 'div' => false)); ?>
	<?php echo $this->Html->link(__('Volver', true), array('action' => 'index'), array('class' => 'btn btn-primary')); ?>
<?php echo $this->Form->end();?>
</div>