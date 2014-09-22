<?php
//debug($this->Session->read('Search'));
/*echo $this->Html->script('zoomooz/jquery.zoomooz-helpers');
echo $this->Html->script('zoomooz/jquery.zoomooz-anim');
echo $this->Html->script('zoomooz/jquery.zoomooz-core');
echo $this->Html->script('zoomooz/jquery.zoomooz-zoomTarget');
echo $this->Html->script('zoomooz/jquery.zoomooz-zoomContainer');
echo $this->Html->script('zoomooz/purecssmatrix');
echo $this->Html->script('zoomooz/sylvester.src.stripped');*/
//echo $this->Html->css('website-assets/website');

//debug($this->data);
//debug($this->passedArgs);
//debug($this->Session->read());

function marc21_decode($camp = null) {
	if (!empty($camp)) {
		$c = explode('^', $camp);
		$indicators = $c[0];
		unset($c[0]);

		$i = 0;
		foreach ($c as $v){
			$c[substr($v, 0, 1)] = substr($v, 1, strlen($v)-1);
			$i++;
			unset($c[$i]);
		}
		$c['indicators'] = $indicators;
		return $c;
	} else {
		return false;
	}
}

if (!empty($this->data)) { // Si viene de una búsqueda.
	$busqueda = 1;
} else {
	$busqueda = 0;
}
?>
<style>
	.btn-primary {
		width: 35px;
		height: 35px;
		margin: 2px 2px 0px 0px;
		padding: 8px 0px 0px 0px;
		text-align: center;
		float: left;
	}
	
	.btn-primary:hover {
		text-decoration: none;
	}
</style>
<ul class="breadcrumb" style="margin: 0">
  <li><a href="<?php echo $this->base; ?>/books">Libros</a></li>
  <?php if (!isset($this->passedArgs[0])) { ?>
  	<li>Autor</li>
  <?php } else { ?>
  	<li><a href="<?php echo $this->base; ?>/books/author">Autor</a></li>
  	<li class="active"><?php echo $this->passedArgs[0]; ?></li>
  <?php } ?>
</ul>

