<ul>
	<li><?php echo html::link('Home'); ?></li>
	<li><?php echo html::link('The Company', array('company')); ?></li>
	<li><?php echo html::link('Products', array('products'), array('onClick'=>'return false;')); ?>
		<ul>
			<li><?php echo html::link('Paints base of water and solvent', array('products1')); ?></li>
			<li><?php echo html::link('Lacs base of water and solvent', array('products2')); ?></li>
			<li><?php echo html::link('Patina base of water and solvent', array('products3')); ?></li>
			<li><?php echo html::link('Primers &amp; coating base of water', array('products4')); ?></li>
			<li><?php echo html::link('Primers &amp; coatings base of solvent', array('products5')); ?></li>
			<li><?php echo html::link('Maintenance and protection of wood', array('products6')); ?></li>
		</ul>
	</li>
	<li><?php echo html::link('Special Effects', array('effects'), array('onClick'=>'return false;')); ?>
		<ul>
			<li><?php echo html::link('Iron oxidation effect', array('effects01')); ?></li>
			<li><?php echo html::link('Aged bronze effects', array('effects02')); ?></li>
			<li><?php echo html::link('Aged copper effect', array('effects03')); ?></li>
			<li><?php echo html::link('Aged leather effect', array('effects04')); ?></li>
			<li><?php echo html::link('Corten steel oxidation effect', array('effects05')); ?></li>
			<li><?php echo html::link('High sparkling metal', array('effects06')); ?></li>
			<li><?php echo html::link('Iridescent effect', array('effects07')); ?></li>
			<li><?php echo html::link('Thermosensitive effect', array('effects08')); ?></li>
			<li><?php echo html::link('Fluorescent effect', array('effects09')); ?></li>
			<li><?php echo html::link('Blackboard effect', array('effects10')); ?></li>
			<li><?php echo html::link('Photoluminescent effect', array('effects11')); ?></li>
			<li><?php echo html::link('Pearl effect', array('effects12')); ?></li>
			<li><?php echo html::link('Brushed anodized metal effect', array('effects13')); ?></li>
			<li><?php echo html::link('Reflective effect', array('effects14')); ?></li>
			<li><?php echo html::link('Soft rubber touch effect', array('effects15')); ?></li>
			<li><?php echo html::link('Liquid crystal effect', array('effects16')); ?></li>
			<li><?php echo html::link('Concrete effect', array('effects17')); ?></li>
		</ul>
	</li>
	<li><?php echo html::link('Contact', array('contact')); ?></li>
</ul>


