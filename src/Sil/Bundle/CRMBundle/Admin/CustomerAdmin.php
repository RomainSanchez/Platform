<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\CRMBundle\Admin;

use Sil\Bundle\CRMBundle\CodeGenerator\CustomerCodeGenerator;
use Sil\Bundle\CRMBundle\Admin\OrganismAdmin as BaseOrganismAdmin;
use Sil\Bundle\CRMBundle\Form\DataTransformer\CustomerCodeTransformer;
use Sonata\AdminBundle\Form\FormMapper;

class CustomerAdmin extends BaseOrganismAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.crm.customer';

    /**
     * @var CustomerCodeGenerator
     */
    private $codeGenerator;

    protected $baseRouteName = 'admin_crm_customer';
    protected $baseRoutePattern = 'crm/customer';

    protected $classnameLabel = 'Customer';

    public function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        $this->renameFormGroup('form_group_organism', 'form_tab_general', 'form_group_customer');
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->andWhere('o.isCustomer = true');

        return $query;
    }

    /**
     * @param FormMapper $mapper
     */
    protected function postConfigureFormFields(FormMapper $mapper)
    {
        parent::postConfigureFormFields($mapper);
        $mapper->get('customerCode')->addViewTransformer(new CustomerCodeTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $object = parent::getNewInstance();

        $object->setisCustomer(true);
        $object->setCustomerCode($this->codeGenerator->generate($object));

        return $object;
    }

    /**
     * @param CustomerCodeGenerator $codeGenerator
     */
    public function setCodeGenerator(CustomerCodeGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }
}
