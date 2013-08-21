<?php
/*  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2008-2010 (original work) Deutsche Institut für Internationale Pädagogische Forschung (under the project TAO-TRANSFER);
 *               2009-2012 (update and modification) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 * 
 */

/**
 * Helper to generate simple tree forms, that allow a user
 * to modify properties that have a resource range or domain 
 *
 * @author Joel Bout, <joel@taotesting.com>
 * @package tao
 * @see core_kernel_classes_* packages
 * @subpackage helpers_form
 */
class tao_helpers_form_GenerisTreeForm extends Renderer
{
	
	/**
	 * Generates a form to define the values of a specific property for a resource
	 * 
	 * @param core_kernel_classes_Resource $resource
	 * @param core_kernel_classes_Property $property
	 * @return tao_helpers_form_GenerisTreeForm
	 */
    public static function buildTree(core_kernel_classes_Resource $resource, core_kernel_classes_Property $property) {
		$tree = new self($resource, $property);
		
		$range = $property->getRange();
		$tree->setData('rootNode',		$range->getUri());
		$tree->setData('dataUrl',		_url('getData', 'GenerisTree', 'tao'));
		$tree->setData('saveUrl',		_url('setValues', 'GenerisTree', 'tao'));
		
		$values = $resource->getPropertyValues($property);
		$tree->setData('values', $values);
		$openNodeUris = tao_models_classes_GenerisTreeFactory::getNodesToOpen($values, $range); 
		$tree->setData('openNodes',		$openNodeUris);
		return $tree;
	}
	
	/**
	 * Generates a form to define the reverse values of a specific property for a resource
	 * This allows to set/remove multiple triples that share the same object   
	 * 
	 * @param core_kernel_classes_Resource $resource
	 * @param core_kernel_classes_Property $property
	 * @return tao_helpers_form_GenerisTreeForm
	 */
	public static function buildReverseTree(core_kernel_classes_Resource $resource, core_kernel_classes_Property $property) {
		$tree = new self($resource, $property);
		
		$domainCollection = $property->getDomain();
		if (!$domainCollection->isEmpty()) {
    		$domain = $domainCollection->get(0);
    		$tree->setData('rootNode',		$domain->getUri());
    		$tree->setData('dataUrl',		_url('getData', 'GenerisTree', 'tao'));
    		$tree->setData('saveUrl',		_url('setReverseValues', 'GenerisTree', 'tao'));
    		
    		$values = array_keys($domain->searchInstances(array(
    			$property->getUri() => $resource
    		), array('recursive' => true, 'like' => false)));
    		
    		$tree->setData('values', $values);
    		$openNodeUris = tao_models_classes_GenerisTreeFactory::getNodesToOpen($values, $domain); 
    		$tree->setData('openNodes',		$openNodeUris);
		}
		return $tree;
	}
	
	/**
	 * Should not be called directly but is public
	 * since Renderer is public
	 * 
	 * @param core_kernel_classes_Resource $resource
	 * @param core_kernel_classes_Property $property
	 */
	public function __construct(core_kernel_classes_Resource $resource, core_kernel_classes_Property $property) {
		$tpl = common_ext_ExtensionsManager::singleton()->getExtensionById('tao')->getConstant('TPL_PATH')
			.'form'.DIRECTORY_SEPARATOR.'generis_tree_form.tpl';
		parent::__construct($tpl);
		
		$this->setData('id',			'uid'.md5($property->getUri().$resource->getUri()));
		$this->setData('title',			$property->getLabel());
		
		$this->setData('resourceUri',	$resource->getUri());
		$this->setData('propertyUri',	$property->getUri());

	}
	
	/**
	 * (non-PHPdoc)
	 * @see Renderer::render()
	 */
	public function render() {
		return parent::render();
	}
}