<div class='author view'>
	<div class="col-md-9 column">
	<h2>Módulo de Libros</h2>
	<?php if (count($items) > 0) { ?>
	<table class="table">
	<tr>
		<th><?php __('Cover');?></th>
		<th><?php __('Detalles de la Obra');?></th>
	</tr>
	<?php foreach ($items as $item): ?>
	<?php
		//$color = "#b3bbce";
		$color = "";
	?>
	<tr>
		<td style="background-color: <?php echo $color; ?>; text-align: center; width: 80px;">
		<?php
			if (($item['Item']['cover_name']) && (file_exists($_SERVER['DOCUMENT_ROOT'] . "/".$this->base."/webroot/covers/" . $item['Item']['cover_path']))){
				echo $this->Html->image("/webroot/covers/" . $item['Item']['cover_path'], array('width' => '70px'));
			} else {
				echo $this->Html->image("/webroot/img/sin_portada.jpg", array('width' => '70px'));
			}

			//if (!empty($item['ItemsPicture'])){
				//echo $this->Html->image("/webroot/attachments/files/big/" . $item['ItemsPicture'][0]['picture_file_path'], array('width' => '70px', 'class' => 'img-polaroid zoomTarget', 'data-closeclick' => 'true'));
			//}
		?>
		</td>
		<td>
			<dl class="dl-horizontal">
				<dt style="width: 120px"><?php __('Title:');?></dt>
				<dd style="margin-left: 130px">
					<?php
						if (!empty($item['Item']['245'])) {
							$title = marc21_decode($item['Item']['245']);
							if ($title) {
								
								foreach ($item['UserItems'] as $ui):
									if($ui['user_id'] == $this->Session->read('Auth.User.id')) {
										echo $html->image('/img/ts/bookmark.png', array('alt' => 'Mi Biblioteca', 'title' => 'Obra agregada a la biblioteca.', 'style' => 'width: 20px;'));
										echo "&nbsp;";
									}
								endforeach;
								
								echo $this->Html->link($title['a'] . '.', 'view/'.$item['Item']['id'], array('title' => 'Haga click para ver los detalles.'));
								if (isset($title['b'])) {echo ' <i>' . $title['b']. '.</i>';}
								if (isset($title['c'])) {echo ' ' . $title['c']. '.';}
								if (isset($title['h'])) {echo ' ' . $title['h']. '.';}
							}
						}
					?>
				</dd>
				<dt style="width: 120px"><?php __('Autor:');?></dt>
				<dd style="margin-left: 130px">
					<?php
						if (!empty($item['Item']['100'])) {
							$author = marc21_decode($item['Item']['100']);
							//echo $author['a'] . '.';
							echo "<b>" . substr($author['a'], 0, 1) . "</b>";
							echo substr($author['a'], 1, strlen($author['a']));
							if (isset($author['d'])) {echo ' ' . $author['d']. '.';}
						}
					?>
				</dd>
				<dt style="width: 120px"><?php __('Publicación:');?></dt>
				<dd style="margin-left: 130px">
					<?php
						if (!empty($item['Item']['260'])) {
							$publication = marc21_decode($item['Item']['260']);
							echo $publication['a'] . '.';
							if (isset($publication['b'])) {echo ' ' . $publication['b']. '.';}
							if (isset($publication['c'])) {echo ' ' . $publication['c']. '.';}
						}
					
					?>
				</dd>
				<!--
				<dt style="width: 120px"><?php __('Type:');?></dt>
				<dd style="margin-left: 130px">
					<?php
						$t1 = $item['Item']['h-006'];
						$t2 = $item['Item']['h-007'];
						
						// Tipo libro.
						if (($t1 == 'a') && ($t2 == 'm')) {
							echo "Libro";
						}
						
						// Tipo revista.
						if (($t1 == 'a') && ($t2 == 's')) {
							echo "Revista";
						}
	
						// Música impresa.
						if (($t1 == 'c') && ($t2 == 'm')) {
							echo "Música Impresa";
						}
						
						// Música manuscrita.
						if (($t1 == 'd') && ($t2 == 'm')) {
							echo "Música Manuscrita";
						}
					?>
				</dd>
				-->
				<?php if (!empty($item['Item']['690'])) { ?>
				<dt style="width: 120px"><?php __('Siglo:');?></dt>
				<dd style="margin-left: 130px">
					<?php
						$century = marc21_decode($item['Item']['690']);
						echo $century['a'];
					?>
				</dd>
				<?php } ?>
				<?php if (!empty($item['Item']['653'])) { ?>
				<dt style="width: 120px"><?php __('Materia:');?></dt>
				<dd style="margin-left: 130px">
				<?php
					$century = marc21_decode($item['Item']['653']);
					echo $century['a'];
				?>
				</dd>
				<?php } ?>
				<dt style="width: 120px">
				<?php if (($this->Session->check('Auth.User') && ($this->Session->read('Auth.User.group_id') != '3'))) { ?>
					<?php //echo $this->Html->link(__('Delete', true), array('action' => 'delete', $item['Item']['id']), null, sprintf(__("¿Desea eliminar '%s'?", true), $item['Item']['title'])); ?>
				<?php } ?>
				</dt>
				<dd style="margin-left: 130px"></dd>
				<!--
				<dt style="width: 120px"><?php __('Century:');?></dt>
				<dd style="margin-left: 130px">	</dd>-->
			</dl>
		</td>
		</tr>
		<?php endforeach; ?>
		</table>
		<?php } else { ?>
			<br />
			<p>No hay libros de autores con esa inicial.</p>
			<br /><br /><br /><br /><br />
		<?php } ?>
		
		<?php if ($this->Paginator->params['paging']['Item']['pageCount'] > 1) { ?>
		<div class="pagination" align="center">
			<ul>
				<?php echo $this->Paginator->prev('<< ' . __('previous', true), array('tag'=>'li', 'separator' => ''), null, array('class'=>'disabled', 'tag'=>'li', 'separator' => ''));?>
				<?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li'));?>
				<?php echo $this->Paginator->next(__('next', true) . ' >>', array('tag'=>'li', 'separator' => ''), null, array('class' => 'disabled', 'tag'=>'li', 'separator' => ''));?>
			</ul>
		</div>
		<br />
		<?php } ?>
	</div>
	<div class="col-md-3 column">
		<br />
		<label><?php __('Autores por la Letra:'); ?></label>
		<br />
		<?php echo $this->Html->link('A', array('action' => 'author/A'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('B', array('action' => 'author/B'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('C', array('action' => 'author/C'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('D', array('action' => 'author/D'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('E', array('action' => 'author/E'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('F', array('action' => 'author/F'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('G', array('action' => 'author/G'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('H', array('action' => 'author/H'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('I', array('action' => 'author/I'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('J', array('action' => 'author/J'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('K', array('action' => 'author/K'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('L', array('action' => 'author/L'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('M', array('action' => 'author/M'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('N', array('action' => 'author/N'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('O', array('action' => 'author/O'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('P', array('action' => 'author/P'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('Q', array('action' => 'author/Q'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('R', array('action' => 'author/R'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('S', array('action' => 'author/S'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('T', array('action' => 'author/T'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('U', array('action' => 'author/U'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('V', array('action' => 'author/V'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('W', array('action' => 'author/W'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('X', array('action' => 'author/X'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('Y', array('action' => 'author/Y'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('Z', array('action' => 'author/Z'), array('class' => 'btn-primary')); ?>
		<?php echo $this->Html->link('Todos', array('action' => 'author/'), array('class' => 'btn-primary', 'style' => 'width: 72px;')); ?>
	</div>
</div>