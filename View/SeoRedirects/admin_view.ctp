<?php $this->assign('title', __('Uri'));?>
<div class="row-fluid">
	<div class="span9">
	<h2><?php echo __('Seo Redirect');?></h2>
		<dl><?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['id']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Seo Uri'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $this->Html->link($seoRedirect['SeoUri']['uri'], array('controller' => 'seo_uris', 'action' => 'view', $seoRedirect['SeoUri']['id'])); ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Redirect'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['redirect']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Priority'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['priority']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Is Active'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['is_active']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Callback'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['callback']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['created']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $seoRedirect['SeoRedirect']['modified']; ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
        <div class="widget no-margin">
            <div class="widget-header">
                <div class="title">
                    <?php echo __('Actions'); ?>
                </div>
            </div>
            <div class="widget-body">
                <ul>
                    <li><?php echo $this->Html->link(__('Edit Seo Redirect'), array('action' => 'edit', $seoRedirect['SeoRedirect']['id'])); ?> </li>
                    <li><?php echo $this->Html->link(__('Delete Seo Redirect'), array('action' => 'delete', $seoRedirect['SeoRedirect']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $seoRedirect['SeoRedirect']['id'])); ?> </li>
					<li><?php echo $this->Html->link(__('New Seo Redirect'), array('action' => 'add')); ?> </li>
                </ul>
            </div>
        </div>
    </div>
</div>