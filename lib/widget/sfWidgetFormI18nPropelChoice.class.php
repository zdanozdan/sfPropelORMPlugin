<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormI18nPropelChoice represents a choice widget for a model with auto translation.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormPropelChoice.class.php 22261 2009-09-23 05:31:39Z fabien $
 */
class sfWidgetFormI18nPropelChoice extends sfWidgetFormPropelChoice
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * model:       The model class (required)
   *  * add_empty:   Whether to add a first empty value or not (false by default)
   *                 If the option is not a Boolean, the value will be used as the text value
   *  * method:      The method to use to display object values (__toString by default)
   *  * key_method:  The method to use to display the object keys (getPrimaryKey by default)
   *  * order_by:    An array composed of two fields:
   *                   * The column to order by the results (must be in the PhpName format)
   *                   * asc or desc
   *  * query_methods: An array of method names listing the methods to execute
   *                 on the model's query object
   *  * criteria:    A criteria to use when retrieving objects
   *  * connection:  The Propel connection to use (null by default)
   *  * multiple:    true if the select tag must allow multiple selections
   *  * peer_method: ignored - only supported for BC purpose
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('i18n');
    $this->addOption('i18n_method', '__');
    $this->addOption('truncate', 30);

    parent::configure($options, $attributes);
  }

  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $i18n = $this->getOption('i18n');
    $i18n_method = $this->getOption('i18n_method');

    if (!method_exists($i18n, $i18n_method))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be i18n rendered in a "%s" widget', $i18n, $i18n_method, __CLASS__));
    }

    $choices = parent::getChoices();
    $choices_i18n = array();

    sfContext::getInstance()->getConfiguration()->loadHelpers('Text');

    foreach ($choices as $key=>$choice)
    {
      if(is_array($choice))
	{
	  $choices_i18n[$key] = truncate_text(call_user_func_array(array($i18n, $i18n_method),array($choice['i18n'],$choice['params'])),$this->getOption('truncate'));
	}
      else
	{
	  $choices_i18n[$key] = truncate_text(call_user_func_array(array($i18n, $i18n_method),array($choice)),$this->getOption('truncate'));
	}
    }

    return $choices_i18n;
  }
}
