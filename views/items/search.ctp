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

//debug($items);
//debug($this->data);
//debug($this->passedArgs);
//debug($this->Session->read());
?>
<style>
	.btn-primary {
		width: 200px;
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
  <li>Buscar</li>
  <li><?php echo $search; ?></li>
</ul>

<div class='items view'>

<div class="col-md-9 column">

	<h2><?php __('Resultados de la Búsqueda');?></h2>
	<table class="table">
	<tr>
		<th><?php __('Portada');?></th>
		<th><?php __('Detalles de la Obra');?></th>
	</tr>
	<?php foreach ($items as $item): ?>
	<?php
		$t1 = $item['Item']['h-006'];
		$t2 = $item['Item']['h-007'];
		$controller = "/";
		
		// Tipo libro.
		if (($t1 == 'a') && ($t2 == 'm')) {
			$color = "#9dae8a";
			$controller = "books";
		}
		
		// Tipo revista.
		if (($t1 == 'a') && ($t2 == 's')) {
			$color = "#b3bbce";
			$controller = "magazines";
		}

		// Música impresa.
		if (($t1 == 'c') && ($t2 == 'm')) {
			$color = "#d5b59e";
			$controller = "";
		}
		
		// Música manuscrita.
		if (($t1 == 'd') && ($t2 == 'm')) {
			$color = "#aea16c";
			$controller = "";
		}
		
		// Iconografía musical.
		if (($t1 == 'k') && ($t2 == 'm')) {
			$color = "#ba938e";
			$controller = "";
		}
		
		// Trabajos académicos.
		if (($t1 == 'a') && ($t2 == 'a')) {
			$color = "#d1c7be";
		}
	?>
	<tr>
		<td style="background-color: <?php echo $color; ?>; text-align: center; width: 80px;">
					<?php
				if (($item['Item']['cover_name']) && (file_exists($_SERVER['DOCUMENT_ROOT'] . "/".$this->base."/webroot/covers/" . $item['Item']['cover_path']))){
					echo $this->Html->image("/webroot/covers/" . $item['Item']['cover_path'], array('title' => 'Haga click para ver los detalles.', 'width' => '70px', 'url' => array('controller' => 'books', 'action' => 'view', $item['Item']['id'])));
				} else {
					echo $this->Html->image("/webroot/img/sin_portada.jpg", array('title' => 'Haga click para ver los detalles.', 'width' => '70px', 'url' => array('controller' => 'books', 'action' => 'view', $item['Item']['id'])));
				}
			?>
		</td>
		<td>
			<dl class="dl-horizontal">
				<dt style="width: 120px">
					<?php __('Title:');?>
				</dt>
				<dd style="margin-left: 130px">
					<?php
						if (!empty($item['Item']['245'])) {
							$title = marc21_decode($item['Item']['245']);
							if ($title) {
								echo $title['a'] . '.';

								if (isset($title['b'])) {echo ' <i>' . $title['b'] . '.</i>';}
								if (isset($title['c'])) {echo ' ' . $title['c']. '.';}
								if (isset($title['h'])) {echo ' ' . $title['h']. '.';}
							}
						}
					?>
				</dd>
				<dt style="width: 120px">
					<?php __('Author:');?>
				</dt>
				<dd style="margin-left: 130px">
					<?php
						if (!empty($item['Item']['100'])) {
							$author = marc21_decode($item['Item']['100']);
							echo $author['a']. '.';
							if (isset($author['d'])) {echo ' ' . $author['d']. '.';}
						}
					?>
				</dd>
				<?php if (!empty($item['Item']['653'])) { ?>
				<dt style="width: 120px"><?php __('Materia:');?></dt>
				<dd style="margin-left: 130px">
				<?php
					$matter = marc21_decode($item['Item']['653']);
					echo $matter['a'] . '.';
				?>
				</dd>
				<?php } ?>
				<?php if (!empty($item['Item']['690'])) { ?>
				<dt style="width: 120px"><?php __('Siglo:');?></dt>
				<dd style="margin-left: 130px">
				<?php
					$century = marc21_decode($item['Item']['690']);
					echo $century['a'] . '.';
				?>
				</dd>
				<?php } ?>
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
				<dt style="width: 120px"><?php __('Tipo:');?></dt>
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
			</dl>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>

	<?php if ($this->Paginator->params['paging']['Item']['pageCount'] > 1) { ?>
	<div class="pagination" align="center">
		<ul>
			<?php echo $this->Paginator->prev('<< ' . __('previous', true), array('tag'=>'li', 'separator' => ''), null, array('class'=>'disabled', 'tag'=>'li', 'separator' => ''));?>
			<?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li'));?>
			<?php echo $this->Paginator->next(__('next', true) . ' >>', array('tag'=>'li', 'separator' => ''), null, array('class' => 'disabled', 'tag'=>'li', 'separator' => ''));?>
		</ul>
	</div><br />
	<?php } ?>
</div>
<?php if (($this->Session->check('Auth.User')) && ($this->Session->read('Auth.User.group_id') != 3)) { ?>
<div class="col-md-3 column">
	<?php if (($this->Session->check('Auth.User') && ($this->Session->read('Auth.User.group_id') != '3'))) { ?>
			<br />
			<label><?php __('Acciones:'); ?></label>
			<br />
			<?php echo $this->Html->link(__('Agregar Libro', true), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
			<br /><br />
		<?php } ?>
</div>
<?php } ?>
</div>