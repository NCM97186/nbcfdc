<?php

/**
 * @file
 * Contains \Drupal\easy_sitemap\Controller\EasySitemapController.
 */
namespace Drupal\easy_sitemap\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Response;

class EasySitemapController extends ControllerBase {
	public function content() {		
		$menu_name = 'main';
		$menu_tree = \Drupal::menuTree();
		$parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
		$parameters->setMinDepth(0);
		$parameters->setMaxDepth(3);
		$tree = $menu_tree->load($menu_name, $parameters);
		$manipulators = array(
		  array('callable' => 'menu.default_tree_manipulators:checkAccess'),
		  array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
		);
		$menu_tree2 = $menu_tree->transform($tree, $manipulators);

		$settings = $this->config('easy_sitemap.settings');
		$str = '';
		foreach(array_reverse($settings->get('menu_list')) as $val => $key){
			$sub_nav = \Drupal::menuTree()->load($val, new \Drupal\Core\Menu\MenuTreeParameters());
			$manipulators = array(
				array('callable' => 'menu.default_tree_manipulators:checkAccess'),
				array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
			);
			$sub_nav = \Drupal::menuTree()->transform($sub_nav, $manipulators);
			$output = array();
			$output = $this->_generateSubMenuTree($output, $sub_nav);
			
			$str .= '<ul class="easy-sitemap">';
			foreach ($output as $key => $value){
				$str .= '<li><a title="'.$value['name'].'" href="'.$value['url_str'].'">'.$value['name'].'</a>';
				if(array_key_exists("child",$value)){
					$i = 0;
					foreach($value['child'] as $k => $val){
						if($i == 0){
							$str .= '<ul>';
						}
						$str .= '<li><a  title="'.$val['name'].'" href="'.$val['url_str'].'">'.$val['name'].'</a></li>';
						
						$i++;
					}
					$str .= '</ul>';
				}
				$str .= '</li>';
			}
			$str .= '</ul>';
		}
		return array(
		  '#type' => 'markup',
		  '#markup' => $str,
		);
	}
	
	private function _generateSubMenuTree(&$output, &$input, $parent = FALSE) {
		$input = array_values($input);
		foreach ($input as $key => $item) {
			//If menu element disabled skip this branch
			if ($item->link->isEnabled()) {
				$name = $item->link->getTitle();
				$url = $item->link->getUrlObject();
				$url_string = $url->toString();
				//If not root element, add as child
				if ($parent === FALSE) {
					$output[$key] = [
						'name' => $name,
						'tid' => $key,
						'url_str' => $url_string
					];
				} else {
					$parent = 'submenu-' . $parent;
					
					$output['child'][$key] = [
						'name' => $name,
						'tid' => $key,
						'url_str' => $url_string
					];
				}
				if ($item->hasChildren) {
					if ($item->depth == 1) {
						$this->_generateSubMenuTree($output[$key], $item->subtree, $key);
					} else {
						$this->_generateSubMenuTree($output['child'][$key], $item->subtree, $key);
					}
				}
			}
		}
		return $output;
	}
}