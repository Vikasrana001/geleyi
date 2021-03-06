<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
 echo json_encode(
	array(
		'monitor_404' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 92,
				'show_in_menu' => false,
				'title' => __('Monitor 404', $psp->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $psp->alias . "_mass404Monitor")
			),
			'desciption' => __('On this module you can see what URLs are referring visitors to 404 pages, how many hits it had and redirect them to another page.', $psp->localizationName),
			'module_init' => 'init.php',
			
			'shortcodes_btn' => array(
				'icon' 	=> 'assets/menu_icon.png',
				'title'	=> __('Insert Monitor 404 sh', $psp->localizationName)
			)
		)
	)
 );